<!-- /app/views/users/change_password.ctp -->

<div id="middle_wrapper_content">
<div id="middle_bar">

<?php
    // Field attributes
    $FIELD_SIZE = 25;
    echo "<fieldset class=\"small\">\n";
    echo "<h1>Change Your Password:</h1>";
    
    
	if($validationError != '') {
    	  echo "<br/><div>Error: ".$validationError."</div>\n";
	  	
    }
	
	if($success == true) {
    	echo "<div>Your password has been changed!</div>";
    }
    else {

		echo $form->create('User', array('action' => 'changePassword'));
    	echo "<ol>\n";
    	echo "<li>" . $form->input('User.password',array('label'=>'Current Password:','size'=>$FIELD_SIZE)) . "</li>\n";
		echo "<li>" . $form->input('User.new_password',array('type'=>'password','label'=>'New Password:','size'=>$FIELD_SIZE)) . "</li>\n";
		echo "<li>" . $form->input('User.new_password_confirmation',array('type'=>'password','label'=>'Confrim New Password:','size'=>$FIELD_SIZE)) . "</li>\n";
	   	echo $form->end('Change Password');
   	}
	   
    echo "</fieldset>\n";
?>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
