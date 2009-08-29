<!-- /app/views/decks/create.ctp -->
<?php
    // Javascript includes
    echo $javascript->link('jquery-1.2.6.min',false);
    echo $javascript->link('deck_create',false);

    // CSS includes
    echo $html->css('deck_create',null,null,false);

    // Nicolo's old functions
    //echo $javascript->link('createdeck',false);
?>

<div id="middle_wrapper_content">
<div id="middle_bar">

<h1>Create a Deck</h1>

<?php

    // Deck detail attributes
    $detailFieldSize = 45;
    $privacyOptions = array(1=>'Private',2=>'Public');
    $privacyAttributes = array('legend'=>false,'label'=>'Privacy:');

    // Card input attributes
    $termFieldSize = 25;
    $definitionFieldSize = 50;
    $cardTermOptions = array('type'=>'text','label'=>false,'size'=>$termFieldSize);
    $cardDefinitionOptions = array('type'=>'text','label'=>false,'size'=>$definitionFieldSize);

    echo $form->create('Deck', array('action' => 'create'));
    echo "<fieldset>\n";
    echo "<legend>Details</legend>\n";
    echo "<ol>\n";
    echo "<li>" . $form->input('Deck.deck_name',array('label'=>'Title:','size'=>$detailFieldSize)) . "</li>\n";
    echo "<li>" . $form->input('DeckTag.tag_id',array('label' => 'Tags:','size'=>$detailFieldSize)) . "</li>\n";

    echo "<li class=\"privacyOptions\">" . $form->radio('privacy',$privacyOptions,$privacyAttributes);
    //echo "<li>" . $form->input('Deck.privacy',array('label'=>'Privacy:','size'=>$detailFieldSize)) . "</li>\n";

    echo "<li>" . $form->input('Deck.description',array('label'=>'Description:','size'=>$detailFieldSize)) . "</li>\n";
    echo "</ol>\n";
    echo "</fieldset>\n";

    echo "<fieldset id=\"cards\">\n";
    echo "<legend>Cards</legend>\n";
    echo "<ol id=\"card_list\">\n";

    echo "<li>";
    echo "<div id=\"term_header\">Term</div>";
    echo "<div id=\"definition_header\">Definition</div>";
    echo "</li>";

    echo "<li>";
    echo $form->input('Card.0.question',array('type'=>'text','label'=>'1','size'=>$termFieldSize));
    echo $form->input('Card.0.answer',$cardDefinitionOptions);
    echo "</li>\n";

    echo "<li>";
    echo $form->input('Card.1.question',array('type'=>'text','label'=>'2','size'=>$termFieldSize));
    echo $form->input('Card.1.answer',$cardDefinitionOptions);
    echo "</li>\n";

    echo "<li>";
    echo $form->input('Card.2.question',array('type'=>'text','label'=>'3','size'=>$termFieldSize));
    echo $form->input('Card.2.answer',$cardDefinitionOptions);
    echo "</li>\n";

    echo "<li>";
    echo $form->input('Card.3.question',array('type'=>'text','label'=>'4','size'=>$termFieldSize));
    echo $form->input('Card.3.answer',$cardDefinitionOptions);
    echo "</li>\n";

    echo "<li>";
    echo $form->input('Card.4.question',array('type'=>'text','label'=>'5','size'=>$termFieldSize));
    echo $form->input('Card.4.answer',$cardDefinitionOptions);
    echo "</li>\n";

    echo "</ol>\n";
    echo "</fieldset>\n";

    //echo $form->button('Add a Card',array('onClick'=>'addCardRows(1)'));
    //echo $form->button('Add 5 Cards',array('onClick'=>'addCardRows(5)'));
    //echo $form->button('Add 10 Cards',array('onClick'=>'addCardRows(10)'));

    echo $form->end('Create Deck');
?>

</legend>
</fieldset>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
