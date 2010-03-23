<!-- File: /app/views/elements/results_tab.ctp -->
<?php
  $RATING_MAP = array(0 => "No Rating",
                      1 => "easy",
                      2 => "medium",
                      3 => "hard");
  $DEFAULT_RATING = 3;
  $correctCount = 0;
  $incorrectCount = 0;
?>

<div id="results_tab">

<!-- Static table header -->
<table class="deck_table">
    <col class="col_correct"/>
    <col class="col_num"/>
    <col class="col_term_defn"/>
    <col class="col_history"/>
    <col class="col_rating"/>
    <thead>
        <tr class="header_row">
            <th></th>
            <th></th>
            <th>Question and Answer</th>
            <th class="history">History</th>
            <th class="edit_rating">
                <button type="button" class="btn"><span><span><b>&nbsp;</b><u>Edit Difficulties</u></span></span></button>
            </th>

        </tr>
    </thead>
    </table>

    <!-- Quiz review in scrollable div -->
    <div class="table_scroll">
    <table class="deck_table">
        <col class="col_correct"/>
        <col class="col_num"/>
        <col class="col_term_defn"/>
        <col class="col_history"/>
        <col class="col_rating"/>
        <tbody>

<?php
  foreach($quiz as $id => $card) {

    // Confirm $correct is 0 or 1
    // Skip if it is otherwise/null, b/c card is in session due to rating being set
    $correct = $card['Result']['last_guess'];
    $quizzed = preg_match("/[0|1]/",$correct);
    if(!$quizzed) {
        continue;
    }

    // Set table vars
    $order = $cardsIndexed[$id]['Card']['card_order'];
    $term = $cardsIndexed[$id]['Card']['question'];
    $defn = $cardsIndexed[$id]['Card']['answer'];
    $rating = $cardsIndexed[$id]['Rating']['rating'];
    if(empty($rating)) {
        $rating = $DEFAULT_RATING;
    }
    $ratingStr = $RATING_MAP[$rating];

    // Build Google Chart: http://code.google.com/apis/chart/types.html#bar_charts
    // From data in $ratingMap and $resultMap arrays
    $totalCorrect = 0;
    $totalIncorrect = 0;
    if(array_key_exists($id, $cardsResults)) {
      $totalCorrect = $cardsResults[$id]['total_correct'];
      $totalIncorrect = $cardsResults[$id]['total_incorrect'];
    }
    $totalAnswered = $totalCorrect + $totalIncorrect;
    // percent answered correctly, expressed as whole number
    $p = round(($totalCorrect/$totalAnswered)*100);
    // remainder out of 100
    $r = 100 - $p;

    // No gradient not great colors
    //http://chart.apis.google.com/chart?cht=bhs&chbh=r,0&chco=00FF00,FF0000&chs=150x40&chd=t:30|70
    //$correctImgStr = "http://chart.apis.google.com/chart?cht=bhs&chbh=r,0&chco=00FF00,FF0000&chs=100x15&chd=t:$p|$r";

    // Better gradient with colors
    //http://chart.apis.google.com/chart?cht=bhs&chco=228B22,FF000A,&chs=130x25&chd=t:50|50&chf=b0,lg,180,228B22,0,2CB52C,1|b1,lg,180,FF000A,0,FF6167,1&chbh=r,0
    $correctImgStr = "http://chart.apis.google.com/chart?cht=bhs&chco=228B22,FF000A,&chs=130x20&chd=t:$p|$r&chf=b0,lg,180,228B22,0,2CB52C,1|b1,lg,180,FF000A,0,FF6167,1&chbh=r,0";

    // Icon for correct/incorrect
    if(strcmp($correct, '1') == 0) {
        $correctCount++;
        $correctIconImg = $html->image('right.png', array('alt' => 'Right'));
    }
    else {
        $incorrectCount++;
        $correctIconImg = $html->image('wrong.png', array('alt' => 'wrong'));
    }

    // Output cell html
    echo "<tr class=\"card_row id_" . $id . "\">";
    echo "<td>" . $correctIconImg . "</td>";
    echo "<td>" . $order . "</td>";
    echo "<td>";
    echo "<div class=\"term\">$term</div>";
    echo "<div class=\"defn\">$defn</div>";
    echo "</td>";
    echo "<td class=\"history\">" . $totalCorrect . "/" . $totalAnswered . "<img src=\"" . $correctImgStr . "\" alt=\"Correct distribution\" /></td>";
    echo "<td class=\"rts_col\">";
    echo "<span class=\"rating\">$ratingStr</span>";
    echo "</td>";
    echo "</tr>";
  }
?>
    </tbody>
</table>

<?php
    $grade = round(($correctCount / ($correctCount + $incorrectCount))*100);

    // Build Google Chart: http://code.google.com/apis/chart/types.html#pie_charts
    // For distribution of correct/incorrect for the quiz
    //http://chart.apis.google.com/chart?cht=bhs&chco=228B22,FF000A,&chs=130x25&chd=t:50|50&chf=b0,lg,180,228B22,0,2CB52C,1|b1,lg,180,FF000A,0,FF6167,1&chbh=r,0
    //http://chart.apis.google.com/chart?cht=p3&chd=t:3,8&chco=00FF00,FF0000&chs=350x100&chl=Correct(3)|Incorrect(8)
    $pieChartImgUrl = "http://chart.apis.google.com/chart?cht=p3&chd=t:" . $correctCount . "," . $incorrectCount .
                      "&chco=2CB52C,FF000A&chs=350x100&chl=Correct(" . $correctCount . ")|" . "Incorrect(" . $incorrectCount . ")";

?>

<div id="summary">
    <img src="<?php echo $pieChartImgUrl; ?>" alt="Quiz Results Distribution" />
    <div id="grade"><?php echo $grade; ?>%</div>
    <div class="clear_div">&nbsp;</div>
</div>

</div> <!-- end div.table_scroll -->

</div> <!-- end results_tab -->
