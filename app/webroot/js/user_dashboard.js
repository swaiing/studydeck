$(document).ready(function(){

	



    });

//Removes a public deck from the user dashboard
function deletePublicDeck(deckID){
	if (confirm('Do you want to remove this deck from your dashboard?')){
		$.post("/studydeck/my_decks/delete",{id:deckID},function(){$("#publicDeckRow"+ deckID).hide("fast");});

    }
	    
}
//Either removes a user deck from their dashboard or
//deletes the user deck permenantly
function deleteUserDeck(deckID){
	if (confirm('Do you want to remove this deck from your dashboard?')){
		if (confirm('Do you want to completly delete this deck?')){  
	       $.post("/studydeck/decks/delete",{id:deckID},function(){$("#userDeckRow"+ deckID).hide("fast");});
	  	}
	  	else{
	    	$.post("/studydeck/my_decks/delete",{id:deckID},function(){$("#userDeckRow"+ deckID).hide("fast");});
	 
	  	}
    }
	    
}