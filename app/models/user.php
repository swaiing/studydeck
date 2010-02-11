<?php
class User extends AppModel{
    var $name = 'User';
    var $hasMany = array('Deck','MyDeck','Rating','Result');
    var $validate = array(
        'email' => array(
            'email' => array(
                'rule' => array('email', true),
                'message' => 'Please supply a valid email address.'
                ),
            'notempty' => array(
                'rule' => VALID_NOT_EMPTY,
                'message' => 'Email field cannot be blank'
                ),            
            'identicalFieldValues' => array(
                'rule' => array('identicalFieldValues', 'email_confirmation'),
                'message' => 'Email and confirmation email fields do not match'
                ), 
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This email is already in use.'
                ) 
                            
            ),            
        'auth_password' => array(
            'authenticateUser' => array(
                'rule' => array('authenticateUser', 'auth_password'),
                'message' => 'Incorrect Password'
                ),
            'notempty' => array(
                'rule' => VALID_NOT_EMPTY,
                'message' => 'Current password field cannot be blank'
                )
            ),
        'password' => array(
            'passwordLength' => array(
                'rule' => array('minLength', 6),
                'message'=> 'Password must be at least 6 characters long'
                ),
            'identicalFieldValues' => array(
                'rule' => array('identicalFieldValues', 'password_confirmation'),
                'message' => 'Password and confirmation password fields do not match'
                )
            )
        
    );
    
        
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
    
    function authenticateUser($field=array(), $auth_field = null ) 
    {
                
        if($this->field('password') == Security::hash($this->data[$this->name][$auth_field],null, true)) {
            return TRUE;
        }
        return FALSE;
    } 
}
?>
