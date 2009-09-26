<?php
class UsersController extends AppController {
      var $name = 'Users';
      var $scaffold;
      var $components =array('Auth');
      var $uses = array('User','MyDeck','Deck','Rating');

      function beforeFilter(){
      
        // Call AppConroller::beforeFilter()
        parent::beforeFilter();
	$this->Auth->allow('register','view','customLogin');
        	       
        $this->set('prevURL', $this->Session->read('Auth.redirect'));
	

        
      }     
      function login(){
      	  // Intentionally blank
      
      }

      function customLogin($redirect = null){
      
         $modedURL = str_replace('_','/',$redirect);
	if(!empty($this->data)){
		
	   if($this->Auth->login($this->data['User'])){
		if($modedURL == "" || $modedURL =="/"){
                 	     $this->redirect('/users/dashboard');
            	}
            	else{
                 $this->redirect($modedURL);
             	 }

            }
		$findUser = $this->User->find('first', array('conditions' => array('User.email' => $this->data['User']['username'])));
		
		if ($findUser != null){
		   $this->data['User']['username'] = $findUser['User']['username'];
		   $this->data['User']['email'] = $findUser['User']['email'];
		   if($this->Auth->login($this->data['User'])){
		     if($modedURL == ""|| $modedURL =="/"){
                     		  $this->redirect('/users/dashboard');
            	      }
            	      else{
			$this->redirect($modedURL);
             		}
		   }

		}
			
			$this->Session->setFlash("Username or Passowrd is incorrect");		
			$this->redirect('/users/login');
		
			
		
	
	}
	
      }

      function logout() {
      	        $this->Auth->logout();
		$this->redirect("/");
      }

      function register(){
      	       if (!empty($this->data)) {
	                   if ($this->data['User']['password'] == $this->Auth->password($this->data['User']['password_confirm'])) {
                	   $this->User->save($this->data);
               		  $this->redirect(array('action' => 'login'));
            		  }

      		}

	}
	function view(){
		 $this->set('userView', $this->User->find('first', array('conditions' => array('username' => $this->Auth->user('username')))));
	}

	function dashboard($sortBy = null){
		 //empty arrays to place public and user decks into
		 $publicDecks=array();
		 $publicDecksNoStudy = array();
		 $userCreatedDecks=array();
		 $userCreatedDecksNoStudy = array();

		 $this->Deck->recursive=1;
		 

		 $favDeck1 = null;
		 $favDeck2 = null;
		 
		 //pulls the current user id
		 $currentUserId = $this->Auth->user('id');

		 //pulls all decks the user has in the mydecks table
		 if($sortBy == 'bycount'){
		 	    $allMyDecks = $this->MyDeck->find ('all', array('conditions' => array('MyDeck.user_id' => $currentUserId),'order'=>array('MyDeck.study_count DESC')));
		 }
		 else {
			    $allMyDecks = $this->MyDeck->find ('all', array('conditions' => array('MyDeck.user_id' => $currentUserId),'order'=>array('MyDeck.modified DESC')));
		 }
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
			 
			 
			 //hold study cound of deck
			 $tempStudyCount = $myDeck['MyDeck']['study_count'];
			 //Find favorite decks
			 if($tempStudyCount != 0){
			 	if($favDeck1 == null){
			 		$favDeck1 = array_merge($tempDeck, array('StudyCount' => $tempStudyCount));      
			 	}
			 	else if ($tempStudyCount >= $favDeck1['StudyCount']){
				     	$favDeck2 = $favDeck1;
					$favDeck1 = array_merge($tempDeck, array('StudyCount' => $tempStudyCount));
			 	}
				else if ($favDeck2 == null || $tempStudyCount >= $favDeck2['StudyCount'] ){
			 	     	$favDeck2 = array_merge($tempDeck, array('StudyCount' => $tempStudyCount));
					
			 	}
			 }
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
			 	//splits the decks into studied and unstudied decks
			 	if ($deck['0'] != '0'){    
				   array_push($userCreatedDecks, $deck);
				}
				else {
				   array_push($userCreatedDecksNoStudy, $deck);  
				}		
								
			 }
			 else {
			      if ($deck['0'] != '0'){    
				   array_push($publicDecks, $deck);
				}
				else {
				   array_push($publicDecksNoStudy, $deck);  
				}
			
			 }
			 
		 }
		 //merges studied and unstudied decks by putting undstudied decks on the back
		 $publicDecks = array_merge($publicDecks, $publicDecksNoStudy);
		 $userCreatedDecks = array_merge($userCreatedDecks, $userCreatedDecksNoStudy);

		 //sets variable used by view
		 $this->set('userCreatedDecks', $userCreatedDecks);
		 $this->set('publicDecks', $publicDecks);
		 $this->set('numDecksStudied', count($allMyDecks));
		 $this->set('favDeck1',$favDeck1);
		 $this->set('favDeck2',$favDeck2);
		 
	}

}

?>
