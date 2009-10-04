<?php
class UsersController extends AppController {
      var $name = 'Users';
      var $scaffold;
      var $components =array('Auth','SwiftMailer');
      var $uses = array('User','MyDeck','Deck','Rating','TempUser');

      function beforeFilter(){
      
        // Call AppConroller::beforeFilter()
        parent::beforeFilter();
	$this->Auth->allow('register','view','customLogin','confirmation','forgotPassword');
        	       
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
      	       //declares validationError variable for view
      	       $this->set('validationError','');
      	       
      	       if (!empty($this->data)) {
	       	  $this->TempUser->set($this->data);
	       	  if($this->TempUser->validates()){
	       	  	   //checks to see if the password and password confirmation match		
	                   if ($this->data['TempUser']['password'] == $this->data['TempUser']['password_confirm']) {  
			      
			      //checks to see if username is in use			      
			      $existingUser = $this->User->find('first', array('conditions' => array('User.username' => $this->data['TempUser']['username'])));
			      if($existingUser != null){
			      	$this->set('validationError','Username is already in use!');
				       
			      }
			      else{
				//checks to see if email is in use
			      	$existingUser = $this->User->find('first', array('conditions' => array('User.email' => $this->data['TempUser']['email'])));
			      	if($existingUser != null){
			      		$this->set('validationError','Email address is already in use!');
					       
			        }
				else{
					//generates a confirmation code
			      		$confirmationCode =  substr(md5(rand()),0,44);
					//encrypts the password
			      		$this->data['TempUser']['password'] = $this->Auth->password($this->data['TempUser']['password']);
			      		$this->data['TempUser']['confirmation_code'] = $confirmationCode;
					
					//creates the user in the temp user table
					//skips validation because it should already be done
                	      		$this->TempUser->save($this->data,array('validate' =>false));

					//email confirmationCode
			       		$this->SwiftMailer->smtpType = 'tls';
               		       		$this->SwiftMailer->smtpHost = 'smtp.gmail.com';
               		       		$this->SwiftMailer->smtpPort = 465;
               		       		$this->SwiftMailer->smtpUsername = 'noreply@studydeck.com';
               		       		$this->SwiftMailer->smtpPassword = 'GoGate7';
 				        $this->SwiftMailer->sendAs = 'html';
               		       		$this->SwiftMailer->from = 'noreply@studydeck.com';
               		       		//$this->SwiftMailer->fromName = 'noreply';
               		       		$this->SwiftMailer->to = $this->data['TempUser']['email'];
               		       		//set variables to template as usual
               		       		$this->set('confirmationLink', 'http://192.168.1.101/studydeck/users/confirmation/'.$confirmationCode);
        
					try {
					    if(!$this->SwiftMailer->send('confirmation', 'StudyDeck Confirmation')) {
                    		     	    $this->log("Error sending email");
            	    			    }
        				}
        				catch(Exception $e) {
              					$this->log("Failed to send email: ".$e->getMessage());
	      		
					}
        	

					//directs them to a page where alerting them that the email has been sent
					$this->redirect(array('action' => '/confirmation/registered'));
					
				 }
               		       }
            		   }
			   else{
				$this->set('validationError','Passwords do not match!');
			   }
			}

      		}

	}


	function confirmation($confirmationCode = null){
		 $this->set('confirmationError','');
		 $this->set('justRegistered','');
		 if($confirmationCode == null){
		 	$this->set('confirmationError','No Confirmation Code Provided');	      
		 }
		 else if($confirmationCode == 'registered'){
		      	$this->set('justRegistered','You will receive an email with a link. \n Please follow the link to confirm  your registration.');	   
		 }
		 else{
			$findUser = $this->TempUser->find('first', array('conditions' => array('TempUser.confirmation_code' => $confirmationCode)));
			$this->set('confirmationError',$findUser['TempUser']['email']);
			$existingUser = $this->User->find('first',array('conditions'=> array('OR'=> array('User.username'=> $findUser['TempUser']['username'],'User.email'=> $findUser['TempUser']['email']))));
			if($findUser == null){
				     $this->set('confirmationError','No user exists with this code');	     
			}
			else if($existingUser != null){
			      $this->set('confirmationError','There is already a user with your username or email. Please register again.');
			}
			else{
				$this->data['User']['username'] = $findUser['TempUser']['username'];
		   		$this->data['User']['email'] = $findUser['TempUser']['email'];
				$this->data['User']['password'] = $findUser['TempUser']['password'];
				$this->User->save($this->data);
				$this->set('foundUser',$findUser);
				$this->TempUser->delete($findUser['TempUser']['id'], false);     
			}				
				

			

		 }
	
	 
	}


	function forgotPassword(){
		  //declares validationError variable for view
      	       	  $this->set('validationError','');


		  //sets the success variable to false to prompt user for email in view
		  $this->set('success',false);

		  if (!empty($this->data)) {
		     	$existingUser = $this->User->find('first', array('conditions' => array('User.email' => $this->data['User']['email'])));
			if($existingUser == null){
			  	$this->set('validationError','There is no user associated with that email');		 

			}
			else{
				//generates a new password
			      	$tempPassword  =  substr(md5(rand()),0,8);
				
				
				//encrypts the password
			      	
			      	
				//email tempPassword
			       	$this->SwiftMailer->smtpType = 'tls';
               		       	$this->SwiftMailer->smtpHost = 'smtp.gmail.com';
               		       	$this->SwiftMailer->smtpPort = 465;
               		       	$this->SwiftMailer->smtpUsername = 'noreply@studydeck.com';
               		       	$this->SwiftMailer->smtpPassword = 'GoGate7';
 				$this->SwiftMailer->sendAs = 'html';
               		       	$this->SwiftMailer->from = 'noreply@studydeck.com';
               		        $this->SwiftMailer->to = $existingUser['User']['email'];
               		       	//set variables to template as usual
               		       	$this->set('tempPassword', $tempPassword);
				$this->set('loginLink','http://192.168.1.101/studydeck/users/login');
				try {
				    if(!$this->SwiftMailer->send('forgotPassword', 'StudyDeck')) {
                    		    	    $this->log("Error sending email");
            	    		    }
				    else{

					//if email was sent succefully reset password
					$this->data = $this->User->read(null,$existingUser['User']['id']);
					$this->data['User']['password'] = $this->Auth->password($tempPassword);
					$this->User->save($this->data);
					//gives use the success message in view
					$this->set('success',true);
					
					
					
				    } 
        			}
        			catch(Exception $e) {
              				$this->log("Failed to send email: ".$e->getMessage());
	      		
				}
        	
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
