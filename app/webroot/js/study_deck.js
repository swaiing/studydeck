/**
 * view_deck.js
 */

  // custom
  function FlashCard(term,defn) {
    this.term = term;
    this.defn = defn;
    this.correctCount = 0;
    this.incorrectCount = 0;
    this.difficulty = 0;
    this.viewed = 0;
    this.setAsCorrect = 0;
    this.setAsIncorrect = 0;
  }

  FlashCard.prototype.toggleCorrect = function() {
    this.setAsCorrect = 1;
    this.setAsIncorrect = 0;
  }

  FlashCard.prototype.toggleIncorrect = function() {
    this.setAsCorrect = 0;
    this.setAsIncorrect = 1;
  }

  FlashCard.prototype.incrementCorrectCount = function() {
    if(this.setAsCorrect) {
	this.correctCount++;
    }
    else if(this.setAsIncorrect) {
	this.incorrectCount++;
    }
  }

  function Deck() {
    this.curCardIndex = 0;
    this.cards = new Array();
  }

  Deck.prototype.init = function() {
      // TODO: read cards from metadata
      // setup some cards
      this.cards.push(new FlashCard("recalcitrant", "disobedient"));
      this.cards.push(new FlashCard("repudiate", "disown refuse to accept or pay"));
      this.cards.push(new FlashCard("evince", "to show clearly to indicate"));
      this.cards.push(new FlashCard("impugned", "challenged to be doubted"));
      this.cards.push(new FlashCard("impiety", "having sudden energy impulsive thrusting ahead forceful"));
      this.cards.push(new FlashCard("fanny pack", "simply described as 'gay'"));
      this.cards.push(new FlashCard("antemeridian", "before noon"));
      this.cards.push(new FlashCard("ascetic", "adj. Given to severe self-denial and practicing excessive abstinence and devotion"));
      this.cards.push(new FlashCard("bedlam", "noun 1. a scene or state of wild uproar and confusion. 2. Archaic. an insane asylum or madhouse. Origin: a popular name for the Hospital of St. Mary of Bethlehem in London, which served as a lunatic asylum from ca. 1400; cf. ME Bedleem, Bethleem, OE Betleem Bethlehem Synonyms: 1. disorder, tumult, chaos, clamor, turmoil, commotion, pandemonium."));
      return;
  }

  Deck.prototype.nextCard = function() {
      this.curCard().incrementCorrectCount();
      this.curCardIndex = (this.curCardIndex+1)%this.cards.length;
      return this.cards[this.curCardIndex];
  }

  Deck.prototype.previousCard = function() {
      this.curCard().incrementCorrectCount();
      if(this.curCardIndex-1 < 0) {
	this.curCardIndex = this.cards.length-1;
      }
      else {
	this.curCardIndex = this.curCardIndex-1;
      }
      return this.cards[this.curCardIndex];
  }

  Deck.prototype.curCard = function() {
      return this.cards[this.curCardIndex];
  }

  function displayCard(card) {

   // Start with definition and evaluation bar hidden
    $("div.card p.defn").hide();
    $("#eval").hide();

    // Unselect correct/incorrect
    $("#eval a").css("background","#FFFFFF");
    $("#eval a").css("background","#FFFFFF");

    // Assign card term and definition
    $("div.card p.term").text(card.term);
    $("div.card p.defn").text(card.defn);
  }

  // jQuery onload
  $(document).ready(function() {

    // Create objects
    var myDeck = new Deck();
    myDeck.init();

    // Load the first card
    displayCard(myDeck.curCard());

    // On-click card show definition toggle
    $("div.card").click(function(event) {
	$("div.card p.defn").slideDown("fast");
	$("#eval").show();
        
    });

    // On-click load previous card
    $("div#prev_card").click(function(event) {
	displayCard(myDeck.previousCard());	
    });

    // On-click load next card
    $("div#next_card").click(function(event) {
	displayCard(myDeck.nextCard());	
    });

    // On-click correct/incorrect select
    $("#eval a").click(function(event) {
	if($(this).hasClass("correct")) {
	  $(this).css("background","#B3ECFF");
	  $("#eval a.incorrect").css("background","#FFF");
	  myDeck.curCard().toggleCorrect();
        }
        else if($(this).hasClass("incorrect")) {
	  $(this).css("background","#B3ECFF");
	  $("#eval a.correct").css("background","#FFF");
	  myDeck.curCard().toggleIncorrect();
        }
	else {
	  alert('WTF!');
	}
    });

  });  // end $(document).ready(function()
