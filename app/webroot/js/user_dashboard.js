$(document).ready(function(){

	



    });

function deletePublicDeck(deckID){
    if (confirm('Are you sure you want to delete your association with this deck?')){
	$.post("/MyDecks/Delete",{id:deckID},function(){$("#publicDeckRow"+ deckID).hide("fast");});

    }
	    
}

function deleteUserDeck(deckID){
    if (confirm('Are you sure you want to delete your association with this deck?')){
	  if (confirm('Do you want to completly delete this deck?')){  
	      
	  }

	  $("#userDeckRow"+ deckID).hide("fast");
    }
	    
}