<!-- /app/views/decks/create.ctp -->
<?php
    // Javascript includes
    echo $javascript->link('jquery-1.2.6.min',false);
    echo $javascript->link('jquery.form',false);
    echo $javascript->link('deck_create',false);

    // CSS includes
    echo $html->css('deck_create',null,null,false);
?>

<div id="middle_wrapper_content">
<div id="middle_bar">

<h1>Create a new deck</h1>

<?php

    // Default attributes
    $detailFieldSize = 45;
    $termFieldSize = 25;
    $definitionFieldSize = 50;
    $privateFlag = 0;
    $publicFlag = 1;

    $session->flash();
    $privacyOptions = array($privateFlag=>'Private',$publicFlag=>'Public');
    $privacyAttributes = array('legend'=>false,'label'=>'Privacy:','default' => $publicFlag);


    $cardTermOptions = array('type'=>'text','label'=>false,'size'=>$termFieldSize);
    $cardDefinitionOptions = array('type'=>'text','label'=>false,'size'=>$definitionFieldSize);
    $defaultNumCards = 5;
     ;
    
    echo $form->create('Deck', array('action' => 'create'));

    // Deck detail fields
    echo "<fieldset>\n";
    echo "<legend>Details</legend>\n";
    echo "<ol>\n";

    // Title
    echo "<li>" . $form->input('Deck.deck_name',array('label'=>'Title:','size'=>$detailFieldSize)) . "</li>\n";

    // Tags/Categories
    //echo "<li>" . $form->input('DeckTag.tag_id',array('label' => 'Tags:','size'=>$detailFieldSize)) . "</li>\n";

    // Privacy radio
    echo "<li class=\"privacyOptions\">" . $form->radio('privacy',$privacyOptions,$privacyAttributes);
    //echo "<li>" . $form->input('Deck.privacy',array('label'=>'Privacy:','size'=>$detailFieldSize)) . "</li>\n";

    // Description
    echo "<li>" . $form->input('Deck.description',array('label'=>'Description:','size'=>$detailFieldSize)) . "</li>\n";
    echo "</ol>\n";
    echo "</fieldset>\n";

      

    // Card inputs
    echo "<fieldset id=\"cards\">\n";
    echo "<legend>Cards</legend>\n";
    echo "<ol id=\"card_list\">\n";

    // Row header
    echo "<li>";
    echo "<div id=\"term_header\">Term</div>";
    echo "<div id=\"definition_header\">Definition</div>";
    echo "</li>";

    // List card rows
    for($i=0; $i<$defaultNumCards; $i++) {
        $card = "Card." . $i;
        echo "<li>";
        echo $form->input($card.".question",array('type'=>'text','label'=>$i+1,'size'=>$termFieldSize));
        echo $form->input($card.".answer",$cardDefinitionOptions);
		echo "<div class = \"plus\">+</div>";
		echo "<div class = \"minus\">-</div>";
        echo "</li>\n";
    }

    echo "</ol>\n";
    echo "</fieldset>\n";
    echo "<div id=\"submit_deck\">";
    echo $form->button('Create Deck', array('type'=>'submit'));
    echo "</div>";
    echo $form->end();
  // CSV upload
    echo "<fieldset id=\"csv_upload\">\n";
    echo "<legend>CSV Upload</legend>\n";
    echo $form->create('Deck', array('name'=>'uploadCSVForm','id'=>'upload_csv_form','type'=>'file','action'=>'uploadCSV'));
    echo "<ol>\n";
    echo "<li>". $form->input('csv_file', array('label'=> 'Upload CSV File', 'type' =>'file'));  
    echo "</li>";
    echo "<li>";
    echo $form->button('Upload File', array('onClick'=>'uploadCsv()'));
    echo "</li>";
    echo $form->end();
    echo "</fieldset>\n";


?>

</legend>
</fieldset>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
