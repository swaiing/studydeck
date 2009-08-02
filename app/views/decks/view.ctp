<!-- File: /app/views/decks/study.ctp -->

<h2>Deck Info</h2>

<h3><?php echo $deckInfo['Deck']['deck_name'] ?></h3>
<p>Created: <?php echo $deckInfo['Deck']['created'] ?></p>
<p>View Count: <?php echo $deckInfo['Deck']['view_count'] ?></p>
<p>Description: <?php echo $deckInfo['Deck']['description'] ?></p>

<table border='1'>
<tr>
  <td>Term</td>
  <td>Definition</td>
</tr>
<?php
  //print_r($deck);
  foreach ($deck as $card) {
	echo "<tr>";
	echo "<td>" . $card['Card']['question'] . "</td>";
	echo "<td>" . $card['Card']['answer'] . "</td>";
	echo "</tr>";
  }
?>
</table>
