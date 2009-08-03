<h3> User Dashboard </h3>

<h1>Welcome <?php echo $activeUser; ?> </h1>
<p>User Stats</p>
<table>
<tr><td>Number of Visits: </td><td></td></tr>
<tr><td>Number of Decks Studied: </td><td><?php echo $numDecksStudied ?></td></tr>
<tr><td>Favourite Decks: </td><td></td></tr>
</table>


<p>Public Decks</p>
<table>
<tr><th>Title</th><th>Description</th><th>Times Studied</th><th>Last Studied</th><th>Difficulty</th></tr>
<?php foreach ($publicDecks as $pDeck): ?>
<tr><td> <?php echo $pDeck['Deck']['deck_name']; ?> </td> <td> <?php echo $pDeck['Deck']['id']; ?> </td>
<td> <?php echo $pDeck['0']; ?> </td><td> <?php echo $pDeck['1']; ?> </td>
<td>  </td>
</tr>
<?php endforeach; ?>

</table>

<p>User Created Decks </p>
<table>
<tr><th>Title</th><th>Description</th><th>Times Studied</th><th>Last Studied</th><th>Difficulty</th></tr>
<?php foreach ($userCreatedDecks as $ucDeck): ?>
<tr>
<td> <?php echo $ucDeck['Deck']['deck_name']; ?><a href="/studydeck/decks/edit/<?php echo $ucDeck['Deck']['id']; ?>">[edit]</a> </td> 
<td> <?php echo $ucDeck['Deck']['id']; ?> </td>
<td> <?php echo $ucDeck['0']; ?> </td><td> <?php echo $ucDeck['1']; ?> </td>
</tr>
<?php endforeach; ?>

</table>