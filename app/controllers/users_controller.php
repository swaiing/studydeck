<?php 
include 'sd_global.php';

class UsersController extends AppController {
	var $name = 'Users';
	var $scaffold;
	var $components =array('Auth','SwiftMailer');
	var $helpers = array('Html','Javascript');
	var $uses = array('User','MyDeck','Deck','Rating','TempUser','Card');

	function beforeFilter() {
      
        // Call AppConroller::beforeFilter()
    	parent::beforeFilter();

		//list of actions that do not need authentication
		$this->Auth->allow('register','view','customLogin','confirmation','forgotPassword');
        
		//variable used for handling redirection	       
        $this->set('prevUrl', $this->Session->read('Auth.redirect'));
	
    } 

    function account() {
        //pulls the current user id
        $currentUserId = $this->Auth->user('id');
        $this->User->recursive = -1;
        $userParams = array('conditions' => array('User.id' => $currentUserId), 'fields' => array('User.username','User.email','User.password'));
        $user = $this->User->find('first', $userParams);
        $this->set('user', $user);
        
        
        if (!empty($this->data)) {
            $this->data['User']['id'] = $currentUserId;
            $this->User->save($this->data);
            
            
            /*
            if($this->Auth->password($this->data['User']['password']) == $user['User']['password']) {
            
            
            }
    
    
            if(isset($this->data['User']['email']) || isset($this->data['User']['email_confirmation'])) {
            
            
            }
            if(isset($this->data['User']['new_password']) || isset($this->data['User']['new_password_confirmation'])) {
            
            
            }
            */
    
    
    
    
    
    
        }
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
    
    //helper function to handle repeat authentication code
	private function customAuth($user = null, $redirectUrl = null) {
        //attempts to authenticate the user passed in to the function
        if($this->Auth->login($user)) {
            
            //will direct any links to a study or learn to the deck info page of that deck
            $redirectUrl = str_replace('learn','info',$redirectUrl);
            $redirectUrl = str_replace('quiz','info',$redirectUrl);
            
            //if going to an info or create page continue through otherwise direct to the dashboard
            if(strpos($redirectUrl, "info") || strpos($redirectUrl, "create")) {
                $this->redirect($redirectUrl);
            }
            else {
                $this->redirect('/users/dashboard');
            }
            
            return true;

        }
        else {
            return false;
        }
    }
    
	function customLogin($redirect = null) {
    	$modedUrl = str_replace('_','/',$redirect);
		
		if(!empty($this->data)) {
		
			//tries to authenticate user assuming username given
	   		if (!$this->customAuth($this->data['User'], $modedUrl)) {
			
                //if authentication fails assume user tried to use email address for login
                //this finds user associated with email
                $findUserParams = array('conditions' => array('User.email' => $this->data['User']['username']));
                $findUser = $this->User->find('first', $findUserParams);

                //if email is found try to authenticate user
                if ($findUser != null) {               
                    $this->data['User']['username'] = $findUser['User']['username'];
                    $this->data['User']['email'] = $findUser['User']['email'];
                    $this->customAuth($this->data['User'],$modedUrl);         
                }
            }
			
			$this->Session->setFlash("The username and/or password you have entered is incorrect");		
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

		$this->Deck->recursive= -1;
        $this->Card->recursive= -1;
        $this->Rating->recursive= -1;
        

		//pulls the current user id
		$currentUserId = $this->Auth->user('id');

		//pulls all decks the user has in the mydecks table
        $colSortBy = 'MyDeck.modified DESC';
		if($sortBy == 'bycount') {
            $colSortBy = 'MyDeck.quiz_count DESC';
        }
        
		$allMyDecksParams = array('conditions' => array('MyDeck.user_id' => $currentUserId),'order'=> $colSortBy);
		$allMyDecks = $this->MyDeck->find ('all',$allMyDecksParams);
		
		//traverses each of mydecks
		foreach ($allMyDecks as $myDeck) {
            $deckId = $myDeck['MyDeck']['deck_id'];
			//pulls the full deck info for this mydeck
			$tempDeckParams = array('conditions' => array('Deck.id' => $deckId));
			$tempDeck = $this->Deck->find('first',$tempDeckParams);
			
            //pulls all the cards for the deck
            $tempCardsParams = array('conditions' => array('Card.deck_id' => $deckId));
            $tempCards = $this->Card->find('list', $tempCardsParams);
            
            
            //gets all the ratings for all the cards for that user
            $tempRatingConditions = array('Rating.card_id' => $tempCards,'Rating.user_id' => $currentUserId);
            $tempRatingsParams = array('conditions' => $tempRatingConditions, 'fields' => array('Rating.rating'));
            $tempRatings = $this->Rating->find('all', $tempRatingsParams);
            
                  
			$totalCards = count($tempCards);
            $easyR = 0;
			$mediumR = 0;
			$hardR = 0;
			//finds the amount of easy medium and hard cards for each deck
			foreach($tempRatings as $rating) {
			
				if($rating != NULL ) {
			    	$numRate = $rating['Rating']['rating'];
			     	if ($numRate == SD_Global::$EASY_CARD) {
			     		$easyR ++;
			     	}
			     	elseif ($numRate == SD_Global::$MEDIUM_CARD) {
			     		$mediumR ++;
			     	}
			     	elseif ($numRate == SD_Global::$HARD_CARD) {
			     		$hardR ++;
			     	}
			    }
                
			}
            $hardR = $hardR + ($totalCards - ($easyR + $mediumR + $hardR)); 

									 
			//logic that determines whether to show last study time		 
			if ($myDeck['MyDeck']['quiz_count'] == 0) {
                $myDeck['MyDeck']['modified'] ="";			  
			}
             
			$deck = array_merge($tempDeck, $myDeck, array("All"=> $totalCards,"Easy"=>$easyR,"Medium" => $mediumR, "Hard"=>$hardR));

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
		 
		 
	}


	function forgotPassword() {
		//declares validationError variable for view
      	$this->set('validationError','');

		//sets the success variable to false to prompt user for email in view
		$this->set('success',false);

		if (!empty($this->data)) {
			$existingUserParams = array('conditions' => array('User.email' => $this->data['User']['email']));
			$existingUser = $this->User->find('first',$existingUserParams);
			if($existingUser == null) {
				$this->set('validationError','There is no user associated with that email');		 

			}
			else {
				//generates a new password
			    $tempPassword  =  substr(md5(rand()),0,8);
						      	
				//email tempPassword
			    $this->setEmailAttributes($existingUser['User']['email'],'/users/login');
                
               	//set variables to template 
               	$this->set('tempPassword', $tempPassword);
                
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
	
    //sets base email attributes and builds link
    private function setEmailAttributes($to = null, $urlSuffix = null) {
        $this->SwiftMailer->smtpType = 'tls';
        $this->SwiftMailer->smtpHost = 'smtp.gmail.com';
        $this->SwiftMailer->smtpPort = 465;
        $this->SwiftMailer->smtpUsername = 'noreply@studydeck.com';
        $this->SwiftMailer->smtpPassword = 'GoGate7';
        $this->SwiftMailer->sendAs = 'html';
        $this->SwiftMailer->from = 'noreply@studydeck.com';
        $this->SwiftMailer->to = $to;
               
        $baseUrl = FULL_BASE_URL;
        $prodUrlTestStr = "://studydeck";
        if(substr_compare($baseUrl, $prodUrlTestStr, -strlen($prodUrlTestStr), strlen($prodUrlTestStr)) != 0) {
            $baseUrl = $baseUrl."/studydeck";
        }
        $loginLink = $baseUrl.$urlSuffix;
		$this->set('loginLink', $loginLink);
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

							
			       			$this->setEmailAttributes($this->data['TempUser']['email'],'/users/confirmation/'.$confirmationCode);
                            
               		    	//set variables to template as usual
        					$this->set('userName',$this->data['TempUser']['username']);
                            
                            //email confirmationCode
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
