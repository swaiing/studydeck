/**
 * deck_info.js
 *
 */

// Object literal which encapsulates: rating selectors
DeckInfoUI = {

    deck:null,
    table:null,
    resultsTable:null,
    RATING_MAP:new Array("unrated","easy","medium","hard"),
    CARD_ID_INDEX:1,

    'init':function() {
        // Pass global deck JSON data
        this.deck = new Deck(deckData, cardData, cardResultsData);

        // Setup RTS
        this.setupRts();
    },

    'setupRts':function() {

        // Setup RTS elements on card list tab
        this.table = $("div#cards_tab table.deck_table tr.card_row");
        this.bindRts(this.table);

        // Setup RTS elements on quiz results tab
        this.resultsTable = $("div#results_tab table.deck_table tr.card_row");
        this.bindRts(this.resultsTable);

    },

    // Function which binds an RTS element to rows in a table
    'bindRts':function(table) {

        if(!table) {
            return;
        }

        // Set variable for scope within closure
        var obj = this;

        // For every row in the table add an RTS
        table.each(function() {

            // Get ID of card from class attribute
            var cArr = ($(this).attr("class")).split(" ");
            var idArr = cArr[obj.CARD_ID_INDEX].split("_");
            var id = idArr[idArr.length-1];

            // Insert widget into DOM
            var elt = $("td.rts_col", this);
            var rts = new RatingSelector(elt);

            // Hide rts
            var rtsElt = $("ul.rts", this);
            rtsElt.css({display:"none"});
            
            // Bind card to RTS widget
            var c = obj.deck.getCard(id);
            rts.setCard(c);

            // Bind DOM rating field to widget
            var ratingElt = $("span.rating", this);
            rts.setRatingElt(ratingElt);
        });
    },

    // Adds card counts to checkboxes
    // Disables all checkboxes with '(0)' aka zero cards
    'refreshCheckboxCounts':function() {

        // Rating counts
        var ratingCounts = this.deck.getRatingCounts();

        // Set the total number of cards
        var totalCards = this.deck.getNumCards();
        var allCardsStr = "All (" + totalCards + ")";
        $("div#category_select label[for=select_all_checkbox]").text(allCardsStr);

        // Closure object
        var obj = this;

        // Disable checkboxes that have zero cards
        $("div#category_select div.checkbox").each(function() {

            // Get rating count for this label
            var label = $("label", this);
            var origLabelStr = label.text().split(" ")[0];
            var ratingStr = origLabelStr.toLowerCase();
            var rating = jQuery.inArray(ratingStr, obj.RATING_MAP);
            var ratingCount = ratingCounts[rating];

            var labelStr;
            if(ratingCount > 0) {
                labelStr = origLabelStr + " (" + ratingCount + ")";
                label.text(labelStr);
                $("input", this).removeAttr('disabled');
                $("label", this).removeAttr('class');
                $("input", this).attr('checked','checked');     // auto-select
                // Uncheck previous if there is medium or hard
                if(ratingStr == 'medium') {
                    $("input", $(this).prev()).attr('checked','');
                }
                if(ratingStr == 'hard') {
                    $("input", $(this).prev()).attr('checked','');
                    $("input", $(this).prev().prev()).attr('checked','');
                }
            }
            else {
                labelStr = origLabelStr + " (0)";
                label.text(labelStr);
                $("input", this).attr('disabled', true);
                $("label", this).attr('class', 'disabled');
            }
        });
    },

    // On-click handler for selecting all checkboxes
    'selectAllCheckboxes':function() {
        var isChecked = $("input#select_all_checkbox").is(':checked');
        $("div#category_select input[type='checkbox']:not(:disabled)").attr('checked', isChecked);
    },

    // Onclick handler for the 'Quiz' button
    // Sets hidden field value to '1'
    'setQuizMode':function() {
        var trueVal = 1;
        $("div#mode_select input#DeckIsQuizMode").val(trueVal);
    },

    // Onclick handler for the 'Learn' button
    // Sets hidden field value to '0'
    'setLearnMode':function() {
        var falseVal = 0;
        $("div#mode_select input#DeckIsQuizMode").val(falseVal);
    },

    // Onclick handler when Edit rating is clicked
    'toggleRatingEdit':function(obj, isResultsTab) {

        var editTxt = 'Edit Difficulties';
        var updateTxt = 'Save Difficulties';
        var modeEdit = true;

        // Change 'Edit' text
        var ctrlElt = $(obj);
        var text = ctrlElt.text();
        if(text == editTxt) {
            ctrlElt.text(updateTxt);
        }
        else {
            ctrlElt.text(editTxt);
            modeEdit = false;

            // Trigger save
            this.deck.writeSession();

            // Add card counts for each rating;
            // Disable checkboxes for ratings with no cards
            DeckInfoUI.refreshCheckboxCounts();
        }

        var table = this.table;
        if(isResultsTab) {
            table = this.resultsTable;
        }

        // Display/hide rating selectors
        table.each(function() {
            var elt = $("td.rts_col", this);

            if(modeEdit) {
                $("ul.rts",elt).css({display:""});
                $("span.rating",elt).css({display:"none"});
            }
            else {
                $("ul.rts",elt).css({display:"none"});
                $("span.rating",elt).css({display:""});
            }

        });
    }

} // End DeckInfoUI object


$(document).ready( function() {

    // Toggle edit rating mode
    DeckInfoUI.init();

    // Add card counts for each rating;
    // Disable checkboxes for ratings with no cards
    DeckInfoUI.refreshCheckboxCounts();

    // Round corners
    //$("div#middle").corner();
    //$("div#bottom").corner();

    // Setup tabs
    $("#bottom").tabs();

    // Auto-select the review tab if present
    $("#bottom").tabs('select',2);

    // Set on-click handlers for quiz/learn mode
    $("input#quiz_button").click(function() { DeckInfoUI.setQuizMode(); });
    $("input#learn_button").click(function() { DeckInfoUI.setLearnMode(); });

    // Set on-click handler for select 'All' checkbox
    $("input#select_all_checkbox").click(function() { DeckInfoUI.selectAllCheckboxes(); });

    // Set onclick for rating selector
    $("div#cards_tab th.edit_rating button").click(function() { DeckInfoUI.toggleRatingEdit(this); });
    $("div#results_tab th.edit_rating button").click(function() { DeckInfoUI.toggleRatingEdit(this, true); });
});
