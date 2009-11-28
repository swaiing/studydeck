<!-- /app/views/decks/explore.ctp -->

<div id="middle_wrapper_content">
	<div id="middle_bar">

	<h1>Explore Decks</h1>

	<?php
		echo $form->create("Deck",array('action' => 'explore'));
		echo $form->input("searchQuery", array('label' => 'Search: '));
		echo $form->end("Search");
	?>


	<div><?php //echo print_r($temp); ?></div>
	<div><a href="/studydeck/decks/explore">All Decks </a> </div>

	<div>Sort By: 
	<?php 
		echo $html->link("Most Viewed","/decks/explore/popular/1/".$queryString);echo '&nbsp;'; 
		echo $html->link("Alphabetical","/decks/explore/alphabetical/1/".$queryString);
		echo '&nbsp;'; 
		echo $html->link("Recently Added","/decks/explore/recent/1/".$queryString) 
	?>
	</div>

	<?php 
		$itemCount = ($page - 1)*20;
	?>
	<table>
		<tr>
			<th></th>
			<th>Deck Name</th>
			<th># of Cards</th>
			<th>Description</th>
			<th>Tags</th>
			<th>User</th>
			<th>Added On</th>
			<th>View Count</th>
			<th>Learn</th>
			<th>Quiz</th>
		</tr>
		<?php foreach ($decks as $deck): ?>
		<tr>
			<?php $itemCount ++; ?>
			<td><?php echo $itemCount; ?> </td>
			<td>
			<?php
		  		echo $html->link($deck['Deck']['deck_name'],"/decks/view/".$deck['Deck']['id']);
			?>
			</td>
			<td><?php echo count($deck['Card']) ?></td>
			<td><?php echo $deck['Deck']['description']; ?> </td>
			<td>
			<?php 
				foreach ($deck['DeckTag'] as $tag) {
			  
			  		if (isset($tagArray[$tag['tag_id']])) {
			  			echo $tagArray[$tag['tag_id']]." ";
			  		}
				}
			?>

		 	</td>
			<td><?php echo $deck['User']['username']; ?> </td>
			<td><?php echo $deck['Deck']['created']; ?></td>
			<td><?php echo $deck['Deck']['view_count']; ?> </td>
			<td>
			<?php
		  		echo $html->link("Learn","/decks/study/".$deck['Deck']['id']);
			?>
			</td>
			<td>
			<?php
		  		echo $html->link("Quiz","/decks/quiz/".$deck['Deck']['id']);
			?>
    		</td>
		
		</tr>
		<?php endforeach; ?>
	</table>
	<div> Page 
	<?php  
		for ($pageInc = 1; $pageInc <= $pages; $pageInc++) {
			echo $html->link($pageInc,"/decks/explore/".$sort."/".$pageInc."/".$queryString);
			echo '&nbsp;';   
		}
	?>
	</div>

	</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
