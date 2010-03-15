<!-- /app/views/decks/explore.ctp -->
<?php
    // CSS includes
    echo $html->css('deck_explore',null,null,false);
?>

<div id="middle_wrapper_content">
<div id="middle_bar" class="box">
<div class="box_content">

    <h1>Explore</h1>
    <div class="description">Find new studydecks created by others.</div>
    <!--<div id="search_box">-->
	<?php
        // Search to be integrated into navigation
		//echo $form->create("Deck",array('action' => 'explore'));
		//echo $form->input("searchQuery", array('label' => 'Search:'));
		//echo $form->end("Search");
    ?>
    <!--</div>-->

    <!--<div id="show_all">-->
        <?php //echo $html->link('All Decks', array('controller'=>'decks', 'action'=>'explore')); ?>
    <!--</div>-->

	<div id="sort_actions">
    <ul>
	<?php 
		echo "<li>" . $html->link("Popular","/decks/explore/popular/1/".$queryString) . "</li>";
		echo "<li>" . $html->link("Recent","/decks/explore/recent/1/".$queryString) . "</li>"; 
        echo "<li>" . $html->link("Alphabetical","/decks/explore/alphabetical/1/".$queryString) . "</li>";
	?>
    </ul>
	</div>

	<?php 
		$itemCount = ($page - 1)*20;
	?>
	<table class="deck_table">
		<tr>
			<th></th>
			<th>Title/Description</th>
            <th>Num. Cards</th>
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
	echo "<div id=\"pagination\">"; 
    echo "<span>Page</span>";
	for ($pageInc = 1; $pageInc <= $pages; $pageInc++) {
		echo $html->link($pageInc,"/decks/explore/".$sort."/".$pageInc."/".$queryString);
	}
	echo "</div>";
    ?>

</div> <!-- end box_content -->
</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
