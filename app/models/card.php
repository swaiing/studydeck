<?php
class Card extends AppModel {
      var $name = 'Card';
      var $belongsTo = array('Deck');
      var $hasMany = array('Rating');
}
?>