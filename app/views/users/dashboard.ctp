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
		<p>User Stats</p>
		<table>
			<tr>
				<td>Number of Decks Studied: </td>
				<td><?php echo $numDecksStudied ?></td>
			</tr>
			<tr>
				<td>Favourite Decks: </td>
				<td> 
					<?php 
						if($favDeck1 != null) { 
							echo $html->link($favDeck1['Deck']['deck_name'],"/studydeck/decks/study/".$favDeck1['Deck']['id']); 
						} 
					?> 
				</td>
				<td> 
					<?php 
						if($favDeck2 != null) {
							echo $html->link($favDeck2['Deck']['deck_name'],"/studydeck/decks/study/".$favDeck2['Deck']['id']); }
					 ?> 
				</td>
			</tr>
		</table>

		<div>Sort Decks By: <a href="/studydeck/users/dashboard"> Recently Used </a> &nbsp <a href="/studydeck/users/dashboard/bycount"> Times Studied </a>
		</div>
		<p>Public Decks</p>
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
				<td>All <?php echo $pDeck['All']; ?> Easy <?php echo $pDeck['Easy']; ?> Medium <?php echo $pDeck['Medium'] ?> Hard <?php echo $pDeck['Hard']; ?> Unclassified <?php echo $pDeck['Unclassified']; ?>  
				</td>
     		</tr>
			<?php endforeach; ?>

		</table>

		<p>User Created Decks </p>
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
				<td>All <?php echo $ucDeck['All']; ?> Easy <?php echo $ucDeck['Easy']; ?> Medium <?php echo $ucDeck['Medium'] ?> Hard <?php echo $ucDeck['Hard']; ?> Unclassified <?php echo $ucDeck['Unclassified']; ?>  </td>
			</tr>
			<?php endforeach; ?>

		</table>

		</div> <!-- end middle_bar -->
</div> <!-- end middle_wrapper -->
<div class="clear_div">&nbsp;</div>
