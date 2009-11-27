/**
 * study_deck.js
 */


// Constants
var RATING_MAP = new Array("no rating","easy","medium","hard");
var VERACITY_MAP = new Array("incorrect","correct");
var MODE_STUDY = "study";
var MODE_QUIZ = "quiz";
var NULL_ID = "null";

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
    this.resultsId = NULL_ID;
    this.totalCorrect = 0;
    this.totalIncorrect = 0;
    this.lastAnswer = 0;

    // Rating table fields
    this.ratingId = NULL_ID;
    this.rating = 0;

    // JS event-handler fields
    this.correct = NULL_ID;
  }

  /***
   *
   * Card methods
   *
   */

  Card.prototype.setCorrect = function() {
    this.correct = 1;
  }

  Card.prototype.setIncorrect = function() {
    this.correct = 0;
  }

  Card.prototype.updateTotal = function() {
    if(this.correct != NULL_ID) {
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
   *
   * Deck class
   *
   */
  function Deck(deckData,cardData,cardRatingsData,cardResultsData) {

    // Deck DB fields
    this.id = NULL_ID;
    this.deckName = '';
    this.userId = '';

    this.curCard = null;
    this.viewedCards = new Array();
    this.unviewedCards = new Array();
    this.numTotalCards = 0;

    // Set deck meta fields
    if(deckData.Deck) {
        this.id = deckData.Deck.id;
        this.deckName = deckData.Deck.deck_name;
        this.userId = deckData.Deck.user_id;
    }

    // Read cards from JSON object cardData
    for (var i=0; i<cardData.length; i++) {

        // Set properties of new card
        var newCard = new Card(cardData[i].Card.id,cardData[i].Card.question,cardData[i].Card.answer);

        // Check for rating in cardRatingsData
        if(cardRatingsData[newCard.id]) {
            newCard.setRatingId(cardRatingsData[newCard.id].id);
            newCard.setRating(cardRatingsData[newCard.id].rating);
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
  Deck.prototype.getCard = function() {
    return this.curCard;
  }

  Deck.prototype.sendUpdate = function(card) {

    if(!card) {
        alert("sendUpdate: card is null");
        return;
    }

    // Build url data string
    var dataStr = "did=" + this.id;
    dataStr += "&cid=" + card.id;
    dataStr += "&rid=" + card.ratingId;
    dataStr += "&sid=" + card.resultsId;
    dataStr += "&rating=" + card.rating;
    dataStr += "&correct=" + card.correct;
    //alert(dataStr);

    // Debug window
    //var newWindow = window.open('','mywin','height=500,width=600,scrollbars=yes');

    $.ajax({
        type: "GET",
        url: "/studydeck/decks/update",
        data: dataStr,
        success: function(msg) {
            //newWindow.document.write(msg);
            //alert("SUCCESS: " + msg);
        }
    });
  }

  Deck.prototype.getNextCard = function() {
    if(this.unviewedCards.length > 0) {
        if(this.curCard) {
            // Update JS object totals
            this.curCard.updateTotal();

            // Update DB with card info
            this.sendUpdate(this.curCard);

            // Push card on viewed cards stack
            this.viewedCards.push(this.curCard);
        }
        this.curCard = this.unviewedCards.pop();
    } 
    return this.curCard;
  }

  Deck.prototype.getPreviousCard = function() {
    if(this.viewedCards.length > 0) {
        if(this.curCard) {
            // Update JS object totals
            this.curCard.updateTotal();

            // Update DB with card info
            this.sendUpdate(this.curCard);

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

        //var MODE = "study";
        //var MODE = "quiz";

        // Call build UI functions
        if(MODE == MODE_STUDY) {
          this.renderStudyWindows();
        }
        else if(MODE == MODE_QUIZ) {
          this.renderQuizButtons();
        }

        // dummy data
        //this.deck = new Deck("SAN Vocab", 1);

        // Pass global deck JSON data
        this.deck = new Deck(deckData,cardData,cardRatingsData,cardResultsData);

        // Set the title
        $("h1.title").text(this.deck.deckName);

        // Reset form items
        $("#show_answer_checkbox").attr('checked',false);

        // Temp start things up
        this.showCard(this.deck.getNextCard());
    },

    // Builds UI
    'renderQuizButtons':function() {
        $("#row_bottom").prepend("<div id=\"incorrect_button\" class=\"left_button\">incorrect</div>");
        $("#row_bottom").prepend("<div id=\"correct_button\" class=\"right_button\">correct</div>");
    },

    'renderStudyWindows':function() {

      var optionsBox = $('<div class=\"margin_box\"></div>')
                        .append("<span class=\"title\">Options</span>")
                        .append("<label for=\"show_answer_checkbox\">Show Answer?</label>")
                        .append("<input type=\"checkbox\" id=\"show_answer_checkbox\" name=\"show_answer\" value=\"show_answer\" />")
                        .prependTo('#left_margin_wrap');

      var statsTable = $("<table class=\"quiz_history\">")
                        .append("<tr><td># Times Correct</td><td id=\"card_total_correct\"></td></tr>")
                        .append("<tr><td># Times Incorrect</td><td id=\"card_total_incorrect\"></td></tr>")
                        .append("<tr><td>Last Answer</td><td id=\"card_last_answer\"></td></tr>");

      var cardHistoryBox = $('<div class=\"margin_box\"></div>')
                        .append("<span class=\"title\">Card Quiz History</span>") 
                        .append(statsTable)
                        .prependTo('#left_margin_wrap');

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
        //this.resetButtons();
        //$("#correct_button").css("background","#B3ECFF");

        // Advance to next card
        this.next();
    },

    'incorrect':function() {

        // Set the card incorrect flag
        this.deck.getCard().setIncorrect();
        
        // Change style of incorrect button
        //this.resetButtons();
        //$("#incorrect_button").css("background","#B3ECFF");

        // Advance to next card
        this.next();
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

    // Start
    DeckViewerUI.init();

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


  });  // end $(document).ready(function()
