<!-- File: /app/views/decks/info.ctp -->

<?php
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('jquery.corner',false);
    echo $javascript->link('deck_info',false);
    echo $html->css('deck_info',null,null,false);
?>

<?php
    // Page global constants

    $EASY = 1;
    $MEDIUM = 2;
    $HARD = 3;
    $TOTAL = 99;
?>


<div id="middle_wrapper_content">
<div id="middle_bar">

<div id="heading">
    <h1><?php echo $deckData['Deck']['deck_name']; ?></h1>
    <p><?php echo $deckData['Deck']['description']; ?></p>
</div>

<div id="middle">

    <?php echo $form->create('Deck', array('action' => 'infoSubmit')); ?>
    <div id="category_select">
        <p>1. Select rating(s):</p>

        <input type="checkbox" id="select_all_checkbox">
        <label for="select_all_checkbox">All (<?php echo $cardsRatingsCount[$TOTAL]; ?>)</label>

    <?php

        $easyLabel = "Easy ($cardsRatingsCount[$EASY])";
        $mediumLabel = "Medium ($cardsRatingsCount[$MEDIUM])";
        $hardLabel = "Hard ($cardsRatingsCount[$HARD])";

        // Create checkbox form item
        $options = array('' => array(
                        $EASY => $easyLabel,
                        $MEDIUM => $mediumLabel,
                        $HARD => $hardLabel));
        echo $form->input('RatingsSelected', array('type' => 'select', 'label' => '', 'options' => $options, 'multiple' => 'checkbox', 'disabled' => array(1,2)));

        // Hidden form field to pass deckId
        echo $form->hidden('deckId', array('value' => $deckId));
    ?>
    </div>

    <div id="mode_select">
        <p>2. Select mode:</p>
        <?php
            // Hidden form field to record bit storing whether the Quiz button was clicked
            // Javascript on-click handler bound to 'Quiz' button
            echo $form->hidden('isQuizMode', array('value' => '0'));
            echo $form->submit('Learn', array('div' => false, 'id' => 'learn_button'));
            echo $form->submit('Quiz', array('div' => false, 'id' => 'quiz_button'));
        ?>
    </div>

    <?php echo $form->end(); ?>

<!--
    <div id="category_select">
        <p>1. Select category:</p>
        <input type="checkbox" id="all_chkbox" value="all" />
        <label for="all_chkbox">All</label>
        <input type="checkbox" id="easy_chkbox" value="easy" />
        <label for="easy_chkbox">Easy</label>
        <input type="checkbox" id="medium_chkbox" value="medium" />
        <label for="medium_chkbox">Medium</label>
        <input type="checkbox" id="hard_chkbox" value="hard" />
        <label for="hard_chkbox">Hard</label>
        <input type="checkbox" id="unrated_chkbox" value="unrated" />
        <label for="unrated_chkbox">Unrated</label>
    </div>
    <div id="mode_select">
        <p>2. Select mode:</p>
        <input type="button" value="Learn" />
        <input type="button" value="Quiz" />
    </div>
-->

    <div class="clear_div">&nbsp;</div>
</div>

<div id="bottom">
    <h3>table goes here</h3>
</div>

<?php
    //print_r($deckData);
?>
</div>
</div>
