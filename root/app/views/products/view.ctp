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

<div id="progress_header">
    <span class="active">Choose Studydecks</span> >
    <span class="inactive">Confirm and Register</span> >
    <span class="inactive">Pay with PayPal</span>
</div>

<h1>Choose a Studydeck</h1>
<div id="intro">
You must purchase a Studydeck in order to use our app.  Please choose one or more Studydecks from the list below.
</div>

<?php //print_r($descriptions); ?>

<?php
    // Create form
    echo $form->create('User', array('action' => 'register', 'name' => 'ProductOrderForm'));

    // Add hidden parameter to indicate form should post directly to Paypal process
    // rather than continue to order confirmation page
    if (isset($userLoggedIn)) {
        echo $form->input('postPaypal', array('id'=>'postPaypal','type'=>'hidden','label'=>false));
    }
?>
<table id="products_table">
<colgroup>
    <col class="selected" />
    <col class="name" />
    <col class="price" />
</colgroup>
<tbody>
<?php
    foreach ($allProducts as $product) {
        $price = $product['Product']['price'];
        $id = $product['Product']['id'];
        $name = $product['Product']['name'];
        $deckId = $product['Product']['deck_id'];
        $deckDesc = $descriptions[$deckId]['Deck']['description'];
        $deckName = $descriptions[$deckId]['Deck']['deck_name'];
?>
    <tr class="product_row" id="product_id_<?php echo $id; ?>">
        <td class="selected">
<?php
        if (isset($productsOwned) && array_key_exists($deckId, $productsOwned) && $productsOwned[$deckId]) {
            echo "purchased";
        }
        else {
            echo $form->input($id, array('class'=>'checkbox', 'type'=>'checkbox', 
                                        'multiple'=>'true', 'label'=>false));
        }
?>
        </td>
        <td class="name">
            <span class="title"><?php echo $name; ?></span>
            <p><?php echo $deckDesc; ?></p>
        </td>
        <td class="price">$<?php echo $price; ?></td>
    </tr>
<?php
    }
?>
    <tr class="product_row">
        <td class="selected"></td>
        <td class="name">
            <span class="title">Custom Studydecks</span>
            <p>Create as many custom Studydecks as you want on any topics of your choosing.</p>
        </td>
        <td class="price">Free!</td>
    </tr>
    <tr>
        <td class="selected"></td>
        <td class="name"></td>
        <td class="price">
            <span id="total_label">total = </span>
            <span id="total">$0.00</span>
            <?php echo $form->input('sentFromView', array('id'=>'sentFromView','type'=>'hidden','label'=>false)); ?>
        </td>
    </tr>
</tbody>
</table>

<div id="submit_button">Purchase and Continue</div>
</div>

</div> <!-- end box_content -->
</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
