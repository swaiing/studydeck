/**
 * deck_study.js
 *
 */


// Constants
var RATING_MAP = new Array("unrated","easy","medium","hard");
var DEFAULT_RATING = 3; 
var VERACITY_MAP = new Array("incorrect","correct");
var MODE_STUDY = "study";
var MODE_QUIZ = "quiz";
var NULL_ID = "null";

  /***
   *
   * Card class
   *
   */
  function Card(id, did, question, answer) {

    // Card table fields
    this.id = id;
    this.question = question;
    this.answer = answer;

    // Store deck ID for AJAX
    this.deckId = did;

    // Results table fields
    this.resultsId = NULL_ID;
    this.totalCorrect = 0;
    this.totalIncorrect = 0;
    this.lastAnswer = 0;

    // Rating table fields
    this.ratingId = NULL_ID;
    this.rating = DEFAULT_RATING;

    // JS event-handler fields
    this.correct = NULL_ID;
  }

  /***
   *
   * Card methods
   *
   */

  Card.prototype.getRatingStr = function() {
    return RATING_MAP[this.rating];
  }

  // UI triggered by RatingSelector to update rating
  Card.prototype.setRatingFromStr = function(ratingStr) {
    var i = jQuery.inArray(ratingStr, RATING_MAP);
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

    // Deck DB fields
    this.id = NULL_ID;
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
  Deck.prototype.getCard = function() {
    return this.curCard;
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
    mode:MODE_STUDY,
    rts:null,

    // Bootstrap intialize function
    'init':function() {

        // Pass global deck JSON data
        this.deck = new Deck(deckData, cardData, cardResultsData);

        // Get first card
        var firstCard = this.deck.getNextCard();

        // Set the title
        //$("h1.title").text(this.deck.deckName);
        // TODO: Workaround for HTML entity references
        $("h1.title").html(this.deck.deckName);

        // Reset form items
        $("#show_answer_checkbox").attr('checked',true);
        this.showAnswerToggle();

        // Call build UI functions
        if(MODE == MODE_STUDY) {
          this.mode = MODE_STUDY;
          this.renderStudyWindows();

          // Initialize new RatingSelector object
          this.rts = new RatingSelector("#card_rating");

        }
        else if(MODE == MODE_QUIZ) {
          this.mode = MODE_QUIZ;
          this.renderQuizButtons();
        }

        // Load first card
        this.showCard(firstCard);
    },

    // Builds correct/incorrect buttons for quiz mode
    'renderQuizButtons':function() {
        $("#row_bottom").prepend("<div id=\"incorrect_button\" class=\"left_button\">incorrect</div>");
        $("#row_bottom").prepend("<div id=\"correct_button\" class=\"right_button\">correct</div>");
    },

    // Builds left-side windows for learn mode
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
        if(this.rts) {
            // Pass to RatingSelector
            this.rts.setCard(card);
        }
        else {
            // Display
            var eltStr = "<div class=\"rating_str\">" + card.getRatingStr() + "</div>";
            $("#card_rating").html(eltStr);
        }

        // Set question field
        //$("#card_question").text(card.question);
        // TODO: Workaround for HTML entity references
        $("#card_question").html(card.question);

        // Set answer field
        //$("#card_answer").text(card.answer);
        // TODO: Workaround for HTML entity references
        $("#card_answer").html(card.answer);

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
        var success = this.deck.getNextCard();

        // Animation card transition
        //$("div#mask").show();
        //$("div#mask").slideUp('fast');

        // Reset the display and show the card
        if(success) {
            this.resetButtons();
            this.showCard(this.deck.getCard());
        }
        // End of deck
        else {
            $("#card_question").text('End of StudyDeck');
            $("#card_answer").text('');
        }
    },

    'previous':function() {

        // Get the previous card
        this.deck.getPreviousCard();

        // Reset the display and show the card
        this.resetButtons();
        this.showCard(this.deck.getCard());
    
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

    // Reveal answer click
    $("#row_body").click(function(event) { DeckViewerUI.showAnswer(); });

    // Set correct button
    $("#correct_button").click(function(event) { DeckViewerUI.correct(); });

    // Set incorrect button
    $("#incorrect_button").click(function(event) { DeckViewerUI.incorrect(); });

    // Set incorrect button
    $("#show_answer_checkbox").click(function(event) { DeckViewerUI.showAnswerToggle(); });


  });  // end $(document).ready(function()
