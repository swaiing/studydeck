<?php
class PurchasedProduct extends AppModel {
      var $name = 'PurchasedProduct';
      var $belongsTo = array('Payment','Product','User');
      
}
?>