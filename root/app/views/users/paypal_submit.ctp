<!-- /app/views/users/paypal_submit.ctp -->

<?php
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
?>

<style type="text/css">
    div#loading {
        width: 450px;
        margin: 50px auto;
        text-align: center;
    }
    div#loading img.loading_gif {
        margin: 50px auto;
    }
</style>

<div id="middle_wrapper_content">
	<div id="middle_bar" class="box">
    <div class="box_content">
<?php
    // Error occurred with paypal.ini
    if (isset($error) && $error) {
?>
    <p>Error occurred, please contact the Studydeck team.</p>
<?php
    }
    // Success
    else {
?>
    <div id="loading">
        <h1>Please wait while we send you to PayPal...<h1>
        <img class="loading_gif" src="/img/circle_loading.gif" alt="Loading" />
        <?php echo $button; ?>
        <script type="text/javascript">
            $("#paypal_form").submit(); 
        </script>
    </div>
<?php
    // end else
    }
?>
	</div> <!-- end box_content -->
	</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
