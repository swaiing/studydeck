<!-- File: /app/views/decks/review.ctp -->

<div id="middle_wrapper_content">
<div id="middle_bar">

<h1>Review</h1>

<?php
  /*
  echo "<p>deck:</p>"; 
  echo print_r($deck);
  echo "<p>quiz results:</p>";
  echo print_r($quizResults);
  */
?>

<table>
<tr>
  <th>#</th>
  <th>Question</th>
  <th>Answer</th>
  <th>Difficulty</th>
  <th>Answered Correctly?</th>
</tr>
<?php

  $RATING_MAP = array(0 => "No Rating",
                      1 => "Easy",
                      2 => "Medium",
                      3 => "Hard");
  $CORRECT_MAP = array(0 => "Incorrect",
                       1 => "Correct");

  $count = 1;

  foreach($deck as $cardId => $card) {

    $question = $card['Card']['question'];
    $answer = $card['Card']['answer'];
    $cardWasQuizzed = False;
    $ratingStr = "N/A";
    $correctStr = "N/A";

    if(array_key_exists($cardId,$quizResults)) {
      $cardWasQuizzed = True;
      $rating = $quizResults[$cardId]['Rating']['rating'];
      $ratingStr = $RATING_MAP[$rating];
      $correct = $quizResults[$cardId]['Result']['last_guess'];
      $correctStr = $CORRECT_MAP[$correct];
    }

    // Highlight row
    if($cardWasQuizzed) {
      if($correct) {
        echo "<tr bgcolor='#CCFFDA'>";
      }
      else {
        echo "<tr bgcolor='#FFB3C6'>";
      }
    }

    echo "<td>" . $count . "</td>";
    echo "<td>" . $question . "</td>";
    echo "<td>" . $answer . "</td>";
    echo "<td>" . $ratingStr . "</td>";
    echo "<td>" . $correctStr . "</td>";
    echo "</tr>";
    $count++;
  }
?>
</table>

</div> <!-- end middle_bar -->
</div> <!-- end #middle_wrapper_content -->
