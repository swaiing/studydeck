<?php 
include 'sd_global.php';
require_once "constants.php";
require_once "paypal/EWPServices.php";


class UsersController extends AppController {
	var $name = 'Users';
	var $scaffold;
	var $components =array('Auth','SwiftMailer','Recaptcha');
	var $helpers = array('Html','Javascript','RelativeTime');
	var $uses = array('User','MyDeck','Deck','Rating','TempUser','Card');

	function beforeFilter() {
      
        // Call AppConroller::beforeFilter()
    	parent::beforeFilter();

		//list of actions that do not need authentication
		$this->Auth->allow('register','view','customLogin','confirmation','forgotPassword');
        
		//variable used for handling redirection	       
        $this->set('prevUrl', $this->Session->read('Auth.redirect'));
        
        
        //sets keys for recaptcha
        $domain =  substr(strrchr(FULL_BASE_URL, '/'),1);      
        if (($handle = fopen("files/recaptchakeys.csv","r")) !== FALSE) {
            while($fileContents = fgetcsv($handle)){
                if($fileContents[0] == $domain) {
                    $this->Recaptcha->publickey = $fileContents[1];
                    $this->Recaptcha->privatekey = $fileContents[2];
                }
            }
        }
        fclose($handle);
        
    } 

    function account() {
        //pulls the current user id
        $currentUserId = $this->Auth->user('id');
        $this->User->recursive = -1;
            
        if (!empty($this->data)) {
            //sets user id to update
            $this->data['User']['id'] = $currentUserId;
            
            $fieldsToSave = array('auth_password');
            
            //checks to see if user is trying to update email
            if($this->data['User']['email'] != null || $this->data['User']['email_confirmation'] != null) {
         
                array_push($fieldsToSave, 'email');
            
            }
            //checks to see if user is trying to update password
            if($this->data['User']['password'] != null || $this->data['User']['password_confirmation'] != null) {
                array_push($fieldsToSave, 'password');      
                
            }
            
            $this->User->set($this->data);
            //needs to validate first to handle password length checking
            if($this->User->validates(array('fieldList' => $fieldsToSave))) {
                //if saving new password hash the password
                if(in_array('password', $fieldsToSave)) {
                    $this->data['User']['password'] = $this->Auth->password($this->data['User']['password']); 
                }
                
                $this->User->save($this->data, false, $fieldsToSave);
                $this->data = null;
            
            }
            else {
                //on save fail remove all passwords for security
                unset($this->data['User']['password']);
                unset($this->data['User']['password_confirmation']);
                unset($this->data['User']['auth_password']);
            }
            
        }        
        //get user information
        $userParams = array('conditions' => array('User.id' => $currentUserId), 'fields' => array('User.username','User.email','User.password'));
        $user = $this->User->find('first', $userParams);
        $this->set('user', $user);
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
		$currentUserName = $this->Auth->user('username');

		//pulls all decks the user has in the mydecks table
        $colSortBy = 'MyDeck.modified DESC';
		if($sortBy == 'bycount') {
            $colSortBy = array('MyDeck.quiz_count DESC', 'MyDeck.modified DESC');
        }
        
		$quizDecksParams = array('conditions' => array('MyDeck.user_id' => $currentUserId, 'MyDeck.quiz_count >' => 0),'order'=> $colSortBy);
		$quizMyDecks = $this->MyDeck->find ('all',$quizDecksParams);
        
        $noQuizDecksParams = array('conditions' => array('MyDeck.user_id' => $currentUserId, 'MyDeck.quiz_count' => 0),'order'=> 'MyDeck.modified DESC');
        $noQuizDecks = $this->MyDeck->find ('all',$noQuizDecksParams);
        
		$allMyDecks = array_merge($quizMyDecks,$noQuizDecks);
        
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
            
            App::import('Sanitize');
            $sanitizeParams = array('connection'=>'default',
                    'odd_spaces'=>'true',
                    'encode'=>true,
                    'dollar'=>true,
                    'carriage'=>true,
                    'unicode'=>true,
                    'escape'=>true,
                    'backslash'=>true
            );

            $deck = Sanitize::clean($deck, $sanitizeParams);
            
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
		 
        // Set page title
        $this->pageTitle = SD_GLOBAL::$PAGE_TITLE_DASHBOARD . $currentUserName . "'s dashboard";
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
        $this->SwiftMailer->smtpHost = 'mail.studydeck.com';
        $this->SwiftMailer->smtpPort = 465;
        $this->SwiftMailer->smtpUsername = 'donotreply@studydeck.com';
        $this->SwiftMailer->smtpPassword = 'GoGate7';
        $this->SwiftMailer->sendAs = 'html';
        $this->SwiftMailer->from = 'noreply@studydeck.com';
        $this->SwiftMailer->to = $to;
               
        $baseUrl = FULL_BASE_URL;
        $prodUrlTestStr = "://studydeck";
        if(substr_compare($baseUrl, $prodUrlTestStr, -strlen($prodUrlTestStr), strlen($prodUrlTestStr)) != 0) {
            //$baseUrl = $baseUrl."/studydeck";
            $baseUrl = $baseUrl;
        }
        $loginLink = $baseUrl.$urlSuffix;
		$this->set('loginLink', $loginLink);
    }
    
	function register() {
    	//declares recaptchaFail variable for view
      	$this->set('recaptchaFailed',false);
      	 
      	if (!empty($this->data)) {
	    	$this->User->set($this->data);
	       	if ($this->User->validates()) {
                if($this->Recaptcha->valid($this->params['form'])){
					$this->User->create();
                    //encrypts the password
					$pre_encrypt = $this->data['User']['password'];
                    $this->data['User']['password'] = $this->Auth->password($pre_encrypt);
                
                    //creates the user in the temp user table
                    //skips validation because it should already be done
                    $new_user = $this->User->save($this->data,false);
					debug($new_user, $showHTML = false, $showFrom = true);
                    if($this->Auth->login($this->data)) {
						//directs them to a page where alerting them that the email has been sent
						$this->redirect(array('action' => '/paypalSubmit'));
					}
                }
                else {
                    $this->set('recaptchaFailed',true);
                    unset($this->data['User']['password']);
                    unset($this->data['User']['password_confirm']);
                }

                
            }
            else {
                unset($this->data['User']['password']);
                unset($this->data['User']['password_confirm']);
            
            }
            
   		}

	}
	
	function paypalSubmit() {
				//paypal
		$buttonParams = array(	"cmd"			=> "_xclick",
						"business" 		=> 'seller_1292086026_biz@studydeck.com',
						"cert_id"		=> 'P3AUVEYDF6AQU',
						"charset"		=> "UTF-8",
						"item_name"		=> 'latin roots',
						"item_number"	=> '1',
						"amount"		=> '4',
						"currency_code"	=> 'USD',
						"return"		=> 'http://www.studydeck.com/dashboard',
						"cancel_return"	=> 'http://www.studydeck.com',
						"notify_url"	=> 'http://www.studydeck.com/purchase/uid',
						"custom"		=> "PayPal EWP Sample");

		$envURL = "https://www.sandbox.paypal.com";

		$buttonReturn = EWPServices::encryptButton(	$buttonParams,
											'certs/studydeck_pubcert.pem',
											'certs/studydeck_prvkey.pem',
											DEFAULT_EWP_PRIVATE_KEY_PWD,
											'certs/sandbox_cert.pem',
											$envURL,
											'');



		$button = $buttonReturn["encryptedButton"];
		$this->set('button', $button);
	}


	//function for debuging probably should be removed eventually
	function view() {
		$this->set('userView', $this->User->find('first', array('conditions' => array('username' => $this->Auth->user('username')))));
	}


}
?>
