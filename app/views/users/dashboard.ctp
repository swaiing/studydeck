<!-- /app/views/users/dashboard.ctp -->
<?php
	// Javascript includes
    //echo $javascript->link('jquery-1.2.6.min',false);
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js',false);
    echo $javascript->link('user_dashboard',false);
    echo $html->css('jquery-ui-1.7.2.custom',null,null,false);
?>

<div id="middle_wrapper_content">
	<div id="middle_bar">
	
		<h1>Welcome <?php echo $activeUser; ?>!</h1>
        <br/>    
		<div>Sort Decks By: <a href="/studydeck/users/dashboard">Last Quizzed</a> &nbsp <a href="/studydeck/users/dashboard/bycount">Times Quizzed</a></div>
		
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
                    <a href="/studydeck/decks/info/<?php echo $ucDeck['Deck']['id']; ?> "> <?php echo $ucDeck['Deck']['deck_name']; ?></a>
                    <div><?php echo $ucDeck['Deck']['description']; ?></div>
                </td> 
                <?php $totalCards = $ucDeck['All'];  ?>
				<td><?php echo $totalCards; ?></td>
				<td><?php echo $ucDeck['MyDeck']['quiz_count']; ?></td>
                <td><?php echo $ucDeck['MyDeck']['modified']; ?></td>
                <?php 
                if($totalCards != 0) {
                    $easyPercent = ($ucDeck['Easy']/$totalCards)*100;
                    $mediumPercent = ($ucDeck['Medium']/$totalCards)*100;
                    $hardPercent = ($ucDeck['Hard']/$totalCards)*100;
                    $progressImgStr = "http://chart.apis.google.com/chart?cht=bhs&chco=00FF00,FFFF00,FF0000&chs=150x40&chd=t:".$easyPercent."|".$mediumPercent."|".$hardPercent;
                }
                ?>
                <td> <img src="<?php echo $progressImgStr;?>" alt=""></td>
                <td><?php echo $html->link("x","#",array('onclick' => "userDeleteDialog(".$ucDeck['Deck']['id'].",\"".$ucDeck['Deck']['deck_name']."\")"));?></td>
                 
            </tr>
			<?php endforeach; ?>

		</table>
		<?php }
		else {
			echo "<div class=\"nodecks\">No User Created Decks in Dashboard</div>";
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
                    <a href="/studydeck/decks/info/<?php echo $pDeck['Deck']['id']; ?> "> <?php echo $pDeck['Deck']['deck_name']; ?></a> 
                    <div><?php echo $pDeck['Deck']['description']; ?></div>
				</td> 
                <?php $totalCards = $pDeck['All'];  ?>
				<td><?php echo $totalCards; ?></td>
				<td><?php echo $pDeck['MyDeck']['quiz_count']; ?></td>
				<td><?php echo $pDeck['MyDeck']['modified']; ?> </td>
                <?php
                if($totalCards != 0) {    
                    $easyPercent = ($pDeck['Easy']/$totalCards)*100;
                    $mediumPercent = ($pDeck['Medium']/$totalCards)*100;
                    $hardPercent = ($pDeck['Hard']/$totalCards)*100;
                    $progressImgStr = "http://chart.apis.google.com/chart?cht=bhs&chco=00FF00,FFFF00,FF0000&chs=150x40&chd=t:".$easyPercent."|".$mediumPercent."|".$hardPercent;
                }
                ?>
                <td> <img src="<?php echo $progressImgStr;?>" alt=""></td>
                <td><?php echo $html->link("x","#",array('onclick' => "publicDeleteDialog(".$pDeck['Deck']['id'].",\"".$pDeck['Deck']['deck_name']."\")"));?></td>            
            </tr>
			<?php endforeach; ?>

		</table>
		<?php }
		else {
			echo "<div class=\"nodecks\">No Public Decks in Dashboard</div>";
		}
		?>
        <div>Displaying <?php echo $numDecksStudied ?> decks</div>
        <div id="publicDeleteDialog" title="Remove From Dashboard"></div>
        <div id="userDeleteDialog" title="Remove Your Deck"></div>
        
				
       
		</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
