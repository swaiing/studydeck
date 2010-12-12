/**
 * product_view.js
 *
 */
 
function updateTotal() {
    var price = 0;
    // Iterate checkboxes
    $("table#products_table tr").each(function() {

        // Check selected
        if ($("td.selected input", this).is(':checked')) {

            // Get price
            price += parseFloat($("td.price", this).text().substr(1));
        }   

    }); 
    $("input#total").val(price);

}   

// Set event handlers
$(document).ready( function() {

    $("input.checkbox").click(function() { updateTotal(); } );

});
