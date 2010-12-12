<!-- /app/views/users/paypal_submit.ctp -->

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

		echo $button
	?>
	
	<script>
		$("#paypal_form").submit(); 
	</script>

	</div> <!-- end box_content -->
	</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
