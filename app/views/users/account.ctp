<!-- /app/views/users/account.ctp -->
<?php
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('jquery.corner',false);
    echo $javascript->link('user_login_register',false);
    echo $html->css('user_login_register',null,null,false);
?>

<div id="middle_wrapper_content">
<div id="middle_bar">

<?php
    // Field attributes
    $FIELD_SIZE = 25;
    echo "<fieldset class=\"small\">\n";
    echo "<h1>Account</h1>";
    echo "<div>Username: ".$user['User']['username']."</div>";
    /*
	if($validationError != '') {
    	  echo "<br/><div>Error: ".$validationError."</div>\n";
	  	
    }
	
	if($success == true) {
    	echo "<div>Your password has been changed!</div>";
    }
    */
    echo "<div id=\"change_email_box\">";
        echo "<h2>Change Email:</h2>";
        echo "<div>Current Email: ".$user['User']['email']."</div>";
        echo $form->create('User', array('action' => 'account'));
        echo $form->input('User.email',array('label'=>'New Email:','size'=>$FIELD_SIZE));
        echo $form->input('User.email_confirmation',array('label'=>'Confrim Email:','size'=>$FIELD_SIZE));
        
    echo "</div>";

    echo "<div id=\"change_password_box\">";
        echo "<h2>Change Password:</h2>";
        echo $form->input('User.password',array('label'=>'New Password:','size'=>$FIELD_SIZE));
        echo $form->input('User.password_confirmation',array('type'=>'password','label'=>'Confrim Password:','size'=>$FIELD_SIZE));      
    echo "</div>"; 
    
    echo "<br/>";
    echo "<div>";       
        echo $form->input('User.auth_password',array('type'=>'password','label'=>'Current Password:','size'=>$FIELD_SIZE));      
    echo "</div>";     
        
    echo $form->end('Update Account');
   	
	   
    echo "</fieldset>\n";
?>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
