/**
 * deck_info.js
 *
 */

// Object literal which encapsulates Rating Selectors
RatingSelectorUI = {

    deck:null,
    table:null,

    'init':function() {
        // Pass global deck JSON data
        this.deck = new Deck(deckData, cardData, cardResultsData);

        // Set variable for scope within closure
        var obj = this;

        // Setup RTS elements
        this.table = $("div#cards_tab table.deck_table tr.card_row");
        this.table.each(function() {

            // Get ID of card from class attribute
            var cArr = ($(this).attr("class")).split(" ");
            var idArr = cArr[cArr.length-1].split("_");
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
        });
    }
}

$(document).ready( function() {
    // Round corners
    $("div#middle").corner();
    $("div#bottom").corner();

    // Setup tabs
    $("#bottom").tabs();

    // Auto-select the review tab if present
    $("#bottom").tabs('select',2);

    // Disable checkboxes for ratings with no cards
    disableCheckboxes();

    // Set on-click handlers for quiz/learn mode
    $("input#quiz_button").click(function(event) { setQuizMode(); });
    $("input#learn_button").click(function(event) { setLearnMode(); });

    // Set on-click handler for select 'All' checkbox
    $("input#select_all_checkbox").click(function(event) { selectAllCheckboxes(); });

    // Toggle edit rating mode
    RatingSelectorUI.init();
    $("div#cards_tab span.edit_rating").click(function(event) { toggleRatingEdit(); });
});

// Disables all checkboxes with '(0)' aka zero cards
function disableCheckboxes() {

    // Disable checkboxes that have zero cards
    $("div#category_select div.checkbox").each(
        function() {
            var strVal = $(this).find("label").text();

            // Run regex for '(0)'
            var regex = /^[^(]*\(0\)$/;
            var searchResult = strVal.search(regex);
            var hasNoCards = (searchResult != -1);

            if(hasNoCards) {
                $(this).find("input").attr('disabled', true);
                $(this).find("label").attr('class', 'disabled');
            }
        }
    );
}

// On-click handler for selecting all checkboxes
function selectAllCheckboxes() {
    var isChecked = $("input#select_all_checkbox").is(':checked');
    $("div#category_select input[type='checkbox']:not(:disabled)").attr('checked', isChecked);

}

// Onclick handler for the 'Quiz' button
// Sets hidden field value to '1'
function setQuizMode() {
    var trueVal = 1;
    $("input#DeckIsQuizMode").val(trueVal);
}
// Onclick handler for the 'Learn' button
// Sets hidden field value to '0'
function setLearnMode() {
    var falseVal = 0;
    $("input#DeckIsQuizMode").val(falseVal);
}

// Onclick handler when Edit rating is clicked
function toggleRatingEdit() {

    var editTxt = '[Edit Ratings]';
    var updateTxt = '[Done Editing]';
    var modeEdit = true;

    // Change 'Edit' text
    var ctrlElt = $("div#cards_tab span.edit_rating");
    var text = ctrlElt.text();
    if(text == editTxt) {
        ctrlElt.text(updateTxt);
    }
    else {
        ctrlElt.text(editTxt);
        modeEdit = false;

        // Trigger save
        RatingSelectorUI.deck.writeSession();
    }

    // Display/hide rating selectors
    RatingSelectorUI.table.each(function() {
        var elt = $("td.rts_col", this);

        if(modeEdit) {
            $("ul.rts",elt).css({display:""});
            $("span.rating_text",elt).css({display:"none"});
        }
        else {
            $("ul.rts",elt).css({display:"none"});
            $("span.rating_text",elt).css({display:""});
        }

    });
}
