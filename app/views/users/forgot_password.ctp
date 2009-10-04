<!-- /app/views/users/forgot_password.ctp -->

<div id="middle_wrapper_content">
<div id="middle_bar">

<?php
    // Field attributes
    $FIELD_SIZE = 25;
    echo "<fieldset class=\"small\">\n";
    echo "<h1>Forgot your password?</h1>";
    
    if($success != true){
    	echo "<div>Enter you email and a temporary password will be emailed to that address.</div>";
    }
     if($validationError != ''){
    	  echo "<br/><div>Error: ".$validationError."</div>\n";
	  	
    }
    if($success == true){
    	echo "<br/><div>Your new password has been emailed to your account</div>";	
    }
    else{
	echo $form->create('User', array('action' => 'forgotPassword'));
    	echo "<ol>\n";
    	echo "<li>" . $form->input('User.email',array('label'=>'Email:','size'=>$FIELD_SIZE)) . "</li>\n";
     	echo $form->end('Email New Password');
     }
	  	
	
    
     echo "</fieldset>\n";
?>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
