<!-- /app/views/users/login.ctp -->

<div id="middle_wrapper_content">
	<div id="middle_bar">


	<?php
    	// Field attributes
    	$FIELD_SIZE = 25;
    	$session->flash();
    	$session->flash('auth');
    	$modedUrl = str_replace('/','_',$prevUrl);
   
    	if(strpos($modedUrl,'users_confirmation')) {
			$modedUrl ='';
    	} 
   		// echo "Moded URL: ".$modedUrl;   
    	echo $form->create('User',array('action'=> 'customLogin/'.$modedUrl));
    	echo "<fieldset class=\"small\">\n";
    	echo "<h1>Login</h1>\n";
    	echo "<ol>\n";
    	echo "<li>" . $form->input('username',array('label'=>'Username:','size'=>$FIELD_SIZE)) . "</li>\n";
    	echo "<li>" . $form->input('password',array('label'=>'Password:','size'=>$FIELD_SIZE)) . "</li>\n";
    	echo  $html->link("forgot your password?","/users/forgotPassword");
    	echo $form->end('Login');
    	echo "</fieldset>\n";
	?>

	</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
