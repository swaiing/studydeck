// deck_create.js

$(document).ready(function(){

    // Event handler for last input box
    $("ol#card_list li:last input:last").blur(function(event) {
        addCardRow(event);
    });

});

// Add new row when last row definition input box loses focus
function addCardRow(event) {

    // Clone new row
    var newRow = $("ol#card_list li:last").clone();

    // Change row label contents
    var rowLabelElt = newRow.find("label");
    var curRowNum = rowLabelElt.html();
    var rowQstIdAttr = rowLabelElt.attr("for");

    // Update row label
    curRowNum++;
    rowLabelElt.html(curRowNum);

    // Update question input
    var rowQstInputElt = newRow.find("input#"+rowQstIdAttr);
    var newRowQstIdAttr = incrementNumInString(rowQstIdAttr);
    var newRowQstNameAttr = incrementNumInString(rowQstInputElt.attr("name"));
    rowLabelElt.attr("for",newRowQstIdAttr);
    rowQstInputElt.attr("id",newRowQstIdAttr);
    rowQstInputElt.attr("name",newRowQstNameAttr);
    rowQstInputElt.attr("value","");

    // Update answer input
    var rowAnsInputElt = newRow.find("input:last");
    var newRowIdAttr = incrementNumInString(rowAnsInputElt.attr("id"));
    var newRowNameAttr = incrementNumInString(rowAnsInputElt.attr("name"));
    rowAnsInputElt.attr("id",newRowIdAttr);
    rowAnsInputElt.attr("name",newRowNameAttr);
    rowAnsInputElt.attr("value","");

    // Remove event handler
    $("ol#card_list li:last input:last").unbind("blur");

    
    // Prepend row to bottom
    newRow.appendTo("ol#card_list");

    // Add event handler 
    $("ol#card_list li:last input:last").blur(function(event) { addCardRow(event); });

    // Set focus on next input box
    rowQstInputElt.focus();
}

// Finds the first instance of a number in a string,
// increments it and returns the string.
function incrementNumInString(str) {
    var firstNumIndex = str.search(/\d/g);
    if(firstNumIndex == -1) {
        return str;
    }
    var head = str.substr(0,firstNumIndex);
    var temp = str.substr(firstNumIndex);
    var lastNumIndex = temp.search(/\D/g);
    var num = temp.substr(0,lastNumIndex);
    num++;
    var tail = temp.substr(lastNumIndex);
    return head + num + tail;
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