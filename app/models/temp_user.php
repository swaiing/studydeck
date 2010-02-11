<?php
class TempUser extends AppModel{
    var $name = 'TempUser';
    var $validate = array(
      	'username' => array(
	        'alphaNumeric' => array(
	  	        'rule' => 'alphaNumeric',
		        'required' => true,
		        'message' => 'Letters and numbers only'
		    ),
	        'between' => array(
	  	        'rule'=> array('between', 4, 30),
		        'message' => 'Username must be between 4 and 30 characters'
	        ),
            'unique' => array(
                'rule' => array('checkUnique','User','username'),
                'message' => 'This username is already in use'
            )
	    ), 
      	'password'=>array(
            'minLength' => array(
                'rule' => array('minLength',6),
                'message' => 'Password must be at least 6 characters long'
            ),
            'identicalFieldValues' => array(
                'rule' => array('identicalFieldValues', 'password_confirm'),
                'message' => 'Password and confirmation password fields do not match'
            )
        ),
	    'email'=>array(
            'validEmail' => array(
                'rule'=>'email',
                'message'=> 'Not a valid email address'
            ),
            'unique' => array(
                'rule' => array('checkUnique','User','email'),
                'message' => 'This email is already in use'
            )
        )
    );
    
    
    function checkUnique($field = array(), $otherModel = null, $fieldToCheck = null) {
        $modelToQuery = ClassRegistry::init($otherModel);    
        $modelToQuery->recursive = -1;
        foreach( $field as $key => $value ){
            $existingObjectParams = array('conditions' => array($otherModel.".".$fieldToCheck => $value));			      
            $existingObject = $modelToQuery->find('first',$existingObjectParams);

            if ($existingObject != null) {
                return FALSE;   
            }
        }
        return TRUE;
        
    }
    
    function identicalFieldValues( $field=array(), $compare_field= null ) 
    {
        if(isset($this->data[$this->name][ $compare_field ])){
            foreach( $field as $key => $value ){
                $v1 = $value;
                $v2 = $this->data[$this->name][ $compare_field ];                 
                if($v1 !== $v2) {
                    return FALSE;
                } else {
                    continue;
                }
            }
        }
        return TRUE;
    }
}

?>
