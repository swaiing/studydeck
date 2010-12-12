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

    $("span#total").text("$" + price);
    $("input#total").val(price);

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

    $("input.checkbox").click(function() { updateTotal(); } );
    $("div#submit_button").click(function() { submitForm(); } );

});
