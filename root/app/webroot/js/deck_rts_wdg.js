/***
 *
 * RatingSelector class
 *
 * File: deck_rts_wdg.js
 *
 */

function RatingSelector(elt, fnStr) {

    // Check for empty div card
    if(!elt) {
        return false;
    }

    // Constants
    this.RATING_MAP = new Array("easy","medium","hard");

    // Set properties
    this.RTS_CLASS = "rts";
    this.card = null;
    this.clickFnStr = fnStr;
    this.eltEasy = null;
    this.eltMedium = null;
    this.eltHard = null;

    // String of HTML for component
    this.domStr = '<ul class=\"' + this.RTS_CLASS + '\">';
    for(var i=0; i<this.RATING_MAP.length; i++) {
        this.domStr += "<li class=\"" + this.RATING_MAP[i] + "\"></li>";
    }
    this.domStr += "</ul>";

    // Set variable for scope within closure
    var obj = this;

    // Insert into DOM unordered list
    this.rtsDom = $(this.domStr).prependTo(elt);

    // Store refernces to each expected element
    this.eltEasy = $("li.easy", elt);
    this.eltMedium = $("li.medium", elt);
    this.eltHard = $("li.hard", elt);

    // Attach mouse-events for each of the list elements
    $(this.rtsDom).children().each(function() {

        // <li> element class attribute
        var classAttr = $(this).attr('class');

        // Mouseover effect, change image
        $(this).mouseover(function() {

            var divClass = obj.RTS_CLASS + '-' + classAttr;
            var divClassSelected = "div." + divClass + '-click';

            if(!$(this).prev().is(divClassSelected)) {
                $(this).before('<div class="' + divClass + '"></div>')
                       .prev().css({display:"none"})
                       .fadeIn(100);
            }

        // Mouseout effect
        }).mouseout(function() {

            var divClass = obj.RTS_CLASS + '-' + classAttr;
            var divSelector = "div." + divClass;

            if($(this).prev().is(divSelector)) {
                $(this).prev().fadeOut(100, function() {
                    $(this).remove();
                });
            }

        // Select button on click
        }).click(function() {

            // Set selected div overlay
            obj.selectButton(classAttr, this);

            // Set new rating
            if(obj && obj.card) {
                obj.card.setRatingFromStr(classAttr);
            }

            // Callback
            if(obj.clickFnStr) {
                setTimeout(obj.clickFnStr, 500);
            }


        });

    });

    return true;
}

// Method which highlights clicked button 
RatingSelector.prototype.selectButton = function(classAttr, buttonElt) {

    // Remove hover divs
    $(buttonElt).siblings("div").remove();

    // Set overlay
    var clickDiv = this.RTS_CLASS + "-" + classAttr + "-click";
    $(buttonElt).before('<div class="' + clickDiv + '"></div>');
    $(clickDiv).css({display:"none"}).fadeIn(100);

    return true;
}

// Return formatted text to display rating
RatingSelector.prototype.getRatingString = function() {
    var str = "";
    if(this.card) {
        str = this.RATING_MAP[this.card.rating];
        //str.replace(/^[a-z]/g
    }
    return str;
}

// Method which sets the card property of the class
RatingSelector.prototype.setCard = function(card) {

    // Check null
    if(!card) {
        return false;
    }
    
    // Set card to new card instance
    this.card = card;

    // Set variable for scope within closure
    var obj = this;

    // Set button to correctly set button
    $(this.rtsDom).children().each(function() {

        // <li> element class attribute
        var classAttr = $(this).attr('class');

        // Mark selected difficulty if rating matches
        if(obj.card.getRatingStr() == classAttr) {
            obj.selectButton(classAttr, this);
        }
    });
    return false;
}

// Method which sets DOM element to update with rating string
RatingSelector.prototype.setRatingElt = function(elt) {
    if(this.card) {
        this.card.setRatingElt(elt);
    }
    return;
}
