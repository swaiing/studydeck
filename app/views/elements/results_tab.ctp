<!-- File: /app/views/elements/deck_viewer.ctp -->

<div id="results_tab">

<table class="deck_table">
    <tr class="header_row">
        <th>#</th>
        <th>Question</th>
        <th>Answer</th>
        <th>Difficulty</th>
        <th>Correct</th>
        <th>History</th>
    </tr>

<?php
  $RATING_MAP = array(0 => "No Rating",
                      1 => "Easy",
                      2 => "Medium",
                      3 => "Hard");
  $CORRECT_MAP = array(0 => "Incorrect",
                       1 => "Correct");
  $correctCount = 0;
  $incorrectCount = 0;

  foreach($quiz as $id => $card) {

    // Confirm $correct is 0 or 1
    // Skip if it is otherwise/null, b/c card is in session due to rating being set
    $correct = $card['Result']['last_guess'];
    $quizzed = preg_match("/[0|1]/",$correct);
    if($quizzed) {
      $correctStr = $CORRECT_MAP[$correct];
    }
    else {
        continue;
    }

    // Set table vars
    $order = $cardsIndexed[$id]['Card']['card_order'];
    $term = $cardsIndexed[$id]['Card']['question'];
    $defn = $cardsIndexed[$id]['Card']['answer'];
    $rating = $cardsIndexed[$id]['Rating']['rating'];
    $ratingStr = $RATING_MAP[$rating];

    // Build Google Chart: http://code.google.com/apis/chart/types.html#bar_charts
    // From data in $ratingMap and $resultMap arrays
    $totalCorrect = 0;
    $totalIncorrect = 0;
    if(array_key_exists($id,$cardsResults)) {
      $totalCorrect = $cardsResults[$id]['total_correct'];
      $totalIncorrect = $cardsResults[$id]['total_incorrect'];
    }
    $totalAnswered = $totalCorrect + $totalIncorrect;
    // percent answered correctly, expressed as whole number
    $p = round(($totalCorrect/$totalAnswered)*100);
    // remainder out of 100
    $r = 100 - $p;
    //http://chart.apis.google.com/chart?cht=bhs&chco=00FF00,FF0000&chs=150x40&chd=t:30|70
    $correctImgStr = "http://chart.apis.google.com/chart?cht=bhs&chco=00FF00,FF0000&chs=100x15&chd=t:$p|$r";

    // Icon for correct/incorrect
    if(strcmp($correct,'1') == 0) {
        $correctCount++;
        $correctIconImg = $html->image('right.png', array('alt' => 'Right'));
    }
    else {
        $incorrectCount++;
        $correctIconImg = $html->image('wrong.png', array('alt' => 'wrong'));
    }

    // Output cell html
    echo "<td>" . $order . "</td>";
    echo "<td>" . $term . "</td>";
    echo "<td>" . $defn . "</td>";
    echo "<td class=\"center\">" . $ratingStr . "</td>";
    echo "<td class=\"center\">" . $correctIconImg . "</td>";
    echo "<td class=\"center\">" . $totalCorrect . "/" . $totalAnswered . "<img src=\"" . $correctImgStr . "\" alt=\"Correct distribution\" \></td>";
    echo "</tr>";
  }
?>

</table>

<?php
    $grade = round(($correctCount / ($correctCount + $incorrectCount))*100);

    // Build Google Chart: http://code.google.com/apis/chart/types.html#pie_charts
    // For distribution of correct/incorrect for the quiz
    //http://chart.apis.google.com/chart?cht=p3&chd=t:3,8&chco=00FF00,FF0000&chs=350x100&chl=Correct(3)|Incorrect(8)
    $pieChartImgUrl = "http://chart.apis.google.com/chart?cht=p3&chd=t:" . $correctCount . "," . $incorrectCount .
                      "&chco=00FF00,FF0000&chs=350x100&chl=Correct(" . $correctCount . ")|" . "Incorrect(" . $incorrectCount . ")";
?>

<div id="summary">
    <img src="<?php echo $pieChartImgUrl; ?>" alt="Quiz Results Distribution" />
    <div id="grade"><?php echo $grade; ?>%</div>
    <div class="clear_div">&nbsp;</div>
</div>

<?php //print_r($quiz); ?>
<!--
<hr/>
-->
<?php //print_r($cardsIndexed); ?>

</div> <!-- end results_tab -->
