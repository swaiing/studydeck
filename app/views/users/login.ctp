<!-- /app/views/users/login.ctp -->

<?php
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('jquery.corner',false);
    echo $javascript->link('user_login_register',false);
    echo $html->css('user_login_register',null,null,false);
?>

<div id="middle_wrapper_content">
	<div id="middle_bar" class="box">
    <div class="box_content"

    <div id="register_ad_box">
        <h2>Not a member?</h2>
        <p><?php echo $html->link('Sign up, for free!', array('controller'=>'users', 'action'=>'register'));?></p>
        <p>As as registered user, you can do a lot!</p>
        <ul>
            <li>Create your own decks</li>
            <li>Save public decks to your customized dashboard</li>
            <li>Categorize flashcards into easy, medium and hard so you only study the cards you need to</li>
            <li>Track your progress with quiz results and deck progress summaries</li>
        </ul>
    </div>

	<?php

    	// Field attributes
    	$FIELD_SIZE = 20;
        $FIELD_LENGTH = 50;
    	$modedUrl = str_replace('/','_',$prevUrl);
   
    	if(strpos($modedUrl,'users_confirmation')) {
			$modedUrl ='';
    	} 
   		// echo "Moded URL: ".$modedUrl;   

        // Start DIV
    	echo "<div id=\"login_box\" class=\"form_box\">\n";
    	echo "<h2>Login to your account</h2>\n";

        // Begin form
    	echo $form->create('User',array('action'=> 'customLogin/'.$modedUrl));

        // Error reporting
        $session->flash();
        $session->flash('auth');

    	echo "<ol>\n";
    	echo "<li>" . $form->input('username',array('label'=>'Username or Email:', 'size'=>$FIELD_SIZE, 'maxLength'=>$FIELD_LENGTH)) . "</li>\n";
    	echo "<li>" . $form->input('password',array('label'=>'Password:', 'size'=>$FIELD_SIZE, 'maxLength'=>$FIELD_LENGTH)) . "</li>\n";
    	echo "<li class='forgot_password'>" . $html->link('Forgot your password?', array('controller'=>'users','action'=>'forgotPassword')) . "</li>\n";

        echo "</ol>\n";
    	echo $form->end('Login');
    	echo "</div>\n";
    ?>


    <div class="clear_div">&nbsp;</div>
	</div> <!-- end box_content -->
	</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->

