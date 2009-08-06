<!-- /app/views/decks/explore.ctp -->

<div id="middle_wrapper_content">
<div id="middle_bar">

<h1>Explore Decks</h1>
<table>
	<tr>
		<th>ID</th>
		<th>Deck Name</th>
		<th>Description</th>
		<th>Study</th>
		<th>Privacy</th>
		<th>View Count</th>
		<th>User</th>
	</tr>
	<?php foreach ($decks as $deck): ?>
	<tr>
		<td><?php echo $deck['Deck']['id']; ?> </td>
		<td>
		<?php
		  echo $html->link($deck['Deck']['deck_name'],"/decks/view/".$deck['Deck']['id']);
		?>
		</td>
		<td><?php echo $deck['Deck']['description']; ?> </td>
		<td>
		<?php
		  echo $html->link("Study","/decks/study/".$deck['Deck']['id']);
		?>
		</td>
		<td><?php echo $deck['Deck']['privacy']; ?> </td>
		<td><?php echo $deck['Deck']['view_count']; ?> </td>
		<td><?php echo $deck['Deck']['user_id']; ?> </td>
	</tr>
	<?php endforeach; ?>
</table>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
