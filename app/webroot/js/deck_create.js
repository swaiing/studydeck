// deck_create.js

$(document).ready(function(){

    //uses jquery autocomplete plug in
    $("#autoComplete").autocomplete("/studydeck/tags/autoComplete",
    {
        minChars: 1,
        cacheLength: 10,
        highlightItem: true,
        multiple: true,
        multipleSeparator: " ",
        onItemSelect: selectItem,
        onFindValue: findValue,
        formatItem: formatItem,
        autoFill: false
    });

  // Event handler for last input box
  $("ol#card_list li:last input:last").blur(function(event) {
    addRow(event);
  });
	
  // Event handler for +/- buttons
	$("div.plus").click(function(event) { addRow(event); });
	$("div.minus").click(function(event) { subtractRow(event); });
});


function selectItem(li) {
	findValue(li);
}

function findValue(li) {
	if( li == null ) return alert("No match!");

// if coming from an AJAX call, let's use the product id as the value
	if( !!li.extra ) var sValue = li.extra[0];

	// otherwise, let's just display the value in the text box
    else var sValue = li.selectValue;

    alert("The value you selected was: " + sValue);
}

function formatItem(row) {
	if(row[1] == undefined) {
		return row[0];
	}
	else {
		return row[0] + " (id: " + row[1] + ")";
	}
}


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
  //var increaseNumbering = true;
  //steve talk to me about this change
  var increaseNumbering = isIncreasing
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
  
  // Update Order value
  var rowOrderInputElt = row.find("input[name$='[order]']");
  var rowOrderIdAttr = rowOrderInputElt.attr("id");
  var rowOrderValueAttr = parseFloat(rowOrderInputElt.attr("value"));
  var newRowOrderIdAttr = changeNumInString(rowOrderIdAttr, increaseNumbering);
  var newRowOrderNameAttr = changeNumInString(rowOrderInputElt.attr("name"), increaseNumbering);
      
  rowOrderInputElt.attr("id",newRowOrderIdAttr);
  rowOrderInputElt.attr("name",newRowOrderNameAttr);
  
  if(increaseNumbering) {
    newRowOrderValueAttr = rowOrderValueAttr + 1;
  }
  else {
    newRowOrderValueAttr = rowOrderValueAttr - 1;
  }
  rowOrderInputElt.attr("value",newRowOrderValueAttr);
  
  // Update Id value
  var rowIdInputElt = row.find("input[name$='[id]']");
  var rowIdIdAttr = rowIdInputElt.attr("id");
  var newRowIdIdAttr = changeNumInString(rowIdIdAttr, increaseNumbering);
  var newRowIdNameAttr = changeNumInString(rowIdInputElt.attr("name"), increaseNumbering);
  
  rowIdInputElt.attr("id",newRowIdIdAttr);
  rowIdInputElt.attr("name",newRowIdNameAttr);
  //alert(rowIdInputElt.attr("value") + " " + rowIdIdAttr +" " + newRowIdNameAttr);
  
  // Clear the field values if row is new
  if(creatingNewRow) {
    rowQstInputElt.attr("value","");
    rowAnsInputElt.attr("value","");
    rowIdInputElt.attr("value","");
        
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
  //alert("head" + head + "num: " + num + " tail " + tail);
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
