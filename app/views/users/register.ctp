<!-- /app/views/users/register.ctp -->

<div id="middle_wrapper_content">
<div id="middle_bar">

<h1>Register</h1>
<?php
      echo $form->create('User', array('action' => 'register'));
      echo $form->input('username');
      echo $form->input('email');
      echo $form->input('password');
      echo $form->input('password_confirm', array('type' => 'password'));
      echo $form->submit();
      echo $form->end();
?>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
