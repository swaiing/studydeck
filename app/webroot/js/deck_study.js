/**
 * deck_study.js
 *
 */


// Constants
var VERACITY_MAP = new Array("Incorrect","Correct");
var MODE_LEARN = "study";
var MODE_QUIZ = "quiz";
var FIN_TEXT = "End of StudyDeck";

// Bind rollover effects to buttons
function ThreeStateButton(elt, fnStr) {

    this.elt = $(elt);
    this.classAttr = this.elt.attr('class');

    // Set variable for scope within closure
    var obj = this;
    this.clickFnStr = fnStr;

    this.elt.mouseover(function() {

        // Check if 'clicked'
        var divClassClicked = "div." + obj.classAttr + "-click";
        if(!$(this).prev().is(divClassClicked)) {
            var overlay = "<div class='" + obj.classAttr + "-hover'></div>";
            $(this).before(overlay)
                          .prev().css({display:"none"})
                          .fadeIn(100);
        }
                      
    }).mouseout(function() {

        // Check if hovered on
        var divClassHovered = "div." + obj.classAttr + "-hover";
        if($(this).prev().is(divClassHovered)) {
            $(this).prev().fadeOut(100, function() {
                $(this).remove();
            });
        }

    }).click(function() {

        // Call selectQuizButton function
        obj.selectButton();
    });
    return true;
}
// Events for when the button is clicked
ThreeStateButton.prototype.selectButton = function() {

    // Remove hover div
    this.reset();

    // Set overlay
    var overlay = "<div class='" + this.classAttr + "-click'></div>";
    $(this.elt).before(overlay)
                  .prev().css({display:"none"})
                  .fadeIn(100);      

    // Callback
    if(this.clickFnStr) {
        setTimeout(this.clickFnStr, 500);
    }

    return true;
}
// Remove overlay divs
ThreeStateButton.prototype.reset = function() {
    $(this.elt).siblings("div").remove();
}

  /**
  *
  * DeckViewerUI Object
  * 
  * Object literal that wraps a Deck Object and binds the UI to it.
  *
  */
  DeckViewerUI = {

    // Constants
    QUIZ_BTNS_CLASS:"crs",
    INC_BTN_CLASS:"inc_btn",
    COR_BTN_CLASS:"cor_btn",

    // Instance variables
    deck:null,
    isShowingAnswer:0,
    mode:MODE_LEARN,

    // Button elements
    rts:null,
    corBtn:null,
    incBtn:null,

    // Bootstrap intialize function
    'init':function() {

        // Pass global deck JSON data
        this.deck = new Deck(deckData, cardData, cardResultsData);

        // Get first card
        var firstCard = this.deck.getNextCard();

        // Set the title
        $("h1.title").html(this.deck.deckName);

        // Enter text for final card div
        $("div#row_body_mask").html("<div id=\"end\">" + FIN_TEXT + "</div>");

        // Call build UI functions
        if(MODE == MODE_LEARN) {
          this.mode = MODE_LEARN;

          // Insert learn mode box
          this.renderLearnWindows();
          this.showAnswerToggle();

          // Initialize new RatingSelector object and insert in bottom row
          this.rts = new RatingSelector("div#row_bottom", 'DeckViewerUI.next()');
        }
        else if(MODE == MODE_QUIZ) {
          this.mode = MODE_QUIZ;

          // Insert elements for buttons
          this.renderQuizButtons();
          
          // Bind actions to buttons
          this.corBtn = new ThreeStateButton("ul." + this.QUIZ_BTNS_CLASS + " li." + this.COR_BTN_CLASS,
                                                'DeckViewerUI.correct()');
          this.incBtn = new ThreeStateButton("ul." + this.QUIZ_BTNS_CLASS + " li." + this.INC_BTN_CLASS,
                                                'DeckViewerUI.incorrect()');
        }

        // Load first card
        this.showCard(firstCard);
    },

    // Builds left-side windows for learn mode
    'renderLearnWindows':function() {
        var chkboxStr = "<input type=\"checkbox\" id=\"show_ans_chk\" ";
            chkboxStr += "name=\"show_answer\" value=\"show_answer\" ";
            chkboxStr += "checked=\"true\"/>";

        var optionsBox = $('<div class=\"ctrl_box\">')
                         .append("<h3>Options</h3>")
                         .append(chkboxStr)
                         .append("<label for=\"show_ans_chk\">Show Answer</label>");

        var statsBox = $('<div class=\"ctrl_box\">')
                         .append("<h3>Card Quiz History</h3>")
                         .append("<span id=\"ans_corr\">75% (3/4)</span>");

        var lastAnswer = $('<div class=\"ctrl_box\">')
                         .append("<h3>Last Answer</h3>")
                         .append("<span id=\"last_ans\">Correct</span>");

        var controlWrap = $('<div id=\"control_wrap\"></div>')
                         .prependTo('#top_controls')
                         .append(optionsBox)
                         .append(statsBox)
                         .append(lastAnswer);

        // Round corners
        controlWrap.corner();

    },

    // Builds correct/incorrect buttons for quiz mode
    'renderQuizButtons':function() {

        // Append Quiz buttons
        var buttonEltStr = "<ul class=\"" + this.QUIZ_BTNS_CLASS + "\">";
        buttonEltStr += "<li class=\"" + this.INC_BTN_CLASS + "\"></li>";
        buttonEltStr += "<li class=\"" + this.COR_BTN_CLASS + "\"></li>";
        buttonEltStr += "</ul>";
        $("#row_bottom").prepend(buttonEltStr);

        return true;
    },

    // Selects correct/incorrect button
    'selectQuizButton':function(classAttr, buttonElt) {

        // Remove hover div
        $(buttonElt).siblings("div").remove();

        // Set overlay
        var overlay = "<div class='" + classAttr + "-click'></div>";
        $(buttonElt).before(overlay)
                      .prev().css({display:"none"})
                      .fadeIn(100);      

        // Fire correct/incorrect button on delay
        if(classAttr == this.COR_BTN_CLASS) {
            setTimeout('DeckViewerUI.correct()', 500);
        }
        else if(classAttr = this.INC_BTN_CLASS) {
            setTimeout('DeckViewerUI.incorrect()', 500);
        }
        return true;
    },

    // Helper functions
    'showCard':function(card) {
        if(!card) {
            //alert("Null card in DeckViewerUI.showCard");
            return;
        }

        // Hide answer field if showAnswer bit set false
        if(!this.isShowingAnswer) {
            this.hideAnswer();
        }

        // Set rating field
        if(this.rts) {
            // Pass to RatingSelector
            this.rts.setCard(card);
        }
        else {
            // TODO: Display rating somewhere in Quiz mode when there is no RTS?
            //var eltStr = "<div class=\"rating_str\">" + card.getRatingStr() + "</div>";
            //$("#card_rating").html(eltStr);
        }

        // Set question field
        //$("#card_question").text(card.question);
        $("#card_question").html(card.question);

        // Set answer field
        //$("#card_answer").text(card.answer);
        $("#card_answer").html(card.answer);

        // Update deck progress
        var progressStr = this.deck.getNumViewed() + "/" + this.deck.getNumCards();
        $("#deck_progress").text(progressStr);

        // Update card metrics
        var totalAns = parseInt(card.totalCorrect) + parseInt(card.totalIncorrect);
        var percentCorrect = (totalAns > 0) ? Math.round((card.totalCorrect/totalAns)*100) : null;
        var quizHistoryStr = "";
        if(percentCorrect != null) {
            quizHistoryStr += percentCorrect + "% ";
        }
        quizHistoryStr += "(" + card.totalCorrect + "/" + totalAns + ")";
        var lastAnswerStr = VERACITY_MAP[card.lastAnswer];
        $("span#ans_corr").text(quizHistoryStr);
        $("span#last_ans").text(lastAnswerStr);
    },

    // Hide answer div and rating/quiz buttons
    'hideAnswer':function() {
        $("span#card_answer").hide();
        $("#row_bottom ul").hide();
    },

    // Show answer div and rating/quiz buttons
    'showAnswer':function() {
       $("span#card_answer").fadeIn("fast");
       $("#row_bottom ul").fadeIn("fast");
    },

    'resetButtons':function() {
        // Revert styles of correct/incorrect buttons
        window.DeckViewerUI.corBtn && DeckViewerUI.corBtn.reset();
        window.DeckViewerUI.incBtn && DeckViewerUI.incBtn.reset();
    },

    // Event handling functions
    'next':function() {
        
        // Get the next card
        var success = this.deck.getNextCard();

        // Reset the display and show the card
        if(success) {
            this.resetButtons();
            this.showCard(this.deck.getCard());
        }
        // End of deck, show special div
        else {
            this.hideAnswer();
            $("div#row_body").hide();
            $("div#row_body_mask").show();
        }
    },

    'previous':function() {

        // Get the previous card
        this.deck.getPreviousCard();

        // If the 'end of deck' div is showing, reset
        var bodyHidden = ($("div#row_body").css('display') == 'none');
        if(bodyHidden) {
            $("div#row_body").show();
            $("div#row_body_mask").hide();
        }

        // Reset the display and show the card
        this.resetButtons();
        this.showCard(this.deck.getCard());
    },

    'correct':function() {

        // Set the card correct flag
        this.deck.getCard().setCorrect();

        // Change style of correct button
        this.resetButtons();

        // Advance to next card
        this.next();
    },

    'incorrect':function() {

        // Set the card incorrect flag
        this.deck.getCard().setIncorrect();
        
        // Change style of incorrect button
        this.resetButtons();

        // Advance to next card
        this.next();
    },

    // Hide/Show answer
    'showAnswerToggle':function() {
        if($("input#show_ans_chk").is(':checked')) {
            this.isShowingAnswer = 1;
            this.showAnswer();
        }
        else {
            this.isShowingAnswer = 0;
            this.hideAnswer();
        }
    }

  }; // end DeckViewerUI Object


  // jQuery document onload
  $(document).ready(function() {

    // Start
    DeckViewerUI.init();

    /**
     *  Bind event handlers
     *  Bind keyboard events
     */
    // Next/previous button
    $("#prev a").click(function(event) { DeckViewerUI.previous(); });
    $(document).bind('keydown', 'left', function(){ DeckViewerUI.previous(); return false; });
    $("#next a").click(function(event) { DeckViewerUI.next(); });
    $(document).bind('keydown', 'right', function(){ DeckViewerUI.next(); return false; });

    // Reveal answer click
    $("div#row_body").click(function(event) { DeckViewerUI.showAnswer(); });
    $(document).bind('keydown', 'space', function(){ DeckViewerUI.showAnswer(); return false; });

    // Key bindings for learn/quiz mode
    if(MODE == MODE_LEARN) {

        // Easy-medium-hard buttons
        $(document).bind('keydown', '1', function() {
            DeckViewerUI.rts.eltEasy.click();
            return false;
        });
        $(document).bind('keydown', '2', function() {
            DeckViewerUI.rts.eltMedium.click();
            return false;
        });
        $(document).bind('keydown', '3', function() {
            DeckViewerUI.rts.eltHard.click();
            return false;
        });
    }
    else if(MODE == MODE_QUIZ) {

        // Bind effects to up/down buttons
        $(document).bind('keydown', 'up', function() {
            DeckViewerUI.corBtn.elt.click();
            return false;
        });
        $(document).bind('keydown', 'down', function() {
            DeckViewerUI.incBtn.elt.click();
            return false;
        });

        // Set incorrect button
        $("input#show_ans_chk").click(function(event) { DeckViewerUI.showAnswerToggle(); });
    }

  });  // end $(document).ready(function()
