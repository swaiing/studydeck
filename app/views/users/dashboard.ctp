<!-- /app/views/users/dashboard.ctp -->
<?php
	// Javascript includes
    //echo $javascript->link('jquery-1.2.6.min',false);
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js',false);
    echo $javascript->link('user_dashboard',false);
    echo $html->css('jquery-ui-1.7.2.custom.gray',null,null,false);
    //echo $html->css('jquery-ui-1.7.2.custom',null,null,false);
?>

<div id="middle_wrapper_content">
	<div id="middle_bar">
	
		<h1>Dashboard</h1>
		<h2>Welcome <?php echo $activeUser; ?>!</h2>
        <br/>    
		<div>Sort Decks By: <a href="/users/dashboard">Last Quizzed</a> &nbsp <a href="/users/dashboard/bycount">Times Quizzed</a></div>
		
		<h2>Created By Me</h2>
		<?php if(count($userCreatedDecks)) { ?>
		<table class="deck_table">
			<tr>
				<th>Deck</th>
                <th>Total Cards</th>
				<th>Times Quizzed</th>
				<th>Last Quizzed</th>				
                <th>Progress</th>
                <th>Remove</th>
			</tr>
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

                    // Old chart format
                    //$progressImgStr = "http://chart.apis.google.com/chart?cht=bhs&chco=00FF00,FFFF00,FF0000&chs=150x40&chd=t:".$easyPercent."|".$mediumPercent."|".$hardPercent;

                    // New chart format with gradients
                    // http://chart.apis.google.com/chart?cht=bhs&chco=FCFF08,FF7B00,FF000A&chs=130x25&chd=t:30|40|30&chf=b0,lg,180,FCFF08,0,FCFF66,1|b1,lg,180,FF7B00,0,FFB077,1|b2,lg,180,FF000A,0,FF6167,1&chbh=r,0
                    $barChartUrl = "http://chart.apis.google.com/chart?cht=bhs&chco=FCFF08,FF7B00,FF000A&chs=130x25&chf=b0,lg,180,FCFF08,0,FCFF66,1|b1,lg,180,FF7B00,0,FFB077,1|b2,lg,180,FF000A,0,FF6167,1&chbh=r,0&chd=t:";
                    $progressImgStr = $barChartUrl . $easyPercent . "|" . $mediumPercent . "|" . $hardPercent;
                }
                ?>
                <td> <img src="<?php echo $progressImgStr;?>" alt=""></td>
                <td><?php echo $html->link("x","#",array('onclick' => "userDeleteDialog(".$ucDeck['Deck']['id'].",\"".$ucDeck['Deck']['deck_name']."\")"));?></td>
                 
            </tr>
			<?php endforeach; ?>

		</table>
		<?php }
		else {
			echo "<div class=\"nodecks\">You have not created any decks yet";
            echo "<br/>";
            echo $html->link("Create a deck!", array('controller'=>'decks', 'action'=>'create'));
            echo "</div>";
		}
		?>
        <h2>Created By Others</h2>
		<?php if(count($publicDecks)) { ?>
		<table class="deck_table">
			<tr>
				<th>Deck</th>
                <th>Total Cards</th>
				<th>Times Quizzed</th>
				<th>Last Quizzed</th>				
                <th>Progress</th>
                <th>Remove</th>
			</tr>
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
                    //$progressImgStr = "http://chart.apis.google.com/chart?cht=bhs&chco=00FF00,FFFF00,FF0000&chs=150x40&chd=t:".$easyPercent."|".$mediumPercent."|".$hardPercent;
                    $progressImgStr = $barChartUrl . $easyPercent . "|" . $mediumPercent . "|" . $hardPercent;
                }
                ?>
                <td> <img src="<?php echo $progressImgStr;?>" alt=""></td>
                <td><?php echo $html->link("x","#",array('onclick' => "publicDeleteDialog(".$pDeck['Deck']['id'].",\"".$pDeck['Deck']['deck_name']."\")"));?></td>            
            </tr>
			<?php endforeach; ?>

		</table>
		<?php }
		else {
			echo "<div class=\"nodecks\">";
            echo $html->link("Explore existing decks!", array('controller'=>'decks', 'action'=>'explore'));           
            echo "</div>";
            echo "<br/>";
		}
		?>
        
        <div>Displaying <?php echo $numDecksStudied ?> decks</div>
        <div id="publicDeleteDialog" title="Remove From Dashboard"></div>
        <div id="userDeleteDialog" title="Remove Your Deck"></div>
        
				
       
		</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
