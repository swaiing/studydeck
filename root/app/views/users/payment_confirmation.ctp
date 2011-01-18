<!-- /app/views/users/payment_confirmation.ctp -->

<?php
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
?>



<div id="middle_wrapper_content">
	<div id="middle_bar" class="box">
    <div class="box_content">    
	<h2>Thank you for your payment!</h2>
	Your transaction has been completed, and a receipt for your purchase has been emailed to you.<br/> 
	Go to your <?php echo $html->link('dashboard',array('controller'=>'users','action'=>'dashboard'));?> to find the decks you have purchased.
 	</div>
	</div> <!-- end box_content -->
	</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
