// deck_info.js

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

