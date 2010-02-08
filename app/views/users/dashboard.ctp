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
	
		<h1>Dashboard</h1>

		<p>Welcome <?php echo $activeUser; ?>!</p>
		
		<h2>User Stats</h2>
		<table>
			<tr>
				<td>Number of Decks Studied: </td>
				<td><?php echo $numDecksStudied ?></td>
			</tr>
			<?php 
			if($favDeck1 != null) { 
				echo "<tr>";
				echo "<td>Favourite Decks: </td>";
				echo "<td>"; 
				echo $html->link($favDeck1['Deck']['deck_name'],"/studydeck/decks/study/".$favDeck1['Deck']['id']); 
				echo "</td>";
				echo "<td>";					
				if($favDeck2 != null) {
					echo $html->link($favDeck2['Deck']['deck_name'],"/studydeck/decks/study/".$favDeck2['Deck']['id']); 
				}
				echo "</td>";
				echo "</tr>";
			} ?> 
		</table>

		<div>Sort Decks By: <a href="/studydeck/users/dashboard"> Recently Used </a> &nbsp <a href="/studydeck/users/dashboard/bycount"> Times Studied </a>
		</div>
		<h2>Public Decks</h2>
		<?php if(count($publicDecks)) { ?>
		<table class="deck_table">
			<tr>
				<th>Title</th>
				<th>Description</th>
				<th>Times Quizzed</th>
				<th>Last Quizzed</th>
				<th>Total Cards</th>
                <th>Easy</th>
                <th>Medium</th>
                <th>Hard</th>
                <th>Remove</th>
			</tr>
			<?php 
				foreach ($publicDecks as $pDeck):
      				echo "<tr id=\"publicDeckRow".$pDeck['Deck']['id']."\">";

			?>
				<td><a href="/studydeck/decks/info/<?php echo $pDeck['Deck']['id']; ?> "> <?php echo $pDeck['Deck']['deck_name']; ?></a> 
				</td> 
				<td><?php echo $pDeck['Deck']['description']; ?></td>
				<td><?php echo $pDeck['0']; ?></td>
				<td> <?php echo $pDeck['1']; ?> </td>
                <td><?php echo $pDeck['All']; ?></td>
                <td><?php echo $pDeck['Easy']; ?></td>
                <td><?php echo $pDeck['Medium'] ?></td>
				<td><?php echo $pDeck['Hard']; ?></td>
                <td><?php echo $html->link("x","#",array('onclick' => "publicDeleteDialog(".$pDeck['Deck']['id'].",\"".$pDeck['Deck']['deck_name']."\")"));?></td>            
            </tr>
			<?php endforeach; ?>

		</table>
		<?php }
		else {
			echo "<div class=\"nodecks\">No Public Decks in Dashboard</div>";
		}
		?>
		<h2>User Created Decks </h2>
		<?php if(count($userCreatedDecks)) { ?>
		<table class="deck_table">
			<tr>
				<th>Title</th>
				<th>Description</th>
				<th>Times Studied</th>
				<th>Last Studied</th>
				<th>Total Cards</th>
                <th>Easy</th>
                <th>Medium</th>
                <th>Hard</th>
                <th>Remove</th>
			</tr>
			<?php foreach ($userCreatedDecks as $ucDeck):
     		echo "<tr id= \"userDeckRow".$ucDeck['Deck']['id']."\">";

			?>

				<td><a href="/studydeck/decks/info/<?php echo $ucDeck['Deck']['id']; ?> "> <?php echo $ucDeck['Deck']['deck_name']; ?></a></td> 
				<td> <?php echo $ucDeck['Deck']['description']; ?> </td>
				<td> <?php echo $ucDeck['0']; ?> </td><td> <?php echo $ucDeck['1']; ?> </td>
				<td><?php echo $ucDeck['All']; ?></td>
                <td><?php echo $ucDeck['Easy']; ?></td>
                <td><?php echo $ucDeck['Medium'] ?></td>
                <td><?php echo $ucDeck['Hard']; ?></td>
                <td><?php echo $html->link("x","#",array('onclick' => "userDeleteDialog(".$ucDeck['Deck']['id'].",\"".$ucDeck['Deck']['deck_name']."\")"));?></td>
                 
            </tr>
			<?php endforeach; ?>

		</table>
		<?php }
		else {
			echo "<div class=\"nodecks\">No User Created Decks in Dashboard</div>";
		}
		?>
        <div id="publicDeleteDialog" title="Remove From Dashboard"></div>
        <div id="userDeleteDialog" title="Remove Your Deck"></div>

		</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
