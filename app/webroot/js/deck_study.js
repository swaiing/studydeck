/**
 * deck_study.js
 *
 */


// Constants
var VERACITY_MAP = new Array("incorrect","correct");
var MODE_STUDY = "study";
var MODE_QUIZ = "quiz";

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
        //$("#incorrect_button").css("background","red");
        //$("#correct_button").css("background","green");

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
