<!-- File: /app/views/views/quiz.ctp -->

<?php
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('deck_class',false);                     // deck object classes
    echo $javascript->link('jquery.hotkeys-0.7.9.min.js',false);    // key-binding widget
    echo $javascript->link('deck_study',false);                     // main

    // CSS includes
    echo $html->css('deck_study',null,null,false) . "\n";
?>

<script type="text/javascript">
<?php
    echo "var deckData = " . $javascript->object($deckData) . ";\n";
    echo "var cardData = " . $javascript->object($cards) . ";\n";
    echo "var cardResultsData = " . $javascript->object($cardsResults) . ";\n";

    // Set variable to set study mode
    echo "var MODE = MODE_QUIZ;\n";
?>
</script>

<div id="middle_wrapper_content">

<div class="crumb">
    <div id="crumb_wrap">
        <?php
            // If not in dashboard show explore link, otherwise 'dashboard' link
            if($assocNope) {
                echo $html->link('Explore', array('controller'=>'decks', 'action'=>'explore'));
            }
            else {
                echo $html->link('Dashboard', array('controller'=>'users', 'action'=>'dashboard'));
            }
        ?>
        &gt;
        <?php
            $deckName = $deckData['Deck']['deck_name'];
            echo $html->link($deckName, array('controller'=>'decks', 'action'=>'info', $deckId));
        ?>
        &gt;
        <span class="you_are_here">Quiz</span>
    </div>
    <div id="crumb_quit">
        <?php
            echo $html->link("[Quit]", array('controller'=>'decks','action'=>'quit',$deckId,'COMMIT_RESULT'),
                              array('class'=>'top_link'));
        ?>
    </div>
</div>

<div id="title_wrap"><h1 class="title"></h1></div>

<?php echo $this->element('deck_viewer'); ?>
