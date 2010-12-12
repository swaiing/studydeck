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

    <div id="register_box" class="form_box">
    <?php
    	echo $form->create('User', array('action' => 'register'));
    ?>
        
    <h2>Order Confirmation</h2>
    <table id="products_table" border="1" cellspacing="10">
    <thead>
        <tr>
            <td>Product</td>
            <td>Price</td>
        </tr>
    </thead>
    <tbody>
    <?php
	$count = 1;
        foreach ($productsOrdered as $product) {
            $price = $product['Product']['price'];
            $id = $product['Product']['id'];
            $name = $product['Product']['name'];
            
            // Output hidden form field
            echo $form->input($count,array('value'=>$id,'type'=>'hidden'));
			$count++;
    ?>
        <tr id="product_id_<?php echo $id; ?>">
            <td class="name"><?php echo $name; ?></td>
            <td class="price">$<?php echo $price; ?></td>
        </tr>
    <?php
        }
    ?>
        <tr>
            <td>Total</td>
            <td><?php echo $orderTotal; ?>
        </tr>
    </tbody>
    </table>

	<?php
    	// Field attributes
    	$FIELD_SIZE = 30;
        $FIELD_LENGTH = 50;

    	echo "<h2>User Registration</h2>\n";

    	//echo "<p>" . $html->link('Already have an account?', array('controller'=>'users', 'action'=>'login')) . "</p>\n";

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
    	echo $form->end('Create my account');
    	echo "</div>\n"; 
	?>

<!--
    <div id="register_priv_box">
        <h2>Good to knows</h2>
        <ul>
            <li>We will not sell your personal info to third-parties.</li>
            <li>Read our <a href="scott_write_this_plz">privacy policy.</a></li>
            <li>We will not spam you.</li>
        <ul>
    </div>
-->

	</div> <!-- end box_content -->
	</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
