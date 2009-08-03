<?php
class UsersController extends AppController {
      var $name = 'Users';
      var $scaffold;
      var $components =array('Auth');
      var $uses = array('User','MyDeck','Deck');
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

	function dashboard(){
		 $publicDecks=array();
		 $userCreatedDecks=array();
		 
		 
		 
		 $this->set('activeUser', $this->Auth->user('username'));
		 $currentUserId = $this->Auth->user('id');
		 $allMyDecks = $this->MyDeck->find ('all', array('conditions' => array('MyDeck.user_id' => $currentUserId)));
		
		 foreach ($allMyDecks as $myDeck){
		 	 $tempDeck = $this->Deck->find('first',array('conditions' => array('Deck.id' => $myDeck['MyDeck']['deck_id'])));
			 //$tempStudyCount = array();
			 if ($myDeck['MyDeck']['study_count'] == 0){
			    $tempStudyCount = array($myDeck['MyDeck']['study_count'],"");			 				     
			 }
			 else
			 {
				$tempStudyCount = array($myDeck['MyDeck']['study_count'],$myDeck['MyDeck']['modified']);
			 }
			 $deck = array_merge($tempDeck, $tempStudyCount);
		 	 if($deck['Deck']['user_id'] == $currentUserId)	{     
				array_push($userCreatedDecks, $deck);		
								
			 }
			 else {
				array_push($publicDecks, $deck);
			 }
			 
		 }
		 $this->set('userCreatedDecks', $userCreatedDecks);
		 $this->set('publicDecks', $publicDecks);
		 $this->set('numDecksStudied', count($allMyDecks));
		 
	}

}

?>