// deck_create.js

$(document).ready(function(){

    // Event handler for last input box
    $("ol#card_list li:last input:last").blur(function(event) {
        addCardRow(event);
        //addRow(event);
    });
	
	$("div.plus").click(function(event) { addRow(event); });
	$("div.minus").click(function(event) { subtractRow(event); });

});


/*
 * Event handler when '+' is clicked
 *
 */
function addRow(event) {

	var parent = $(event.target).parent();
	var newRow = parent.clone();
	
	// Call changeRowNumbering to set attributes of newRow
	var isIncreasing = true;
	changeRowNumbering(newRow, isIncreasing, true);

	// Prepend row to bottom
  newRow.insertAfter(parent);
	
	// Adds the click event to all the plus and minus buttons
	//TODO: make this so it only adds click to newly added plus and minus  
	$("div.plus").click(function(event) { addRow(event); });
	$("div.minus").click(function(event) { subtractRow(event); });
	
  // Re-number the rest of the rows 
	var increaseNumbering = true;
	adjustNumbering(newRow, increaseNumbering);
}

/*
 * Event handler when '-' is clicked
 *
 */
function subtractRow(event) {
	var parent = $(event.target).parent();
	var prevSibling = parent.prev();

  // Remove row
	parent.remove();
	
  // Re-number the rest of the rows
	var increaseNumbering = false;
	adjustNumbering(prevSibling, increaseNumbering);
}

/*
 * Called by addRow/subtractRow to increment/decrement numbering
 *
 */
function adjustNumbering(newRow, isIncreasing) {

	var currentRow = newRow.next();
	
	while(currentRow != null) {
		// Call changeRowNumbering to set attributes of currentRow
		changeRowNumbering(currentRow, isIncreasing, false);
		currentRow = currentRow.next();
	}
}

/*
 * Takes a row element and changes the values of it's attributes.
 *
 */
function changeRowNumbering(row, isIncreasing, creatingNewRow) {

  // Change row label contents
  var rowLabelElt = row.find("label");
  var curRowNum = rowLabelElt.html();
  var rowQstIdAttr = rowLabelElt.attr("for");

  // Update row label
  if(isIncreasing) {
    curRowNum++;
  }
  else {
    curRowNum--;
  }
  rowLabelElt.html(curRowNum);

  // Update question input
  var rowQstInputElt = row.find("input#"+rowQstIdAttr);
  var increaseNumbering = true;
  var newRowQstIdAttr = changeNumInString(rowQstIdAttr, increaseNumbering);
  var newRowQstNameAttr = changeNumInString(rowQstInputElt.attr("name"), increaseNumbering);
  rowLabelElt.attr("for",newRowQstIdAttr);
  rowQstInputElt.attr("id",newRowQstIdAttr);
  rowQstInputElt.attr("name",newRowQstNameAttr);

  // Update answer input
  var rowAnsInputElt = row.find("input:last");
  var newRowIdAttr = changeNumInString(rowAnsInputElt.attr("id"), increaseNumbering);
  var newRowNameAttr = changeNumInString(rowAnsInputElt.attr("name"), increaseNumbering);
  rowAnsInputElt.attr("id",newRowIdAttr);
  rowAnsInputElt.attr("name",newRowNameAttr);

  // Clear the field values if row is new
  if(creatingNewRow) {
    rowQstInputElt.attr("value","");
    rowAnsInputElt.attr("value","");
  }
}


/*
 * Event handler for when the last text box loses focus.
 * i.e. intended for tabbing in last box, to create new 
 * row.
 *
 */
function addCardRow(event) {

  // Clone new row
  var newRow = $("ol#card_list li:last").clone();
	
  // Call changeRowNumbering to set attributes of newRow
  var isIncreasing = true;
  changeRowNumbering(newRow, isIncreasing, true);

  // Remove event handler
  $("ol#card_list li:last input:last").unbind("blur");

  // Prepend row to bottom
  newRow.appendTo("ol#card_list");

  // Add event handler 
  $("ol#card_list li:last input:last").blur(function(event) { addCardRow(event); });

  // Set focus on next input box
  var inputToFocus = newRow.find("input:first");
  inputToFocus.focus();
}

/*
 * Finds the first instance of a number in a string,
 * increments it and returns the string.
 */
function changeNumInString(str, isIncreasing) {
	
	var firstNumIndex = str.search(/\d/g);
  if(firstNumIndex == -1) {
      return str;
  }
  var head = str.substr(0,firstNumIndex);
  var temp = str.substr(firstNumIndex);
  var lastNumIndex = temp.search(/\D/g);
  var num = temp.substr(0,lastNumIndex);
	
	if(isIncreasing) {
		num++;
	}
	else {
		num--;
	}
	
  var tail = temp.substr(lastNumIndex);
  var reformedStr = head + num + tail;
  return reformedStr;
}

//uploads CSV into create deck form
function uploadCsv(){
   
	$('#upload_csv_form').ajaxSubmit({dataType: 'json', success: csvToForm});
    
   
}

//called after successful upload
//converts returned JSON data into the question and answer form
function csvToForm(res){
 
	var cardNum;
	for (cardNum = 1; cardNum <= res.totalCount; cardNum++){
		var questionID = "#Card" + (cardNum - 1) + "Question";
		var anwserID = "#Card" + (cardNum - 1) + "Answer";
		if(!$(questionID).length){
	    	addCardRow();	    
		}

		$(questionID).val(res[cardNum]["q"]);
		$(anwserID).val(res[cardNum]["a"]);

    }

}
