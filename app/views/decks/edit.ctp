<!-- /app/views/decks/edit.ctp -->

<?php
    // Javascript includes
    //echo $javascript->link('jquery-1.2.6.min',false);
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js',false);
    echo $javascript->link('jquery.form',false);
    echo $javascript->link('deck_create',false);
    echo $javascript->link('jquery.autocomplete.min.js', false);

    // CSS includes
    echo $html->css('deck_create',null,null,false);
    echo $html->css('jquery-ui-1.7.2.custom',null,null,false);
?>

<div id="middle_wrapper_content">
<div id="middle_bar">

<h1>Edit your deck</h1>
<?php
    echo $this->element('save_deck', array('edit' => true));
?>



</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
