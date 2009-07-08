<h1>Decks</h1>
<table>
	<tr>
		<th>Id</th>
		<th>Deck Name </th>
		<th>Description </th>
		<th>Privacy</th>
		<th>View Count</th>
		<th>User</th>
	</tr>
	<?php foreach ($decks as $deck): ?>
	<tr>
		<td><?php echo $deck['Deck']['id']; ?> </td>
		<td><?php echo $deck['Deck']['deck_name']; ?> </td>
		<td><?php echo $deck['Deck']['description']; ?> </td>
		<td><?php echo $deck['Deck']['privacy']; ?> </td>
		<td><?php echo $deck['Deck']['view_count']; ?> </td>
		<td><?php echo $deck['Deck']['user_id']; ?> </td>
	</tr>
	<?php endforeach; ?>
</table>
