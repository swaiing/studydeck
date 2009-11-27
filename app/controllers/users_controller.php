<?php 
class UsersController extends AppController {
	var $name = 'Users';
	var $scaffold;
	var $components =array('Auth','SwiftMailer');
	var $helpers = array('Html','Javascript');
	var $uses = array('User','MyDeck','Deck','Rating','TempUser');

	function beforeFilter() {
      
        // Call AppConroller::beforeFilter()
    	parent::beforeFilter();

		//list of actions that do not need authentication
		$this->Auth->allow('register','view','customLogin','confirmation','forgotPassword');
        
		//variable used for handling redirection	       
        $this->set('prevUrl', $this->Session->read('Auth.redirect'));
	
        
    }     
     
	//allows user to change their password
	function changePassword() {
		//declares validationError variable for view
    	$this->set('validationError','');
		$validationError = '';
		//sets the success variable to false to prompt user for email in view
		$this->set('success',false);
		
		if (!empty($this->data)) {
			//checks to make sure fields are not left empty
		    if($this->data['User']['password'] != null && $this->data['User']['new_password'] != null && $this->data['User']['new_password_confirmation'] != null) {

				$currentPassword = $this->Auth->password($this->data['User']['password']);
		    	$newPassword = $this->Auth->password($this->data['User']['new_password']);

		    	//variety of checks to validate the data
		   		if(strlen($this->data['User']['new_password']) < 6) {
		    		$validationError = 'Passwords must be at least 6 characters long';		   
		    	}
		    	else if($this->data['User']['new_password'] != $this->data['User']['new_password_confirmation']) {
		    		$validationError = 'New Passwords Do Not Match!';
		    	}

				$this->data = $this->User->read(null,$this->Auth->user('id'));

	    		if($this->data['User']['password'] != $currentPassword) {
		    		$validationError ='Current Password Incorrect!';
	    		}
		    }
		    else {
				$validationError = 'Form Not Complete!';
		    
			}
		    

			//if all the validation checks out this changes the password
		    if($validationError == '') {			
				$this->data['User']['password'] = $newPassword;
		    	$this->User->save($this->data);
				$this->set('success',true);
		    }
		    else {
				$this->data['User']['password'] = "";
				$this->set('validationError',$validationError);
		    		
		    }
		    
					
		    
		 }

	}

	//user is sent to this action through a link in their email
	//the function confirms their new account creation
	function confirmation($confirmationCode = null) {
		$this->set('confirmationError','');
		$this->set('justRegistered','');
		
		//check to see if user came to page without a code
		if($confirmationCode == null) {
			$this->set('confirmationError','No Confirmation Code Provided');	      
		}
		//if user has just registered they will be taken here
		else if($confirmationCode == 'registered') {
			$this->set('justRegistered','You will receive an email with a link. \n Please follow the link to confirm  your registration.');	   
		}
		else {

			$findUserParams = array('conditions' => array('TempUser.confirmation_code' => $confirmationCode));
			$findUser = $this->TempUser->find('first', $findUserParams );

			$this->set('confirmationError',$findUser['TempUser']['email']);
	
			$existingUserParams = array('conditions'=> array('OR'=> array('User.username'=> $findUser['TempUser']['username'],'User.email'=> $findUser['TempUser']['email'])));
			$existingUser = $this->User->find('first', $existingUserParams);
			
			if($findUser == null) {
				$this->set('confirmationError','No user exists with this code');	     
			}
			else if($existingUser != null) {
				$this->set('confirmationError','There is already a user with your username or email. Please register again.');
			}
			else {
				$this->data['User']['username'] = $findUser['TempUser']['username'];
		   		$this->data['User']['email'] = $findUser['TempUser']['email'];
				$this->data['User']['password'] = $findUser['TempUser']['password'];
				$this->User->save($this->data);
				$this->set('foundUser',$findUser);
				$this->TempUser->delete($findUser['TempUser']['id'], false);
				//this is to fix a bug where the confirmation error was being magically sent
				$this->set('confirmationError','');     
			}				
				

			

		 }
	
	 
	}

	
	function customLogin($redirect = null) {
    	$modedURL = str_replace('_','/',$redirect);
		
		if(!empty($this->data)) {
		
			//tries to authenticate user assuming username given
	   		if($this->Auth->login($this->data['User'])) {
				if($modedURL == "" || $modedURL =="/") {
            		$this->redirect('/users/dashboard');
            	}
            	else {
            		$this->redirect($modedURL);
            	}

        	}
			
			//if authentication fails assume user tried to use email address for login
			//this finds user associated with email
			$findUserParams = array('conditions' => array('User.email' => $this->data['User']['username']));
			$findUser = $this->User->find('first', $findUserParams );

			//if email is found try to authenticate user
			if ($findUser != null) {
				$this->data['User']['username'] = $findUser['User']['username'];
				$this->data['User']['email'] = $findUser['User']['email'];
				if($this->Auth->login($this->data['User'])) {
		    		if($modedURL == ""|| $modedURL == "/") {
                		$this->redirect('/users/dashboard');
            		}
            		else {
						$this->redirect($modedURL);
             		}
		   		}

			}
			
			$this->Session->setFlash("Username and/or Passowrd incorrect");		
			$this->redirect('/users/login');
		
			
		
	
		}
	
	}

     
    //generates the user dashboard  
	function dashboard($sortBy = null) {
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
		if($sortBy == 'bycount') {
			$allMyDecksParams = array('conditions' => array('MyDeck.user_id' => $currentUserId),'order'=>array('MyDeck.study_count DESC'));
			$allMyDecks = $this->MyDeck->find ('all',$allMyDecksParams);
		}
		else {
			$allMyDecksParams = array('conditions' => array('MyDeck.user_id' => $currentUserId),'order'=>array('MyDeck.modified DESC'));
			$allMyDecks = $this->MyDeck->find ('all',$allMyDecksParams);
		}
		//traverses each of mydecks
		foreach ($allMyDecks as $myDeck) {
			//pulls the full deck info for this mydeck
			$tempDeckParams = array('conditions' => array('Deck.id' => $myDeck['MyDeck']['deck_id']));
			$tempDeck = $this->Deck->find('first',$tempDeckParams);
			 
			$totalCards = count($tempDeck['Card']);
			$easyR = 0;
			$mediumR = 0;
			$hardR = 0;
			//finds the amount of easy medium and hard cards for each deck
			for ($cardX = 0; $cardX < $totalCards ; $cardX++) {
				$tempRatingParams = array('conditions' => array ('Rating.card_id' =>$tempDeck['Card'][$cardX]['id'],'Rating.user_id' => $currentUserId));
				$tempRating = $this->Rating->find('first',$tempRatingParams);
				if($tempRating != NULL ) {
			    	$numRate = $tempRating['Rating']['rating'];
			     	if ($numRate == 1) {
			     		$easyR ++;
			     	}
			     	elseif ($numRate == 2) {
			     		$mediumR ++;
			     	}
			     	elseif ($numRate == 3) {
			     		$hardR ++;
			     	}
			    }
			 }

			 $unclassifiedR =$totalCards - $easyR - $mediumR - $hardR;
			 
			 
			 //hold study count of deck
			 $tempStudyCount = $myDeck['MyDeck']['study_count'];
			 //Find favorite decks
			 if($tempStudyCount != 0) {
			 	if($favDeck1 == null) {
			 		$favDeck1 = array_merge($tempDeck, array('StudyCount' => $tempStudyCount));      
			 	}
			 	else if ($tempStudyCount >= $favDeck1['StudyCount']) {
					$favDeck2 = $favDeck1;
					$favDeck1 = array_merge($tempDeck, array('StudyCount' => $tempStudyCount));
			 	}
				else if ($favDeck2 == null || $tempStudyCount >= $favDeck2['StudyCount'] ) {
			 		$favDeck2 = array_merge($tempDeck, array('StudyCount' => $tempStudyCount));	
			 	}
			 }
			 //logic that determines whether to show last study time		 
			 if ($myDeck['MyDeck']['study_count'] == 0) {
			    $tempStudyCountArray = array($tempStudyCount,"");			 				     
			 }
			 else {
				$tempStudyCountArray = array($tempStudyCount,$myDeck['MyDeck']['modified']);
			 }

			 $deck = array_merge($tempDeck, $tempStudyCountArray,array("All"=> $totalCards,"Easy"=>$easyR,"Medium" => $mediumR, "Hard"=>$hardR, "Unclassified" => $unclassifiedR));

			 //splits decks between user decks and private decks
		 	 if($deck['Deck']['user_id'] == $currentUserId)	{ 
			 	//splits the decks into studied and unstudied decks
			 	if ($deck['0'] != '0') {    
					array_push($userCreatedDecks, $deck);
				}
				else {
					array_push($userCreatedDecksNoStudy, $deck);  
				}		
								
			 }
			 else {
			 	if ($deck['0'] != '0') {    
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


	function forgotPassword() {
		//declares validationError variable for view
      	$this->set('validationError','');

		//sets the success variable to false to prompt user for email in view
		$this->set('success',false);

		if (!empty($this->data)) {
			$exsitingUserParams = array('conditions' => array('User.email' => $this->data['User']['email']));
			$existingUser = $this->User->find('first',$existingUserParams);
			if($existingUser == null) {
				$this->set('validationError','There is no user associated with that email');		 

			}
			else {
				//generates a new password
			    $tempPassword  =  substr(md5(rand()),0,8);
						      	
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
				    else {

						//if email was sent succefully reset password
						$this->data = $this->User->read(null,$existingUser['User']['id']);
						//encrypts the password
						$this->data['User']['password'] = $this->Auth->password($tempPassword);
						$this->User->save($this->data);
						//gives use the success message in view
						$this->set('success',true);
					
					
					
				    } 
        		}
        		catch(Exception $e) {
              		//$this->log("Failed to send email: ".$e->getMessage());
					$this->Session->setFlash("Failed to send email: ".$e->getMessage());	
	      		
				}
        	
			}
		     
		}

	}

	function login(){
      	  // Intentionally blank
      
    }

	function logout() {
    $this->Auth->logout();
    $this->Session->destroy();
    $this->redirect("/");
  }
	
	function register() {
    	//declares validationError variable for view
      	$this->set('validationError','');
      	       
      	if (!empty($this->data)) {
	    	$this->TempUser->set($this->data);
	       	if ($this->TempUser->validates()) {
	       		//checks to see if the password and password confirmation match		
	            if ($this->data['TempUser']['password'] == $this->data['TempUser']['password_confirm']) {  
			      
			    	//checks to see if username is in use
					$existingUserParams = array('conditions' => array('User.username' => $this->data['TempUser']['username']));			      
			      	$existingUser = $this->User->find('first',$existingUserParams);

			      	if ($existingUser != null) {
			      		$this->set('validationError','Username is already in use!');   
			      	}
			      	else {
						//checks to see if email is in use
						$existingUserParams = array('conditions' => array('User.email' => $this->data['TempUser']['email']));
			      		$existingUser = $this->User->find('first',$existingUserParams);
			      		if ($existingUser != null) {
			      			$this->set('validationError','Email address is already in use!');
					       
			        	}
						else {
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
               		    	//$this->SwiftMailer->fromName = 'Welcome To StudyDeck!';
               		    	$this->SwiftMailer->to = $this->data['TempUser']['email'];
               		    	//set variables to template as usual
               		    	$this->set('confirmationLink', 'http://192.168.1.101/studydeck/users/confirmation/'.$confirmationCode);
        					$this->set('userName',$this->data['TempUser']['username']);
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
				else {
					$this->set('validationError','Passwords do not match!');
				}
			}

		}

	}

	//function for debuging probably should be removed eventually
	function view() {
		$this->set('userView', $this->User->find('first', array('conditions' => array('username' => $this->Auth->user('username')))));
	}


}
?>
