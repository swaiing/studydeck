<h3> User Dashboard </h3>

<h1>Welcome <?php echo $activeUser; ?> </h1>
<p>User Stats</p>
<table>
	<tr>
		<td>Number of Decks Studied: </td>
		<td><?php echo $numDecksStudied ?></td>
	</tr>
	<tr>
		<td>Favourite Decks: </td>
		<td><a href="/studydeck/decks/study/<?php if(count($favDecks)>0) echo $favDecks['0']['Deck']['id']; ?> "> <?php if(count($favDecks)> 0) echo $favDecks['0']['Deck']['deck_name']; ?></a></td>
		<td><a href="/studydeck/decks/study/<?php if(count($favDecks)>1) echo $favDecks['1']['Deck']['id'];?> "><?php if(count($favDecks)>1) echo $favDecks['1']['Deck']['deck_name']; ?></a></td>
	</tr>
</table>


<p>Public Decks</p>
<table>
	<tr>
		<th>Remove Deck</th>
		<th>Title</th>
		<th>Description</th>
		<th>Times Studied</th>
		<th>Last Studied</th>
		<th>Difficulty</th>
	</tr>
<?php foreach ($publicDecks as $pDeck): ?>
      <tr>
		<td><?php echo $html->link("x","/MyDecks/delete/".$pDeck['Deck']['id']);?> </td>
		<td><a href="/studydeck/decks/study/<?php echo $pDeck['Deck']['id']; ?> "> <?php echo $pDeck['Deck']['deck_name']; ?></a> </td> 
		<td> <?php echo $pDeck['Deck']['description']; ?> </td>
		<td> <?php echo $pDeck['0']; ?> </td><td> <?php echo $pDeck['1']; ?> </td>
		<td>All <?php echo $pDeck['All']; ?> Easy <?php echo $pDeck['Easy']; ?> Medium <?php echo $pDeck['Medium'] ?> Hard <?php echo $pDeck['Hard']; ?> Unclassified <?php echo $pDeck['Unclassified']; ?>  </td>
      </tr>
<?php endforeach; ?>

</table>

<p>User Created Decks </p>
<table>
	<tr>
		<th>Remove Deck</th>
		<th>Title</th>
		<th>Description</th>
		<th>Times Studied</th>
		<th>Last Studied</th>
		<th>Difficulty</th>
	</tr>
<?php foreach ($userCreatedDecks as $ucDeck): ?>
        <tr>
		<td><?php echo $html->link("x","/decks/fail/".$ucDeck['Deck']['id']);?> </td>
		<td><a href="/studydeck/decks/study/<?php echo $ucDeck['Deck']['id']; ?> "> <?php echo $ucDeck['Deck']['deck_name']; ?></a> <a href="/studydeck/decks/edit/<?php echo $ucDeck['Deck']['id']; ?>">[edit]</a> </td> 
		<td> <?php echo $ucDeck['Deck']['description']; ?> </td>
		<td> <?php echo $ucDeck['0']; ?> </td><td> <?php echo $ucDeck['1']; ?> </td>
		<td>All <?php echo $ucDeck['All']; ?> Easy <?php echo $ucDeck['Easy']; ?> Medium <?php echo $ucDeck['Medium'] ?> Hard <?php echo $ucDeck['Hard']; ?> Unclassified <?php echo $ucDeck['Unclassified']; ?>  </td>
	</tr>
<?php endforeach; ?>

</table>