<?php
class Rating extends AppModel {
      var $name = 'Rating';
      var $belongsTo = array('User','Card');
}
?>