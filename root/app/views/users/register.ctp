<!-- /app/views/users/register.ctp -->

<?php
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('jquery.corner',false);
    echo $javascript->link('user_login_register',false);
    echo $html->css('user_login_register',null,null,false);
?>

<div id="middle_wrapper_content">
	<div id="middle_bar" class="box">
    <div class="box_content">

    <div id="progress_header">
        <span class="inactive">[Choose Studydecks]</span>
        <hr class="divider"/>
        <span class="active">[Confirm and Register]</span>
        <hr class="divider"/>
        <span class="inactive">[Pay with PayPal]</span>
    </div>

    <?php
    	echo $form->create('User', array('action' => 'register'));
    ?>
        
    <div id="order_confirm_box">

    <h2>Order Confirmation</h2>
    <table id="products_table" border="1">
    <thead>
        <tr>
            <td>Product</td>
            <td>Price</td>
        </tr>
    </thead>
    <tbody>
    <?php
        $total = 0;
        foreach ($productsOrdered as $product) {
            $price = $product['Product']['price'];
            $id = $product['Product']['id'];
            $name = $product['Product']['name'];
            $total += $price;
            
            // Output hidden form field
            echo $form->input($id, array('value' => true, 'type' => 'hidden'));
    ?>
        <tr id="product_id_<?php echo $id; ?>">
            <td class="name"><?php echo $name; ?></td>
            <td class="price">$<?php echo $price; ?></td>
        </tr>
    <?php
        }
    ?>
        <tr>
            <td class="total_label">Total</td>
            <td class="total_val">$<?php echo $total; ?>
        </tr>
    </tbody>
    </table>

    <?php
        // Modify order link
    	echo "<div id=\"modify_order\">" . $html->link('Modify Your Order', array('controller'=>'products', 'action'=>'view')) . "</div>\n";
    ?>
    </div>

    <div id="register_box" class="form_box">
	<?php
    	// Field attributes
    	$FIELD_SIZE = 30;
        $FIELD_LENGTH = 50;

    	echo "<h2>User Registration</h2>\n";

    	echo "<ol>\n";
    	echo "<li>" . $form->input('User.email',array('label'=>'Email:', 'size'=>$FIELD_SIZE, 'maxLength'=>$FIELD_LENGTH)) . "</li>\n";
    	echo "<li>" . $form->input('User.password',array('label'=>'Password:', 'size'=>$FIELD_SIZE, 'maxLength'=>$FIELD_LENGTH)) . "</li>\n";
    	echo "<li>" . $form->input('User.password_confirm', array('type' => 'password', 'label'=>'Repeat:', 'size'=>$FIELD_SIZE, 'maxLength'=>$FIELD_LENGTH)) . "</li>\n";

        echo "</ol>\n";

		$recaptcha->display_form('echo');
        if($recaptchaFailed) {
            echo "<div>Captcha input has failed!";
            echo "</div>\n"; 
        }
    	echo $form->end('Pay with PayPal');
    	echo "</div>\n"; 
	?>
    </div>

	</div> <!-- end box_content -->
	</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
