<!-- /app/views/products/view.ctp -->

<?php
    // Javascript/CSS includes
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('product_view',false);
    echo $html->css('product_view',null,null,false);
?>

<div id="middle_wrapper_content">
<div id="middle_bar" class="box">
<div class="box_content">

<div id="products_wrapper">
<h1>Select a Studydeck</h1>

<?php

    // If user logged in then do not display inactive products, send form directly to Paypal
    if (isset($productsOwned)) {

        // Create form
        echo $form->create('User', array('action' => 'registerSubmitPaypal', 'name' => 'ProductOrderForm'));
    }
    // If user not logged in then display all products, send form to register page
    else {

        // Create form
        echo $form->create('User', array('action' => 'register', 'name' => 'ProductOrderForm'));
    }
?>
<table id="products_table" border="1">
<tbody>
<?php
    foreach ($allProducts as $product) {
        $price = $product['Product']['price'];
        $id = $product['Product']['id'];
        $name = $product['Product']['name'];
        $deckId = $product['Product']['deck_id'];
?>
    <tr id="product_id_<?php echo $id; ?>">
        <td class="selected">
<?php
        if (isset($productsOwned) && $productsOwned[$deckId]) {
            echo "purchased";
        }
        else {
            echo $form->input($id, array('class'=>'checkbox', 'type'=>'checkbox', 
                                        'multiple'=>'true', 'label'=>false));
        }
?>
        </td>
        <td class="name"><?php echo $name; ?></td>
        <td class="price">$<?php echo $price; ?></td>
    </tr>
<?php
    }
?>
    <tr style="background-color:#cecece">
        <td></td>
        <td>Create your own deck</td>
        <td>Free!</td>
    </tr>
    <tr>
        <td></td>
        <td>Total:</td>
        <td>
            <span id="total">$0</span>
            <?php echo $form->input('total', array('id'=>'total','type'=>'hidden','label'=>false)); ?>
        </td>
    </tr>
</tbody>
</table>

<?php //echo $form->end('Buy Studydecks'); ?>
<div id="submit_button">Buy Studydecks</div>
</div>

</div> <!-- end box_content -->
</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
