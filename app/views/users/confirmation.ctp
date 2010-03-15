<!-- /app/views/users/confirmation.ctp -->

<?php
    //echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    //echo $javascript->link('jquery.corner',false);
    //echo $javascript->link('user_login_register',false);
    echo $html->css('user_login_register',null,null,false);
?>

<div id="middle_wrapper_content">
<div id="middle_bar" class="box">
<div class="box_content">

<div id="confirm_box">
<?php

    // Display error
    if(!empty($confirmationError)) {
        echo "<h1>Error!</h1>";
    	echo "<p>Confirmation Error: " . $confirmationError . "</p>";
    }
    else { 

        // Successful registration
        if($justRegistered) {
            echo "<h1>Thanks!</h1>";
            echo "<p>Thanks for registering with StudyDeck!</p>";
            echo "<p>You will receive an email containing a link to confirm your registration.</p>";
        }
        else {
            echo "<h1>Registration Complete!</h1>";
            echo "<p>" . $foundUser['TempUser']['username'] . ", your account has been confirmed!</p>";
            echo "<p>Please " . $html->link('login', array('controller'=>'users', 'action'=>'login')) . " to continue.</p>";
        }
    }
?>
</div>

</div> <!-- end box_content -->
</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
