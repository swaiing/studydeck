$(document).ready(function(){
    $("#publicDeleteDialog").dialog({
        bgiframe: true, autoOpen: false, height: 200, modal: true
    });
    
    $("#userDeleteDialog").dialog({
        bgiframe: true, autoOpen: false, height: 100, width: 360, modal: true
    });
    

	



});

//Removes a public deck from the user dashboard

function deletePublicDeck(deckId){
	$.post("/studydeck/my_decks/delete",{id:deckId},function(){$("#publicDeckRow"+ deckId).hide("fast");});   
}



//Either removes a user deck from their dashboard or
//deletes the user deck permenantly
function deleteUserDeck(deckId, permanently){
	if (permanently) {
		$.post("/studydeck/decks/delete",{id:deckId},function(){$("#userDeckRow"+ deckId).hide("fast");});
	}
 	else {
        $.post("/studydeck/my_decks/delete",{id:deckId},function(){$("#userDeckRow"+ deckId).hide("fast");});
    }
	    
}

//launches the remove public deck dialog box
function publicDeleteDialog(deckId, deckTitle) {
    
    $("#publicDeleteDialog").html("Are you sure you want to delete " + deckTitle + " from your dashboard?");
    
    
    $("#publicDeleteDialog").dialog('option','buttons', { 
        No:function(){
            $(this).dialog('close');
        }, 
        Yes:function(){
            deletePublicDeck(deckId);
            $(this).dialog('close');
        }
    });
    $("#publicDeleteDialog").dialog('open');
}

//launches the remove user created deck dialog box
function userDeleteDialog(deckId, deckTitle) {
    $("#userDeleteDialog").html(deckTitle);
    $("#userDeleteDialog").dialog('option','buttons', { 
        Cancel:function(){
            $(this).dialog('close');
        }, 
        "Remove From Dashboard":function(){
            deleteUserDeck(deckId, false);
            $(this).dialog('close');
        },
        "Delete Deck":function(){
            deleteUserDeck(deckId, true);
            $(this).dialog('close');
        }
    });
    $("#userDeleteDialog").dialog('open');
}
