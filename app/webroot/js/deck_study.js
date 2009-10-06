/**
 * study_deck.js
 */


// Constants
var RATING_MAP = new Array("no rating","easy","medium","hard");
var VERACITY_MAP = new Array("incorrect","correct");

  /***
   *
   * Card class
   *
   */
  function Card(id,question,answer) {

    // Card table fields
    this.id = id;
    this.question = question;
    this.answer = answer;

    // Results table fields
    this.totalCorrect = 0;
    this.totalIncorrect = 0;
    this.lastAnswer = 0;

    // Rating table fields
    this.rating = 0;

    // JS event-handler fields
    this.correct = 0;
  }

  /***
   *
   * Card methods
   *
   */

  Card.prototype.setQuestion = function(question) {
    this.question = question;
  }

  Card.prototype.setAnswer = function() {
    this.correct = answer;
  }

  Card.prototype.setCorrect = function() {
    this.correct = 1;
  }

  Card.prototype.setIncorrect = function() {
    this.correct = 0;
  }

  Card.prototype.setRating = function(rating) {
    this.rating = rating;
  }

  Card.prototype.setLastAnswer = function(lastAnswer) {
    this.lastAnswer = lastAnswer;
  }

  /**
   *
   * Deck class
   *
   */
  function Deck(name,userId,deckData) {

    // Deck DB fields
    this.deckName = name;
    this.userId = userId;

    this.curCard = null;
    this.viewedCards = new Array();
    this.unviewedCards = new Array();
    this.numTotalCards = 0;

    // Read cards from JSON object deckData
    for (var i=0; i<deckData.length; i++) {

        // Set properties of new card
        var newCard = new Card(i,deckData[i].Card.question,deckData[i].Card.answer);

        // Check for Rating object
        if(deckData[i].Rating.length > 0) {
            newCard.setRating(deckData[i].Rating[0].rating);
        }

        // Check for Result object
        if(deckData[i].Result.length > 0) {
            newCard.totalCorrect = deckData[i].Result[0].total_correct;
            newCard.totalIncorrect = deckData[i].Result[0].total_incorrect;
        }

        // Add card to deck
        this.unviewedCards.push(newCard);
    }

    // Set the number of total cards
    this.numTotalCards = this.unviewedCards.length;

  } // end function Deck(name,userId, deckData)

  /**
   *
   * Deck methods
   *
   */
  Deck.prototype.getCard = function() {
    return this.curCard;
  }

  Deck.prototype.getNextCard = function() {
    if(this.unviewedCards.length > 0) {
        if(this.curCard) {
            this.viewedCards.push(this.curCard);
        }
        this.curCard = this.unviewedCards.pop();
    } 
    return this.curCard;
  }

  Deck.prototype.getPreviousCard = function() {
    if(this.viewedCards.length > 0) {
        if(this.curCard) {
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
  *
  * DeckViewerUI Object
  * 
  * Object literal that wraps a Deck Object and binds the UI to it.
  *
  */
  DeckViewerUI = {

    // Instance variables
    deck:null,
    isShowingAnswer:0,

    // Bootstrap intialize function
    'init':function() {
        // dummy data
        //this.deck = new Deck("SAN Vocab", 1);

        // Pass global deck JSON data
        this.deck = new Deck(deckName,deckUser,deckData);

        // Reset form items
        $("#show_answer_checkbox").attr('checked',false);

        // Temp start things up
        this.showCard(this.deck.getNextCard());
    },

    // Helper functions
    'showCard':function(card) {
        if(!card) {
            alert("Null card in DeckViewerUI.showCard");
            return;
        }

        // Hide answer field if showAnswer bit set false
        if(!this.isShowingAnswer) {
            $("#card_answer").hide();
        }

        // Set rating field
        var ratingStr = RATING_MAP[card.rating];
        $("#card_rating").text(ratingStr);

        // Set question field
        $("#card_question").text(card.question);

        // Set answer field
        $("#card_answer").text(card.answer);

        // Update deck progress
        var progressStr = this.deck.getNumViewed() + "/" + this.deck.getNumCards();
        $("#deck_progress").text(progressStr);

        // Set card metrics
        $("#card_total_correct").text(card.totalCorrect);
        $("#card_total_incorrect").text(card.totalIncorrect);
        var lastAnswerStr = VERACITY_MAP[card.lastAnswer];
        $("#card_last_answer").text(lastAnswerStr);

    },

    'showAnswer':function() {
        
        // Show answer field
       $("#card_answer").fadeIn("fast");

    },

    'resetButtons':function() {

        // Revert styles of correct/incorrect buttons
        $("#incorrect_button").css("background","red");
        $("#correct_button").css("background","green");

    },

    // Event handling functions
    'next':function() {
        
        // Get the next card
        this.deck.getNextCard();

        // Reset the display and show the card
        this.resetButtons();
        this.showCard(this.deck.getCard());
    },

    'previous':function() {

        // Get the previous card
        this.deck.getPreviousCard();

        // Reset the display and show the card
        this.resetButtons();
        this.showCard(this.deck.getCard());
    
    },

    'toggleRating':function() {
        
        // Get new rating
        var newRating = (this.deck.getCard().rating + 1)%RATING_MAP.length;
        this.deck.getCard().setRating(newRating);

        // Display new rating
        var ratingStr = RATING_MAP[this.deck.getCard().rating];
        $("#card_rating").text(ratingStr);

    },

    'correct':function() {

        // Set the card correct flag
        this.deck.getCard().setCorrect();

        // Change style of correct button
        this.resetButtons();
        $("#correct_button").css("background","#B3ECFF");
    },

    'incorrect':function() {

        // Set the card incorrect flag
        this.deck.getCard().setIncorrect();
        
        // Change style of incorrect button
        this.resetButtons();
        $("#incorrect_button").css("background","#B3ECFF");

    },

    'showAnswerToggle':function() {

        // Hide/Show answer
        if($("#show_answer_checkbox").is(':checked')) {
            this.isShowingAnswer = 1;
            $("#card_answer").fadeIn("fast");
        }
        else {
            this.isShowingAnswer = 0;
            $("#card_answer").hide();
        }
    }

  }; // end DeckViewerUI Object


  // jQuery document onload
  $(document).ready(function() {

    /**
     *  Bind event handlers
     */

    // Previous button
    $("#prev_button").click(function(event) { DeckViewerUI.previous(); });

    // Next button
    $("#next_button").click(function(event) { DeckViewerUI.next(); });

    // Change rating button
    $("#card_rating").click(function(event) { DeckViewerUI.toggleRating(); });

    // Reveal answer click
    $("#row_answer").click(function(event) { DeckViewerUI.showAnswer(); });

    // Set correct button
    $("#correct_button").click(function(event) { DeckViewerUI.correct(); });

    // Set incorrect button
    $("#incorrect_button").click(function(event) { DeckViewerUI.incorrect(); });

    // Set incorrect button
    $("#show_answer_checkbox").click(function(event) { DeckViewerUI.showAnswerToggle(); });

    // Start
    DeckViewerUI.init();

  });  // end $(document).ready(function()
