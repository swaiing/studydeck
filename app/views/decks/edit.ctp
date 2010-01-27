<!-- /app/views/decks/edit.ctp -->

<?php
    // Javascript includes
    //echo $javascript->link('jquery-1.2.6.min',false);
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('jquery.form',false);
    echo $javascript->link('deck_create',false);
    echo $javascript->link('jquery.autocomplete.min.js', false);

    // CSS includes
    echo $html->css('deck_create',null,null,false);
?>

<div id="middle_wrapper_content">
<div id="middle_bar">

<h1>Edit your deck</h1>

<?php

    // Default attributes
    $DETAIL_FS = 45;
    $DETAIL_LENGTH = 75;
    $TERM_FS = 25;
    $TERM_LENGTH = 75;
    $DEFINITION_FS = 50;
    $DEFINITION_LENGTH = 168;
    $UPLOAD_FS = 25;
    $UPLOAD_LENGTH = 75;

    $PRIVATE_FLAG = 0;
    $PUBLIC_FLAG = 1;
    $PRIVATE_LABEL = "Private";
    $PUBLIC_LABEL = "Public";

    $DEFAULT_NUM_CARDS = count($existingCards);

    

    $privacyOptions = array($PRIVATE_FLAG => $PRIVATE_LABEL, $PUBLIC_FLAG=>$PUBLIC_LABEL);
    $privacyAttributes = array('legend'=>false, 'label'=>'Privacy:', 'default'=> $existingDeck['Deck']['privacy']);

    $cardTermOptions = array('type'=>'text', 'label'=>false, 'size'=>$TERM_FS, 'maxlength'=>$TERM_LENGTH);
    $cardDefinitionOptions = array('type'=>'text', 'label'=>false, 'size'=>$DEFINITION_FS, 'maxlength'=>$DEFINITION_LENGTH);

    // Create the form 
    echo $form->create('Deck', array('action' => 'edit'));

    // Deck detail fields
    echo "<div id=\"deck_inputs\">\n";
    echo "<ol>\n";
    
    //set Deck Id
    echo $form->hidden('Deck.id', array('type'=>'text', 'label'=>false,'value' => $existingDeck['Deck']['id'])); 
    
    // Title
    echo "<li>" . $form->input('Deck.deck_name',array('label'=>'Title:', 'size'=>$DETAIL_FS, 'maxlength'=>$DETAIL_LENGTH, 'value' => $existingDeck['Deck']['deck_name'])) . "</li>\n";

    // Tags/Categories
    echo "<li>" . $form->input('Tag.tag',array('label'=>'Tags:', 'size'=>$DETAIL_FS, 'maxlength'=>$DETAIL_LENGTH, 'id'=>'autoComplete')) . "</li>\n";
 
    // Privacy radio
    echo "<li class=\"privacyOptions\">" . $form->radio('privacy', $privacyOptions, $privacyAttributes);

    // Description
    echo "<li>" . $form->input('Deck.description',array('label'=>'Description:', 'size'=>$DETAIL_FS, 'maxlength'=>$DETAIL_LENGTH, 'value' => $existingDeck['Deck']['description'])) . "</li>\n";
    echo "</ol>\n";

    // Card inputs
    echo "<div id=\"card_inputs\">\n";
    echo "<ol id=\"card_list\">\n";

    // Row header
    echo "<li>";
    echo "<div id=\"term_header\">Term</div>";
    echo "<div id=\"definition_header\">Definition</div>";
    echo "</li>";

    // List card rows
    for($i=0; $i<$DEFAULT_NUM_CARDS; $i++) {
        $card = "Card." . $i;
        echo "<li>";
        // Hidden form to store card order
        echo $form->hidden($card.".card_order", array('type'=>'text', 'label'=>false,'value' => $existingCards[$i]['Card']['card_order']));
        echo $form->hidden($card.".id", array('type'=>'text', 'label'=>false,'value' => $existingCards[$i]['Card']['id']));
        echo $form->input($card.".question",array('type'=>'text','label'=>$i+1,'size'=>$TERM_FS, 'value' => $existingCards[$i]['Card']['question']));
        echo $form->input($card.".answer",array('type'=>'text', 'label'=>false, 'size'=>$DEFINITION_FS, 'maxlength'=>$DEFINITION_LENGTH, 'value' => $existingCards[$i]['Card']['answer']));
        echo "<div class = \"plus\"></div>";
		echo "<div class = \"minus\"></div>";
        echo "</li>\n";
    }

    echo "</ol>\n";
    echo "</div>\n";    // div#card_inputs
    echo "</div>\n";    // div#deck_inputs
    echo "<div id=\"submit_deck\">";
    echo $form->button('Update Deck', array('type'=>'submit'));
    echo "</div>";
    echo $form->end();
    
    
    //echo print_r($this->data);
    //echo print_r($existingCards);
    // CSV upload
    /*
    echo "<div id=\"upload_box\">";
    echo $form->create('Deck', array('id'=>'upload_csv_form', 'type'=>'file', 'action'=>'uploadCSV'));
    echo $form->input('csv_file', array('label'=>'File:', 'type'=>'file', 'size'=>$UPLOAD_FS, 'maxlength'=>$UPLOAD_LENGTH));  
    echo $form->button('Upload', array('id'=>'upload_button', 'onClick'=>'uploadCsv()'));
    echo $form->end();
    echo "</div>";
    */

?>

</legend>
</fieldset>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
