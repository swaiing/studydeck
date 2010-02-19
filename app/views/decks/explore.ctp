<!-- /app/views/decks/explore.ctp -->

<div id="middle_wrapper_content">
<div id="middle_bar">

    <h1>Explore Decks</h1>

    
    <div id="search_box">
	<?php
		echo $form->create("Deck",array('action' => 'explore'));
		echo $form->input("searchQuery", array('label' => 'Search:'));
		echo $form->end("Search");
    ?>
	
    </div>




	<!--<div><a href="/studydeck/decks/explore">All Decks</a></div>-->
    <div id="show_all">
        <?php echo $html->link('All Decks', array('controller'=>'decks', 'action'=>'explore')); ?>
    </div>

	<div>Sort By: 
	<?php 
		echo $html->link("Most Viewed","/decks/explore/popular/1/".$queryString);
		echo '&nbsp;';
        echo $html->link("Alphabetical","/decks/explore/alphabetical/1/".$queryString);
		echo '&nbsp;'; 
		echo $html->link("Recently Added","/decks/explore/recent/1/".$queryString) 
	?>
	</div>

	<?php 
		$itemCount = ($page - 1)*20;
	?>
	<table class="deck_table">
		<tr>
			<th></th>
			<th>Deck</th>
            <th># of Cards</th>
			<th>Tags</th>
			<th>User</th>
			<th>Created</th>
			<th>Quiz Count</th>
		</tr>
		<?php 
        foreach ($decks as $deck):
            echo "<tr>";
            $itemCount++;
			echo "<td>".$itemCount."</td>";
			echo "<td>".$html->link(html_entity_decode($deck['Deck']['deck_name']),"/decks/info/".$deck['Deck']['id']);
            echo "<div>".$deck['Deck']['description']."</div>";
            echo "</td>";
            echo "<td>".count($deck['Card'])."</td>";
			
			echo "<td>";
            foreach ($deck['DeckTag'] as $tag) {
			  	if (isset($tagArray[$tag['tag_id']])) {
			  		echo $tagArray[$tag['tag_id']]." ";
			  	}
			}
			echo "</td>";
			echo "<td>".$deck['User']['username']."</td>";
			echo "<td>"; 
            $rt = new RelativeTimeHelper();
            echo $rt->getRelativeTime(html_entity_decode($deck['Deck']['created']));
            
            
            echo "</td>";
			echo "<td>".$deck['Deck']['quiz_count']."</td>";
		
            echo "</tr>";
        endforeach; 
	echo "</table>";
	echo "<div> Page "; 
	for ($pageInc = 1; $pageInc <= $pages; $pageInc++) {
		echo $html->link($pageInc,"/decks/explore/".$sort."/".$pageInc."/".$queryString);
		echo '&nbsp;';   
	}
	echo "</div>";
    ?>

</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
