<!-- /app/views/users/dashboard.ctp -->

<?php
	// Javascript includes
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js',false);
    echo $javascript->link('user_dashboard',false);

    // CSS includes
    echo $html->css('user_dashboard',null,null,false);
    echo $html->css('jquery-ui-1.7.2.custom.gray',null,null,false);

    // Chart constant
    // New chart format with gradients
    // http://chart.apis.google.com/chart?cht=bhs&chco=FCFF08,FF7B00,FF000A&chs=130x25&chd=t:30|40|30&chf=b0,lg,180,FCFF08,0,FCFF66,1|b1,lg,180,FF7B00,0,FFB077,1|b2,lg,180,FF000A,0,FF6167,1&chbh=r,0

    // Old single bar chart
    //$BAR_CHART_URL = "http://chart.apis.google.com/chart?cht=bhs&chco=FCFF08,FF7B00,FF000A&chs=130x25&chf=b0,lg,180,FCFF08,0,FCFF66,1|b1,lg,180,FF7B00,0,FFB077,1|b2,lg,180,FF000A,0,FF6167,1&chbh=r,0&chd=t:";

    // New multi-bar chart
    // http://chart.apis.google.com/chart?cht=bvg&chco=FCFF08|FF7B00|FF000A&chxt=x&chl=Easy|Med|Hard&chs=100x50&chd=t:50,20,30
    $BAR_CHART_URL = "http://chart.apis.google.com/chart?cht=bvg&chco=FCFF08|FF7B00|FF000A&chxt=x&chl=Easy|Med|Hard&chs=100x50&chd=t:";
?>

<div id="middle_wrapper_content">
	<div id="middle_bar" class="box">
	<div class="box_content">
	
		<h1>Dashboard</h1>
        <div class="description">Studydecks you have purchased or created will be displayed here.</div>

        <div id="created_others">
        <h2>Premium Studydecks</h2>
		<?php if(count($publicDecks)) { ?>
		<table class="deck_table">
            <col class="deck"/>
            <col class="num_cards"/>
            <col class="num_quizzed"/>
            <col class="last_quizzed"/>
            <col class="progress"/>
            <col class="remove"/>
            <thead>
			<tr>
				<th>Deck</th>
                <th>Total Cards</th>
				<th>Times Quizzed</th>
				<th>Last Quizzed</th>				
                <th>Progress</th>
                <th></th>
			</tr>
            </thead>
			<?php 
				foreach ($publicDecks as $pDeck):
      				echo "<tr id=\"publicDeckRow".$pDeck['Deck']['id']."\">";

			?>
				<td>
                    <?php echo $html->link(html_entity_decode($pDeck['Deck']['deck_name']),"/decks/info/".$pDeck['Deck']['id']); ?>
                    <div><?php echo $pDeck['Deck']['description']; ?></div>
				</td> 
                <?php $totalCards = $pDeck['All'];  ?>
				<td><?php echo $totalCards; ?></td>
				<td><?php echo $pDeck['MyDeck']['quiz_count']; ?></td>
				<td><?php
                    if($pDeck['MyDeck']['modified'] != null) {
                        $rt = new RelativeTimeHelper();
                        echo $rt->getRelativeTime(html_entity_decode($pDeck['MyDeck']['modified']));
                    }
                    ?>
                </td>
                <?php
                if($totalCards != 0) {    
                    $easyPercent = ($pDeck['Easy']/$totalCards)*100;
                    $mediumPercent = ($pDeck['Medium']/$totalCards)*100;
                    $hardPercent = ($pDeck['Hard']/$totalCards)*100;
                    //$progressImgStr = $BAR_CHART_URL . $easyPercent . "|" . $mediumPercent . "|" . $hardPercent;
                    $progressImgStr = $BAR_CHART_URL . $easyPercent . "," . $mediumPercent . "," . $hardPercent;
                }
                ?>
                <td> <?php if (isset($progressImgStr)) { ?>
                        <img src="<?php echo $progressImgStr; ?>" alt="">
                    <?php } ?>
                </td>
                <td><?php echo $html->link("Remove","#",array('onclick' => "publicDeleteDialog(".$pDeck['Deck']['id'].",\"".$pDeck['Deck']['deck_name']."\")"));?></td>            
            </tr>
			<?php endforeach; ?>

		</table>
		<?php }
		else {
			echo "<div class=\"nodecks\">You have not purchased any Studydecks. If you have, please contact us at support@studydeck.com";
            echo "</div>";
		}
		?>
        </div>

        <div id="created_me">
		<h2>Created By Me</h2>
		<?php if(count($userCreatedDecks)) { ?>
		<table class="deck_table">
            <col class="deck"/>
            <col class="num_cards"/>
            <col class="num_quizzed"/>
            <col class="last_quizzed"/>
            <col class="progress"/>
            <col class="remove"/>
            <thead>
			<tr>
				<th>Deck</th>
                <th>Total Cards</th>
				<th>Times Quizzed</th>
				<th>Last Quizzed</th>				
                <th>Progress</th>
                <th></th>
			</tr>
            </thead>
			<?php foreach ($userCreatedDecks as $ucDeck):
     		    echo "<tr id= \"userDeckRow".$ucDeck['Deck']['id']."\">";
			?>
				<td>
                    <?php echo $html->link(html_entity_decode($ucDeck['Deck']['deck_name']),"/decks/info/".$ucDeck['Deck']['id']); ?>
                    <div><?php echo $ucDeck['Deck']['description']; ?></div>
                </td> 
                <?php $totalCards = $ucDeck['All'];  ?>
				<td><?php echo $totalCards; ?></td>
				<td><?php echo $ucDeck['MyDeck']['quiz_count']; ?></td>
                <td><?php 
                    if($ucDeck['MyDeck']['modified'] != null) {
                        $rt = new RelativeTimeHelper();
                        echo $rt->getRelativeTime(html_entity_decode($ucDeck['MyDeck']['modified']));
                    }
                ?>
                </td>
                <?php 
                if($totalCards != 0) {
                    $easyPercent = ($ucDeck['Easy']/$totalCards)*100;
                    $mediumPercent = ($ucDeck['Medium']/$totalCards)*100;
                    $hardPercent = ($ucDeck['Hard']/$totalCards)*100;
                    //$progressImgStr = $BAR_CHART_URL . $easyPercent . "|" . $mediumPercent . "|" . $hardPercent;
                    $progressImgStr = $BAR_CHART_URL . $easyPercent . "," . $mediumPercent . "," . $hardPercent;
                }
                ?>
                <td>
                    <?php if (isset($progressImgStr)) { ?>
                        <img src="<?php echo $progressImgStr;?>" alt="" />
                    <?php } ?>
                </td>
                <td><?php echo $html->link("Remove","#",array('onclick' => "userDeleteDialog(".$ucDeck['Deck']['id'].",\"".$ucDeck['Deck']['deck_name']."\")"));?></td>
                 
            </tr>
			<?php endforeach; ?>

		</table>
		<?php }
		else {
			echo "<div class=\"nodecks\">You have not created any Studydecks.  ";
            echo $html->link("Create a Studydeck", array('controller'=>'decks', 'action'=>'create')) . ".";
            echo "</div>";
		}
		?>
        </div>
        
        <div id="displaying">Displaying <?php echo $numDecksStudied ?> decks</div>

        <!-- javascript dialog boxes -->
        <div id="publicDeleteDialog" title="Remove From Dashboard"></div>
        <div id="userDeleteDialog" title="Remove Your Deck"></div>
        
		</div> <!-- end box_content -->
		</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
