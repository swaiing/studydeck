<?php
class Deck extends AppModel {
		var $name = 'Deck';
		var $belongsTo = 'User';
		var $hasMany = array('MyDeck','Card','DeckTag');
		var $actsAs = array ('Searchable');
		var $validate = array(
			'deck_name' => array(
				'alphaNumeric' => array(
					'rule' => 'alphaNumeric',
					'required' => true,
					'message' => 'Letters and numbers only'
				),
				'between' => array(
					'rule' => array('between', 3, 127),
					'message' => 'Must be between 3 to 127 characters'
				)
			),
			'description' => array(
				'alphaNumeric' => array(
					'rule' => 'alphaNumeric',
					'required' => true,
					'message' => 'Letters and numbers only'
				),
				'maxLength' => array(
					'rule' => array('maxLength', 255),
					'message' => 'Description must be less than 255 characters'
				)
			)
		);
	
}
?>