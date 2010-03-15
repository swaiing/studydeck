<!-- /app/views/users/forgot_password.ctp -->

<?php
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('jquery.corner',false);
    echo $javascript->link('user_login_register',false);
    echo $html->css('user_login_register',null,null,false);
?>

<div id="middle_wrapper_content">
	<div id="middle_bar" class="box">
    <div class="box_content">

	<?php
    	// Field attributes
    	$FIELD_SIZE = 30;
    	$FIELD_LENGTH = 50;

    	echo "<div id=\"forgot_box\" class=\"form_box\">\n";
    	echo "<h2>Forgot your password?</h2>";
    
    	$session->flash();

    	if($success != true) {
    		echo "<p>Enter your email address and we will set it to a temporary password which we will email you.</p>";
    	}

     	if($validationError != '') {
    	  	echo "<p><span class=\"error\">".$validationError."</span></p>";
	  	}

    	if($success) {
    		echo "<p>You should receive an email shortly containing a temporary password.</p>";
    	}
    	else {
			echo $form->create('User', array('action' => 'forgotPassword'));
    		echo "<ol>\n";
    		echo "<li>" . $form->input('User.email',array('label'=>'Email:', 'size'=>$FIELD_SIZE, 'maxLength'=>$FIELD_LENGTH)) . "</li>\n";
            echo "</ol>\n";
     		echo $form->end('Email me');
     	}
    
     	echo "</div>\n";
	?>

    <div class="clear_div">&nbsp;</div>
	</div> <!-- end box_content -->
	</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
