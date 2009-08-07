<!-- /app/views/users/login.ctp -->

<div id="middle_wrapper_content">
<div id="middle_bar">

<h1>Login</h1>
<?php
        $session->flash('auth');
        echo $form->create('User',array('action'=> 'login'));
        echo $form->input('username');
        echo $form->input('password');
        echo $form->end('Login');
?>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
