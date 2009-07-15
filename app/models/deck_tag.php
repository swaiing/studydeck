<?php
class DeckTag extends AppModel {
      var $name = 'DeckTag';
      var $belongsTo = array('Tag','Deck');
      
}
?>