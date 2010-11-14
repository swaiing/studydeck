<!-- /app/views/users/account.ctp -->
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
    $FIELD_SIZE = 25;
    echo "<h1>Account Settings</h1>";
    //echo "<p>Username: ".$user['User']['username']."</p>";
    /*
	if($validationError != '') {
    	  echo "<br/><div>Error: ".$validationError."</div>\n";
    }
	if($success) {
    	echo "<div>Your password has been changed!</div>";
    }
    */
    echo $form->create('User', array('action' => 'account'));
    echo "<div id=\"change_email_box\" class=\"form_box\">";
    echo "<h2>Change Your Email</h2>";
    echo "<ol>";
    echo "<li>Current Email: ".$user['User']['email']."</li>";
    echo "<li>" . $form->input('User.email',array('label'=>'New Email:','size'=>$FIELD_SIZE)) . "</li>";
    echo "<li>" . $form->input('User.email_confirmation',array('label'=>'Confirm:','size'=>$FIELD_SIZE)) . "</li>";
    echo "</ol>";
    echo "</div>";

    echo "<div id=\"change_password_box\" class=\"form_box\">";
    echo "<h2>Change Your Password</h2>";
    echo "<ol>";
    echo "<li>" . $form->input('User.password',array('label'=>'New Password:','size'=>$FIELD_SIZE)) . "</li>";
    echo "<li>" . $form->input('User.password_confirmation',array('type'=>'password','label'=>'Confirm:','size'=>$FIELD_SIZE)) . "</li>";
    echo "</ol>";
    echo "</div>"; 

    echo "<div id=\"update_account_box\">";
    echo "<h2>Confirm and Update</h2>";
    echo "<p>Enter your current password to update changes made above.</p>";
    echo $form->input('User.auth_password',array('type'=>'password','label'=>'Current Password:','size'=>$FIELD_SIZE));      
    echo $form->end('Update Settings');
    echo "</div>";     
?>

</div> <!-- end box_content -->
</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
