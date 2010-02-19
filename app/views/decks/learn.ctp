<!-- File: /app/views/decks/learn.ctp -->

<?php
    //echo $javascript->link('jquery-1.2.6.min',false) . "\n";
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('deck_study',false) . "\n";;
    echo $javascript->link('deck_rts_wdg',false) . "\n";;
    echo $html->css('deck_study',null,null,false) . "\n";
    echo $html->css('deck_rts_wdg',null,null,false) . "\n";
?>

<script type="text/javascript">
<?php
    echo "var deckData = " . $javascript->object($deckData) . ";\n";
    echo "var cardData = " . $javascript->object($cards) . ";\n";
    echo "var cardResultsData = " . $javascript->object($cardsResults) . ";\n";

    // Set variable to set study mode
    echo "var MODE = MODE_STUDY;\n";
?>
</script>

<!-- <pre><?php //print_r($debug); ?></pre> -->

<div id="middle_wrapper_content">

<!-- JS rendered title -->
<div id="title_wrap">
  <h1 class="title"></h1>
  <?php
        echo $html->link('Quit',
                          array('controller'=>'decks','action'=>'quit', $deckId, "COMMIT_RATING"),
                          array('class'=>'top_link'));
  ?>
</div>

<!-- Insert deck_viewer presentation -->
<?php echo $this->element('deck_viewer'); ?>
