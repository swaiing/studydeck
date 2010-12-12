<?php
class Product extends AppModel {
		var $name = 'Product';
		//var $hasMany = array('Deck','ProductsPurchased');
		var $hasMany = array('Deck');
		var $validate = array(
			'name' => array(
				'between' => array(
					'rule' => array('between', 3, 127),
					'message' => 'Product name must be between 3 to 127 characters'
				)
			)
		);
	
}
?>
