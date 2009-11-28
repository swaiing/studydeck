<!-- File: /app/views/decks/review.ctp -->

<?php
    echo $html->css('deck_review',null,null,false) . "\n";
?>

<div id="middle_wrapper_content">
<div id="middle_bar">

<h1>Review</h1>

<div id="review_intro">
  <p>The assessment of your quiz is displayed in the table below:</p>
</div>

<div id="quiz_legend">
  <span class="legend_title">Legend</span>
  <table id="table_legend">
    <tr><td>Correct cards</td><td class='correct'>Green</td></tr>
    <tr><td>Incorrect cards</td><td class='incorrect'>Red</td></tr>
  </table>
</div>

<?php
// Debug
/*
  echo "<p>cards:</p>"; 
  echo print_r($cards);
  echo "<p>quiz results:</p>";
  echo print_r($quizResults);
  */
?>

<div id="review_table_wrap">
<table id="table_review">
  <tr class="header_row">
    <th>#</th>
    <th>Question</th>
    <th>Answer</th>
    <th>Difficulty</th>
    <th>Answered Correctly?</th>
    <th>Times Answered Correctly</th>
    <th>Times Answered Incorrectly</th>
  </tr>

<?php

  $RATING_MAP = array(0 => "No Rating",
                      1 => "Easy",
                      2 => "Medium",
                      3 => "Hard");
  $CORRECT_MAP = array(0 => "Incorrect",
                       1 => "Correct");

  $count = 1;

  foreach($cards as $id => $card) {

    // Data from $cards array
    $question = $card['Card']['question'];
    $answer = $card['Card']['answer'];
    $cardId = $card['Card']['id'];

    // Data from $ratingMap and $resultMap arrays
    $rating = null;
    if(array_key_exists($cardId,$cardsRatings)) {
      $rating = $cardsRatings[$cardId]['rating'];
    }

    $totalCorrect = 0;
    $totalIncorrect = 0;
    if(array_key_exists($cardId,$cardsResults)) {
      $totalCorrect = $cardsResults[$cardId]['total_correct'];
      $totalIncorrect = $cardsResults[$cardId]['total_incorrect'];
    }

    $cardWasQuizzed = False;
    $ratingStr = "N/A";
    $correctStr = "N/A";

    // Convert rating to human-readable string
    // Confirm $rating is 0-3
    if(preg_match("/[0-3]/",$rating)) {
      $ratingStr = $RATING_MAP[$rating];
    }


    // Get quiz result from session populated array
    if(array_key_exists($cardId,$quizResults)) {
      $cardWasQuizzed = True;
      $correct = $quizResults[$cardId]['Result']['last_guess'];

      // Confirm $correct is 0 or 1
      if(preg_match("/[0|1]/",$correct)) {
        $correctStr = $CORRECT_MAP[$correct];
      }
    }

    // Highlight row based on correct/incorrect
    if($cardWasQuizzed) {
      if(strcmp($correct,'1') == 0) {
        echo "<tr class='correct'>";
      }
      else if(strcmp($correct,'0') == 0) {
        echo "<tr class='incorrect'>";
      }
      else {
        echo "<tr>"; 
      }
    }

    echo "<td>" . $count . "</td>";
    echo "<td>" . $question . "</td>";
    echo "<td>" . $answer . "</td>";
    echo "<td>" . $ratingStr . "</td>";
    echo "<td>" . $correctStr . "</td>";
    echo "<td>" . $totalCorrect . "</td>";
    echo "<td>" . $totalIncorrect . "</td>";
    echo "</tr>";
    $count++;
  }
?>
</table>
</div>

</div> <!-- end middle_bar -->
</div> <!-- end #middle_wrapper_content -->
