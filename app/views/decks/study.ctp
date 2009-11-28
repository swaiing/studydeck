<!-- File: /app/views/decks/study.ctp -->

<?php
    echo $javascript->link('jquery-1.2.6.min',false) . "\n";
    echo $javascript->link('deck_study',false) . "\n";;
    echo $html->css('deck_study',null,null,false) . "\n";
?>

<script type="text/javascript">
<?php
    echo "var deckData = " . $javascript->object($deckData) . ";\n";
    echo "var cardData = " . $javascript->object($cards) . ";\n";
    echo "var cardRatingsData = " . $javascript->object($cardsRatings) . ";\n";
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
        echo $html->link('Stop Learning',
                          array('controller'=>'decks','action'=>'quitStudy',$deckId),
                          array('class'=>'top_link'));
  ?>
</div>

<!-- Insert deck_viewer presentation -->
<?php echo $this->element('deck_viewer'); ?>
