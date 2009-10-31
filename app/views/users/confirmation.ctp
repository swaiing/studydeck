<!-- /app/views/users/confirmation.ctp -->

<div id="middle_wrapper_content">
<div id="middle_bar">

<?php
    // Field attributes
    $FIELD_SIZE = 25;
    echo "<fieldset class=\"small\">\n";
    if($confirmationError != ''){
    	  echo "Confirmation Error: ".$confirmationError."\n";
	  	
    }
    else if($justRegistered == true){
    	  echo "Thanks for registering with StudyDeck. ";
	  echo "You will receive an email with a link. ";
	  echo "Please click the link to confirm your registration";
	  	
    }
    else{
	  echo $foundUser['TempUser']['username'].", you account has been confirmed!\n";
	  echo 'Please login '.$html->link("here","/users/login/");	
	
    }
     echo "</fieldset>\n";
?>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
