<!-- /app/views/users/register.ctp -->

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
    	$FIELD_SIZE = 35;
        $FIELD_LENGTH = 50;

    	echo "<div id=\"register_box\" class=\"form_box\">\n";
    	echo "<h2>Register with StudyDeck</h2>\n";

    	echo $form->create('User', array('action' => 'register'));
    	echo "<p>" . $html->link('Already have an account?', array('controller'=>'users', 'action'=>'login')) . "</p>\n";

    	echo "<ol>\n";
    	echo "<li>" . $form->input('TempUser.username',array('label'=>'Username:', 'size'=>$FIELD_SIZE, 'maxLength'=>$FIELD_LENGTH)) . "</li>\n";
    	echo "<li>" . $form->input('TempUser.email',array('label'=>'Email:', 'size'=>$FIELD_SIZE, 'maxLength'=>$FIELD_LENGTH)) . "</li>\n";
    	echo "<li>" . $form->input('TempUser.password',array('label'=>'Password:', 'size'=>$FIELD_SIZE, 'maxLength'=>$FIELD_LENGTH)) . "</li>\n";
    	echo "<li>" . $form->input('TempUser.password_confirm', array('type' => 'password', 'label'=>'Repeat:', 'size'=>$FIELD_SIZE, 'maxLength'=>$FIELD_LENGTH)) . "</li>\n";

        echo "</ol>\n";
        $recaptcha->display_form('echo');
        if($recaptchaFailed) {
            echo "<div>Captcha input has failed!";
            echo "</div>\n"; 
        }
    	echo $form->end('Create my account');
    	echo "</div>\n"; 
        
	?>

    <div id="register_priv_box">
        <h2>Good to knows</h2>
        <ul>
            <li>StudyDeck is a free service.</li>
            <li>We will not sell your personal info to third-parties.</li>
            <li>Read our <a href="scott_write_this_plz">privacy policy.</a></li>
            <li>We will not spam you.</li>
        <ul>
    </div>

    <div class="clear_div">&nbsp;</div>
	</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
