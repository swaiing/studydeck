<!-- File: /app/views/elements/save_deck.ctp -->

<?php
    // Default attributes
    $DETAIL_FS = 45;
    $DETAIL_LENGTH = 75;
    $TERM_FS = 20;
    $TERM_LENGTH = 75;
    $DEFINITION_FS = 45;
    $DEFINITION_LENGTH = 168;
    $UPLOAD_FS = 25;
    $UPLOAD_LENGTH = 75;

    $PRIVATE_FLAG = 0;
    $PUBLIC_FLAG = 1;
    $PRIVATE_LABEL = "Private";
    $PUBLIC_LABEL = "Public";

    $DEFAULT_NUM_CARDS = 5;
    $DEFAULT_PRIVACY = $PUBLIC_FLAG;
    
    $controllerAction = 'create';
    $deckTitle = '';
    $deckDescription = '';
    $tagList = '';
    
    if($edit) {
        $DEFAULT_NUM_CARDS = count($existingCards);
        $DEFAULT_PRIVACY = $existingDeck['Deck']['privacy'];
        $controllerAction = 'edit';
        $deckTitle = $existingDeck['Deck']['deck_name'];
        $deckDescription = $existingDeck['Deck']['description'];
        
        //build current tag list
        
        foreach($existingTags as $tempTag) {
            $tagList = $tagList.$tempTag['Tag']['tag']." ";
        }
    }

    $privacyOptions = array($PRIVATE_FLAG => $PRIVATE_LABEL, $PUBLIC_FLAG=>$PUBLIC_LABEL);
    $privacyAttributes = array('legend'=>false, 'label'=>'Privacy:', 'default'=> $DEFAULT_PRIVACY);

    $cardTermOptions = array('type'=>'text', 'label'=>false, 'size'=>$TERM_FS, 'maxlength'=>$TERM_LENGTH);
    $cardDefinitionOptions = array('type'=>'text', 'label'=>false, 'size'=>$DEFINITION_FS, 'maxlength'=>$DEFINITION_LENGTH);

    // Create the form 
    echo $form->create('Deck', array('action' => $controllerAction));

    // Deck detail fields
    echo "<div id=\"deck_inputs\">\n";
    echo "<ol>\n";
    
    //set Deck Id for edit deck
    if($edit) {
        echo $form->hidden('Deck.id', array('type'=>'text', 'label'=>false,'value' => $existingDeck['Deck']['id'])); 
    }
    
    // Title
    echo "<li>" . $form->input('Deck.deck_name',array('label'=>'Title:', 'size'=>$DETAIL_FS, 'maxlength'=>$DETAIL_LENGTH, 'value' => $deckTitle)) . "</li>\n";
    
    // Tags/Categories
    echo "<li>" . $form->input('Tag.tag',array('label'=>'Tags:', 'size'=>$DETAIL_FS, 'maxlength'=>$DETAIL_LENGTH, 'id'=>'autoComplete', 'value' => $tagList)) . "</li>\n";
 
    // Privacy radio
    echo "<li class=\"privacy_options\">";
    echo "<span class=\"label\">Visibility:</span>";
    echo $form->radio('privacy', $privacyOptions, $privacyAttributes);
    echo "</li>";

    // Description
    echo "<li>" . $form->input('Deck.description',array('label'=>'Description:', 'size'=>$DETAIL_FS, 'maxlength'=>$DETAIL_LENGTH, 'value' => $deckDescription)) . "</li>\n";
    echo "</ol>\n";

    // Card inputs
    echo "<div id=\"card_inputs\">\n";
    echo "<ol id=\"card_list\">\n";

    // Row header
    echo "<li>";
    echo "<div id=\"term_header\">Question</div>";
    echo "<div id=\"definition_header\">Answer</div>";
    echo "</li>";

    // List card rows
    for($i=0; $i<$DEFAULT_NUM_CARDS; $i++) {
        $card = "Card." . $i;
        echo "<li>";
       
        $cardOrder = $i+1;
        $cardQuestion = '';
        $cardAnswer = '';
        
        if($edit) {
            $cardOrder = $existingCards[$i]['Card']['card_order'];   
            $cardQuestion = $existingCards[$i]['Card']['question'];
            $cardAnswer = $existingCards[$i]['Card']['answer'];            
        }
        // Hidden form to store card order    
        echo $form->hidden($card.".card_order", array('type'=>'text', 'label'=>false, 'size'=> 4, 'maxlength'=> 4, 'value' => $cardOrder));
        
        if($edit) {
            echo $form->hidden($card.".id", array('type'=>'text', 'label'=>false,'value' => $existingCards[$i]['Card']['id']));
        }
        echo $form->input($card.".question",array('type'=>'text','label'=>$i+1,'size'=>$TERM_FS, 'value' => $cardQuestion));
        echo $form->input($card.".answer",array('type'=>'text', 'label'=>false, 'size'=>$DEFINITION_FS, 'maxlength'=>$DEFINITION_LENGTH, 'value' => $cardAnswer));
        echo "<div class = \"plus\"></div>";
		echo "<div class = \"minus\"></div>";
        echo "</li>\n";
    }

    echo "</ol>\n";
    echo "</div>\n";    // div#card_inputs
    echo "</div>\n";    // div#deck_inputs
    echo "<div id=\"submit_deck\">";
    $submitButtonText = 'Create Studydeck';
    if($edit) {
        $submitButtonText = 'Update Deck';
    }
    echo $form->button($submitButtonText, array('type'=>'submit'));
    echo "</div>";
    echo $form->end();
?>

<div id="deleteCardDialog" title="Remove card"></div>
