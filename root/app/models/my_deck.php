<?php
class MyDeck extends AppModel {
      var $name = 'MyDeck';
      var $belongsTo = array('User','Deck');
}
?>