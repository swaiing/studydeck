function addCardRows(numRows){
	
    //gets main form element
	var objform = document.getElementById("DeckCreateForm");
	
	var nextSib = objform.firstChild;
	var prevSib = objform.firstChild;
	var count = 0;
	//traverses the children of the form to count number of cards currently used
	while(true){
	    nextSib = nextSib.nextSibling;
	    //inpu text area is a card form name
	    if (nextSib.className == "input textarea"){
		count = count + 1;
	    }
	    //button signals the last of the card form names
	    else if  (nextSib.type == "button"){
		break;
	    }
	    prevSib = nextSib;
	}
	//divides count by 2 because count holds both Q and A
	var cardCount = count/2;
	var x = 0;
	//adds number of card Q and A elements based on numRows variable
	for (x = cardCount; x < (cardCount + numRows); x++){
	var divQTA = document.createElement("div");
	var divATA = document.createElement("div");
	divQTA.className="input textarea";
	divATA.className="input textarea";
	divQTA.innerHTML =' <label for="Card'+ x + 'Question">Question</label> <textarea name="data[Card]['+ x + '][question]" cols="30" rows="6" id="Card'+ x  + 'Question" ></textarea></div>';
	
	divATA.innerHTML = '<label for="Card'+ x + 'Answer">Answer</label>    <textarea name="data[Card]['+ x + '][answer]" cols="30" rows="6" id="Card'+ x + 'Answer" ></textarea></div>';
	objform.insertBefore(divQTA,nextSib);
	objform.insertBefore(divATA,nextSib);
	
	}
	
	// alert(count);	 

		 
	 
}

