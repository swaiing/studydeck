/**
 * deck_class.js
 *
 */

  /***
   *
   * Card class
   *
   */
  function Card(id, did, question, answer) {

    // Constants
    this.RATING_MAP = new Array("unrated","easy","medium","hard");
    this.DEFAULT_RATING = 3;
    this.NULL_ID = "null";

    // Card table fields
    this.id = id;
    this.question = question;
    this.answer = answer;

    // Store deck ID for AJAX
    this.deckId = did;

    // Results table fields
    this.resultsId = this.NULL_ID;
    this.totalCorrect = 0;
    this.totalIncorrect = 0;
    this.lastAnswer = 0;

    // Rating table fields
    this.ratingId = this.NULL_ID;
    this.rating = this.DEFAULT_RATING;

    // JS event-handler fields
    this.correct = this.NULL_ID;
  }

  /***
   *
   * Card methods
   *
   */

  Card.prototype.getRatingStr = function() {
    return this.RATING_MAP[this.rating];
  }

  // UI triggered by RatingSelector to update rating
  Card.prototype.setRatingFromStr = function(ratingStr) {
    var i = jQuery.inArray(ratingStr, this.RATING_MAP);
    if(i != -1) {
        this.rating = i;
        this.notifyRating();
    }
  }

  // UI triggered by correct button to set correct
  Card.prototype.setCorrect = function() {
    this.correct = 1;
    this.notifyResult();
  }

  // UI triggered by incorrect button to set incorrect
  Card.prototype.setIncorrect = function() {
    this.correct = 0;
    this.notifyResult();
  }

  Card.prototype.updateTotal = function() {
    if(this.correct != this.NULL_ID) {
        if(this.correct) this.totalCorrect++; 
        else this.totalIncorrect++;
    }
  }

  Card.prototype.setResultsId = function(id) {
    this.resultsId = id;
  }

  Card.prototype.setRating = function(rating) {
    this.rating = rating;
  }

  Card.prototype.setRatingId = function(id) {
    this.ratingId = id;
  }

  Card.prototype.setLastAnswer = function(lastAnswer) {
    this.lastAnswer = lastAnswer;
  }

  /**
   * AJAX call to update rating
   */
  Card.prototype.notifyRating = function() {

    // Build url data string
    var dataStr = "did=" + this.deckId;
    dataStr += "&cid=" + this.id;
    dataStr += "&rid=" + this.ratingId;
    dataStr += "&rating=" + this.rating;
    //alert(dataStr);

    // Debug window
    //var newWindow = window.open('','mywin','height=500,width=600,scrollbars=yes');

    $.ajax({
        type: "GET",
        url: "/studydeck/decks/updateRating",
        data: dataStr,
        success: function(msg) {
            //newWindow.document.write(msg);
            //alert("SUCCESS: " + msg);
        }
    });
    return true;
  }

  /**
   * AJAX call to update result
   */
  Card.prototype.notifyResult = function() {

    // Build url data string
    var dataStr = "did=" + this.deckId;
    dataStr += "&cid=" + this.id;
    dataStr += "&sid=" + this.resultsId;
    dataStr += "&correct=" + this.correct;
    //alert(dataStr);

    // Debug window
    //var newWindow = window.open('','mywin','height=500,width=600,scrollbars=yes');

    $.ajax({
        type: "GET",
        url: "/studydeck/decks/updateResult",
        data: dataStr,
        success: function(msg) {
            //newWindow.document.write(msg);
            //alert("SUCCESS: " + msg);
        }
    });
    return true;
  }

  /**
   *
   * Deck class
   *
   */
  function Deck(deckData, cardData, cardResultsData) {

    // Constants
    this.NULL_ID = "null";

    // Deck DB fields
    this.id = this.NULL_ID;
    this.deckName = '';
    this.userId = '';

    this.curCard = null;
    this.viewedCards = new Array();
    this.unviewedCards = new Array();
    this.numTotalCards = 0;

    // Check for 'cardData' JSON object
    if(!(cardData && deckData)) {
        alert('JSON object(s) not found.');
        return false;
    }

    // Set deck meta fields
    if(deckData.Deck) {
        this.id = deckData.Deck.id;
        this.deckName = deckData.Deck.deck_name;
        this.userId = deckData.Deck.user_id;
    }

    // Read cards from JSON object "cardData"
    for (var i=0; i<cardData.length; i++) {

        // Set properties of new card
        var newCard = new Card(cardData[i].Card.id, this.id, cardData[i].Card.question, cardData[i].Card.answer);

        // Check for rating in cardRatingsData
        if(cardData[i].Rating.rating) {
            newCard.setRatingId(cardData[i].Rating.id);
            newCard.setRating(cardData[i].Rating.rating);
        }

        // Check for result in cardResultsData
        if(cardResultsData[newCard.id]) {
            newCard.setResultsId(cardResultsData[newCard.id].id);
            newCard.setLastAnswer(cardResultsData[newCard.id].last_guess);
            newCard.totalCorrect = cardResultsData[newCard.id].total_correct;
            newCard.totalIncorrect = cardResultsData[newCard.id].total_incorrect;
        }

        // Add card to deck
        this.unviewedCards.push(newCard);
    }

    // Reverse order
    this.unviewedCards.reverse();

    // Set the number of total cards
    this.numTotalCards = this.unviewedCards.length;

  } // end function Deck(name,userId, deckData)

  /**
   *
   * Deck methods
   *
   */

  // Returns card by card_id otherwise current card
  Deck.prototype.getCard = function(id) {

    // Return current card
    if(!id) {
        return this.curCard;
    }

    // Find card by ID
    var i = 0;
    var t = null;
    for(i=0; i<this.unviewedCards.length; i++) {
        t = this.unviewedCards[i];
        if(t.id == id) return t;
    }
    for(i=0; i<this.viewedCards.length; i++) {
        if(t.id == id) return t;
    }
    return null;
  }

  // Advance current card
  Deck.prototype.getNextCard = function() {
    if(this.unviewedCards.length > 0) {
        if(this.curCard) {
            // Update JS object totals
            this.curCard.updateTotal();

            // Push card on viewed cards stack
            this.viewedCards.push(this.curCard);
        }

        this.curCard = this.unviewedCards.pop();
    } 
    else {
        return null;
    }
    return this.curCard;
  }

  // Go back to previous card
  Deck.prototype.getPreviousCard = function() {
    if(this.viewedCards.length > 0) {
        if(this.curCard) {
            // Update JS object totals
            this.curCard.updateTotal();

            // Push card on viewed cards stack
            this.unviewedCards.push(this.curCard);
        }
        this.curCard = this.viewedCards.pop();
    } 
    return this.curCard;
  }

  Deck.prototype.getNumViewed = function() {
    return this.viewedCards.length+1;
  }

  Deck.prototype.getNumCards = function() {
    return this.numTotalCards;
  }

  /**
   * AJAX call to trigger save
   */
  Deck.prototype.writeSession = function() {

    var commitType = "COMMIT_RATING";

    // Build url data string
    var dataStr = "did=" + this.id;
    dataStr += "&save=" + commitType;
    //alert(dataStr);

    // Debug window
    //var newWindow = window.open('','mywin','height=500,width=600,scrollbars=yes');

    $.ajax({
        type: "GET",
        url: "/studydeck/decks/updateSave",
        data: dataStr,
        success: function(msg) {
            //newWindow.document.write(msg);
            //alert("SUCCESS: " + msg);
        }
    });
    return true;
  }
