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
    var priceSet = price.toFixed(2);
    $("span#total").text("$" + priceSet);
    $("input#total").val(priceSet);

}

// Event handler to check checkbox when row is clicked
function setRowChecked(event) {

    if(!$(event.target).is("input.checkbox")) {
        var trElt = $(event.target).parents("tr.product_row");
        var inputBox = $("input.checkbox", trElt);
        if (inputBox.is(':checked')) {
            inputBox.attr('checked', false);
        }
        else {
            inputBox.attr('checked', true);
        }
    }
    updateTotal();
}

function submitForm() {

    // Validate selection, iterate checkboxes
    var productSelected = false;
    $("table#products_table tr").each(function() {
        // Check selected
        if ($("td.selected input", this).is(':checked')) {
            productSelected = true;
        }   
    }); 


    // Check a product has been checked
    if (productSelected) {
        document.ProductOrderForm.submit();
    }
    else {
        alert('You must select a Studydeck to purchase in order to continue');
    }
}

// Set event handlers
$(document).ready( function() {

    $("div#submit_button").click(function() { submitForm(); } );
    $("tr.product_row").each(function () {
        $(this).click(function(event) { setRowChecked(event); });
    });

});
