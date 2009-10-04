<?php
class TempUser extends AppModel{
      var $name = 'TempUser';
      var $validate = array(
      	  'username' => array(
	  'alphaNumeric'=> array(
	  	'rule' => 'alphaNumeric',
		'required' => true,
		'message' => 'Letters and numbers only'
		),
	  'between' => array(
	  	'rule'=> array('between', 4, 30),
		'message' => 'Username must be between 4 and 30 characters'
	        )
	  ), 
      	  'password'=>array('rule'=>array('minLength','6'),
	  'message'=> 'Password must be at least 6 characters long'),
	  'email'=>array('rule'=>'email','message'=> 'Not a valid email address'));      


}

?>