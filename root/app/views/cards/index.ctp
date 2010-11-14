<h1>Decks</h1>
<table>
	<tr>
		<th>Id</th>
		<th>Question </th>
		<th>Answer</th>
		<th>Deck ID</th>
		<th>Created</th>
		<th>Modified</th>
	</tr>
	<?php foreach ($cards as $card): ?>
	<tr>
		<td><?php echo $card['Card']['id']; ?> </td>
		<td><?php echo $card['Card']['question']; ?> </td>
		<td><?php echo $card['Card']['answer']; ?> </td>
		<td><?php echo $card['Card']['deck_id']; ?> </td>
		<td><?php echo $card['Card']['created']; ?> </td>
		<td><?php echo $card['Card']['modified']; ?> </td>
	</tr>
	<?php endforeach; ?>
</table>
