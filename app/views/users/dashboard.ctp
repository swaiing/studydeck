<!-- /app/views/users/dashboard.ctp -->
<?php
	// Javascript includes
    echo $javascript->link('jquery-1.2.6.min',false);
    echo $javascript->link('user_dashboard',false);
?>

<div id="middle_wrapper_content">
	<div id="middle_bar">
	<?php $session->flash(); ?>
		<h3> User Dashboard </h3>

		<h1>Welcome <?php echo $activeUser; ?> </h1>
		<?php echo $html->link('change password',"/users/changePassword"); ?>
		<p>User Stats</p>
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
		<p>Public Decks</p>
		<?php if(count($publicDecks)) { ?>
		<table>
			<tr>
				<th>Remove Deck</th>
				<th>Title</th>
				<th>Description</th>
				<th>Times Studied</th>
				<th>Last Studied</th>
				<th>Difficulty</th>
			</tr>
			<?php 
				foreach ($publicDecks as $pDeck):
      				echo "<tr id=\"publicDeckRow".$pDeck['Deck']['id']."\">";

			?>
      
				<td>
					<?php 
						echo $html->link("x","#",array('onclick' => "deletePublicDeck(".$pDeck['Deck']['id'].")"));
					?> 
				</td>
				<td><a href="/studydeck/decks/study/<?php echo $pDeck['Deck']['id']; ?> "> <?php echo $pDeck['Deck']['deck_name']; ?></a> 
				</td> 
				<td><?php echo $pDeck['Deck']['description']; ?></td>
				<td><?php echo $pDeck['0']; ?></td>
				<td> <?php echo $pDeck['1']; ?> </td>
				<td>All <?php echo $pDeck['All']; ?> Easy <?php echo $pDeck['Easy']; ?> Medium <?php echo $pDeck['Medium'] ?> Hard <?php echo $pDeck['Hard']; ?> </td>
     		</tr>
			<?php endforeach; ?>

		</table>
		<?php }
		else {
			echo "<div class=\"nodecks\">No Public Decks in Dashboard</div>";
		}
		?>
		<p>User Created Decks </p>
		<?php if(count($userCreatedDecks)) { ?>
		<table>
			<tr>
				<th>Remove Deck</th>
				<th>Title</th>
				<th>Description</th>
				<th>Times Studied</th>
				<th>Last Studied</th>
				<th>Difficulty</th>
			</tr>
			<?php foreach ($userCreatedDecks as $ucDeck):
     		echo "<tr id= \"userDeckRow".$ucDeck['Deck']['id']."\">";

			?>

				<td><?php echo $html->link("x","#",array('onclick' => "deleteUserDeck(".$ucDeck['Deck']['id'].")"));?> </td>
				<td><a href="/studydeck/decks/study/<?php echo $ucDeck['Deck']['id']; ?> "> <?php echo $ucDeck['Deck']['deck_name']; ?></a> <a href="/studydeck/decks/edit/<?php echo $ucDeck['Deck']['id']; ?>">[edit]</a> </td> 
				<td> <?php echo $ucDeck['Deck']['description']; ?> </td>
				<td> <?php echo $ucDeck['0']; ?> </td><td> <?php echo $ucDeck['1']; ?> </td>
				<td>All <?php echo $ucDeck['All']; ?> Easy <?php echo $ucDeck['Easy']; ?> Medium <?php echo $ucDeck['Medium'] ?> Hard <?php echo $ucDeck['Hard']; ?></td>
			</tr>
			<?php endforeach; ?>

		</table>
		<?php }
		else {
			echo "<div class=\"nodecks\">No User Created Decks in Dashboard</div>";
		}
		?>
		</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
