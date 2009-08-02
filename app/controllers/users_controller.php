<?php
class UsersController extends AppController {
      var $name = 'Users';
      var $scaffold;
      var $components =array('Auth');
      
      function beforeFilter(){
      
      $this->Auth->allow('register','view');
      $this->Auth->fields = array('username'=>'username', 'password'=>'password');
      $this->Auth->loginRedirect=array('controller'=> 'users','action'=>'view');
      }     
      function login(){
      	      
      }
      function logout() {
      	       $this->redirect($this->Auth->logout());
      }
      function register(){
      	       if (!empty($this->data)) {
	                   if ($this->data['User']['password'] == $this->Auth->password($this->data['User']['password_confirm'])) {
                	   $this->User->save($this->data);
               		  // $this->redirect(array('action' => 'index'));
            		  }

      		}

	}
	function view(){
		 $this->set('userView', $this->User->find('first', array('conditions' => array('username' => $this->Auth->user('username')))));
	}

}

?>