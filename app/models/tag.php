<?php
class Tag extends AppModel {
      var $name = 'Tag';
      var $hasMany = 'DeckTag';
      var $actsAs = array ('Searchable');
}
?>