<!-- /app/views/users/register.ctp -->

<div id="middle_wrapper_content">
<div id="middle_bar">

<?php
    // Field attributes
    $FIELD_SIZE = 25;

    echo $form->create('User', array('action' => 'register'));
    echo "<fieldset class=\"small\">\n";
    echo "<h1>Register</h1>\n";
    echo "<ol>\n";
    echo "<li>" . $form->input('username',array('label'=>'Username:','size'=>$FIELD_SIZE)) . "</li>\n";
    echo "<li>" . $form->input('email',array('label'=>'Email:','size'=>$FIELD_SIZE)) . "</li>\n";
    echo "<li>" . $form->input('password',array('label'=>'Password:','size'=>$FIELD_SIZE)) . "</li>\n";
    echo "<li>" . $form->input('password_confirm', array('type' => 'password','label'=>'Repeat:','size'=>$FIELD_SIZE)) . "</li>\n";
    echo $form->end('Create my account');
    echo "</fieldset>\n";
?>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
