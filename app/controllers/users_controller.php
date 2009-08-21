<?php
class UsersController extends AppController {
      var $name = 'Users';
      var $scaffold;
      var $components =array('Auth');
      var $uses = array('User','MyDeck','Deck','Rating');
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
		 //empty arrays to place public and user decks into
		 $publicDecks=array();
		 $userCreatedDecks=array();

		 $this->Deck->recursive=1;
		 
		 
		 //sets the activeUser variable to username of current user
		 $this->set('activeUser', $this->Auth->user('username'));
		 //pulls the current user id
		 $currentUserId = $this->Auth->user('id');
		 //pulls all decks the user has in the mydecks table
		 $allMyDecks = $this->MyDeck->find ('all', array('conditions' => array('MyDeck.user_id' => $currentUserId),'order'=>array('MyDeck.study_count DESC')));
		 //declares blank favourite deck array
		 $favouriteDecks=array();
		 //traverses each of mydecks
		 foreach ($allMyDecks as $myDeck){
		 	 //pulls the full deck info for this mydeck
		 	 $tempDeck = $this->Deck->find('first',array('conditions' => array('Deck.id' => $myDeck['MyDeck']['deck_id'])));
			 
			 $totalCards = count($tempDeck['Card']);
			 $easyR = 0;
			 $mediumR = 0;
			 $hardR = 0;
			 for ($cardX = 0; $cardX < $totalCards ; $cardX ++){
			     $tempRating = $this->Rating->find('first', array('conditions' => array ('Rating.card_id' =>$tempDeck['Card'][$cardX]['id'],'Rating.user_id' => $currentUserId)));
			     if($tempRating != NULL ){
			     		   $numRate = $tempRating['Rating']['rating'];
			     		   if ($numRate == 1){
			     		      $easyR ++;
			     		   }
			     		   elseif ($numRate ==2){
			     		   	  $mediumR ++;
			     		   }
			     		   elseif ($numRate == 3){
			     		   	  $hardR ++;
			     	           }
			     }
			 }

			 $unclassifiedR =$totalCards - $easyR - $mediumR - $hardR;
			 //add tempdeck onto favouriteDeck array
			 array_push($favouriteDecks,$tempDeck);
			 //hold study cound of deck
			 $tempStudyCount = $myDeck['MyDeck']['study_count'];
			 
			 //logic that determines whether to show last study time		 
			 if ($myDeck['MyDeck']['study_count'] == 0){
			    $tempStudyCountArray = array($tempStudyCount,"");			 				     
			 }
			 else
			 {
				$tempStudyCountArray = array($tempStudyCount,$myDeck['MyDeck']['modified']);
			 }
			 $deck = array_merge($tempDeck, $tempStudyCountArray,array("All"=> $totalCards,"Easy"=>$easyR,"Medium" => $mediumR, "Hard"=>$hardR, "Unclassified" => $unclassifiedR));
			 //splits decks between user decks and private decks
		 	 if($deck['Deck']['user_id'] == $currentUserId)	{     
				array_push($userCreatedDecks, $deck);		
								
			 }
			 else {
				array_push($publicDecks, $deck);
			 }
			 
		 }
		 //sets variable used by view
		 $this->set('userCreatedDecks', $userCreatedDecks);
		 $this->set('publicDecks', $publicDecks);
		 $this->set('numDecksStudied', count($allMyDecks));
		 $this->set('favDecks',$favouriteDecks);
		 
		 
	}

}

?>