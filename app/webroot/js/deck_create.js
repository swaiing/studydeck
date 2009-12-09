// deck_create.js

$(document).ready(function(){

  // Event handler for last input box
  $("ol#card_list li:last input:last").blur(function(event) {
    addRow(event);
  });
	
  // Event handler for +/- buttons
	$("div.plus").click(function(event) { addRow(event); });
	$("div.minus").click(function(event) { subtractRow(event); });
});


/*
 * Event handler when '+' is clicked
 *
 */
function addRow(event) {

    var parent;
    var newRow;
    //if event is null just add row to last line
	if(event == null) { 
        parent = $("ol#card_list li:last");
        
	}
    else {
        parent = $(event.target).parent();
        // Get the parent of the element invoking function
        if($(parent).length < 1) {
            return;
        }

        // Find <li> element
        while($(parent)[0].tagName.toLowerCase() != "li") {
            parent = $(parent).parent();
        }
    
       
        
    }
   
    // Clone this parent row
    newRow = parent.clone();
    
    // Call changeRowNumbering to set attributes of newRow
	var isIncreasing = true;
	changeRowNumbering(newRow, isIncreasing, true);

    // Remove event handler
    $("ol#card_list li:last input:last").unbind("blur");

	// Prepend row to bottom
    //skip hide if event is null because this breaks the CSV upload
    if(event != null){
        newRow.hide();
    }
    newRow.insertAfter(parent);

    // Run fade effect
    newRow.fadeIn("fast");
    
   

  // Re-number the rest of the rows 
	var increaseNumbering = true;
	renumberRemainingRows(newRow, increaseNumbering);

  // Change focus to new row added
  var inputToFocus = newRow.find("input:first");
  inputToFocus.focus();

  // Add +/- event handlers to plus/minus buttons
  $(newRow).find("div.plus").click(function(event) { addRow(event); });
  $(newRow).find("div.minus").click(function(event) { subtractRow(event); });

  // Add event handler to last input box
  $("ol#card_list li:last input:last").blur(function(event) { addRow(event); });
  

}

/*
 * Event handler when '-' is clicked
 *
 */
function subtractRow(event) {
	var parent = $(event.target).parent();
	var prevSibling = parent.prev();

  // Remove the row if there's more than one left
  if($(parent).siblings().size() > 1) {

    // Run fade effect
    $(parent).fadeOut("fast", function() { $(this).remove(); });

    // Re-number the rest of the rows
	  var increaseNumbering = false;
	  renumberRemainingRows(prevSibling, increaseNumbering);

    // Add event handler to last input box
    $("ol#card_list li:last input:last").blur(function(event) { addRow(event); });
  }
}

/*
 * Called by addRow/subtractRow to increment/decrement numbering
 *
 */
function renumberRemainingRows(newRow, isIncreasing) {

	var currentRow = $(newRow).next();
	while($(currentRow).html() != null) {
		// Call changeRowNumbering to set attributes of currentRow
		changeRowNumbering(currentRow, isIncreasing, false);
	  currentRow = $(currentRow).next();
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

// Uploads CSV into create deck form
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
	    	addRow(null);	    
		}

		$(questionID).val(res[cardNum]["q"]);
		$(anwserID).val(res[cardNum]["a"]);

    }
}
