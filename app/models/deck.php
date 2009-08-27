<?php
class Deck extends AppModel {
      var $name = 'Deck';
      var $belongsTo = 'User';
      var $hasMany = array('MyDeck','Card','DeckTag');
      var $actsAs = array ('Searchable');
}
?>