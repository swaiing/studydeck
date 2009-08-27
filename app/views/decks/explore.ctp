<!-- /app/views/decks/explore.ctp -->

<div id="middle_wrapper_content">
<div id="middle_bar">

<h1>Explore Decks</h1>

<?php
	echo $form->create("Deck",array('action' => 'explore'));
	echo $form->input("searchQuery", array('label' => 'Search: '));
	echo $form->end("Search");


?>


<div>Sort By: <?php echo $html->link("Most Viewed","/decks/explore/popular/");echo '&nbsp;'; echo $html->link("Alphabetical","/decks/explore/alphabetical/");echo '&nbsp;'; echo $html->link("Recently Added","/decks/explore/recent/") ?></div>
<table>
	<tr>
		
		<th>Deck Name</th>
		<th>Description</th>
		<th>User</th>
		<th>Added On</th>
		<th>Privacy</th>
		<th>View Count</th>
		<th>Study</th>
	</tr>
	<?php foreach ($decks as $deck): ?>
	<tr>
		
		<td>
		<?php
		  echo $html->link($deck['Deck']['deck_name'],"/decks/view/".$deck['Deck']['id']);
		?>
		</td>
		<td><?php echo $deck['Deck']['description']; ?> </td>
		<td><?php echo $deck['Deck']['user_id']; ?> </td>
		<td><?php echo $deck['Deck']['created']; ?></td>
		<td><?php echo $deck['Deck']['privacy']; ?> </td>
		<td><?php echo $deck['Deck']['view_count']; ?> </td>
		<td>
		<?php
		  echo $html->link("Study","/decks/study/".$deck['Deck']['id']);
		?>
		</td>
		
	</tr>
	<?php endforeach; ?>
</table>
<div> Page <?php  
for($x = 1; $x <= $pages; $x++){
echo $html->link($x." ","/decks/explore/".$sort."/".$x);
echo '&nbsp;';   
}
?></div>

<p> <?php //echo $temp ?> </p>
</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
