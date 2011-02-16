<?php 
include 'sd_global.php';
require_once "constants.php";
require_once "paypal/EWPServices.php";


class UsersController extends AppController {
	var $name = 'Users';
	var $components =array('Auth','SwiftMailer','Recaptcha');
	var $helpers = array('Html','Javascript','RelativeTime');
	var $uses = array('User','MyDeck','Deck','Rating','TempUser','Card','Product','Payment','PurchasedProduct');


	function beforeFilter() {
      
        // Call AppConroller::beforeFilter()
    	parent::beforeFilter();
		 $this->Auth->fields = array(
		 'username' => 'email',
		 'password' => 'password');
		//list of actions that do not need authentication
		$this->Auth->allow('register','view','customLogin','confirmation','forgotPassword','paypalIpn','registerSubmit');
        
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

        // Set page title
        $this->pageTitle = SD_GLOBAL::$PAGE_TITLE_SETTINGS;

            
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
			//$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
			//tries to authenticate user assuming username given
	   		if (!$this->customAuth($this->data['User'], $modedUrl)) {
			
                //if authentication fails assume user tried to use email address for login
                //this finds user associated with email
                $findUserParams = array('conditions' => array('User.email' => $this->data['User']['email']));
                $findUser = $this->User->find('first', $findUserParams);

                //if email is found try to authenticate user
                if ($findUser != null) {               
                    //$this->data['User']['username'] = $findUser['User']['username'];
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

        // Set page title
        $this->pageTitle = SD_GLOBAL::$PAGE_TITLE_DASHBOARD;
		 
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

        // Set page title
        $this->pageTitle = SD_GLOBAL::$PAGE_TITLE_DEFAULT;
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

    // Action for view and order confirmation
    function register()
    {

        // Logging
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Set page title
        $this->pageTitle = SD_GLOBAL::$PAGE_TITLE_REGISTER;

    	// Declares recaptchaFail variable for view
      	$this->set('recaptchaFailed', false);
        
        // Sent to view with no data
        if (empty($this->data)) {
            $this->log($LOG_PREFIX . "No product form data passed to register page!");
            $this->redirect(array('controller'=>'products', 'action' => 'view'));
        }
        else {

            // Debug
			//print_r($this->data);

            $formSubmittedData = $this->data['User'];

            // Holds data
            $productIdsSelected = array();
            $sentFromView = false;
            $postPaypal = false;

            // Iterate purchased deck IDs
            foreach ($formSubmittedData as $key => $value) {

                // Key is product ID
                if (is_int($key)) {
                    $this->log($LOG_PREFIX . "Key is " . $key . ". Value is " . $value);
                    if ($value) {
                        $productIdsSelected[$key] = true;
                    }
                }
                // Key flag indicating forwarded from view
                else if (strcmp($key,"sentFromView") == 0) {
                    $sentFromView = true;
                }
                else if (strcmp($key,"postPaypal") == 0) {
                    $postPaypal = true;
                }
            } // end foreach

            // Query products
            $this->Product->recursive = -1;
            $allProducts = $this->Product->find('all');
            $productsOrdered = array();
            foreach ($allProducts as $product) {
                $id = $product['Product']['id'];
                if (array_key_exists($id, $productIdsSelected) && $productIdsSelected[$id]) {
                    array_push($productsOrdered, $product);
                }
            }

            // productsOrdered output
            // Array (
            // [0] => Array ( [Product] => Array ( [id] => 1 [deck_id] => 5 [name] => Studydeck Top 500 [price] => 10 ) )
            // [1] => Array ( [Product] => Array ( [id] => 2 [deck_id] => 6 [name] => Studydeck Latin Roots [price] => 5 )
            // ) ) 


           $this->set('productsOrdered', $productsOrdered); 

           // Post directly to Paypal
           if ($postPaypal) {
                //if($this->Auth->login($this->data)) {
                    // directs them to a page where alerting them that the email has been sent
                    $this->Session->write('products', $productsOrdered);
                    $this->redirect(array('action' => 'paypalSubmit'));
                //}
           }
           
           // Skip rest of method because it was originally forwared from products/view
           if ($sentFromView) {
                return;
           }
		   
           // Function continues because we are already on the register page

           // Validate user registration fields
		   $this->User->set($this->data);
	       	if ($this->User->validates()) {

				//print_r($this->params['form']); 
                if($this->Recaptcha->valid($this->params['form'])) {
					$this->User->create();

                    //creates the user in the temp user table
                    //skips validation because it should already be done
                    $new_user = $this->User->save($this->data,false);
					
                    if($this->Auth->login($this->data)) {
						//directs them to a page where alerting them that the email has been sent
						$this->Session->write('products', $productsOrdered);
						$this->redirect(array('action' => 'paypalSubmit'));
						
					}
                }
                else {
                    $this->set('recaptchaFailed', true);
                    unset($this->data['User']['password']);
                    unset($this->data['User']['password_confirm']);
                }

            }
            else {
                unset($this->data['User']['password']);
                unset($this->data['User']['password_confirm']);
            
            }

        } // end else

    }

	function paypalSubmit()
    {
        // Logging
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Set user id
        $userId = $this->Auth->user('id');

        // Obtained from ini file, which are different in DEV and PRD
        $ppIniArr = parse_ini_file("paypal.ini", true);

        // Log fatal error
        if ($ppIniArr == null || count($ppIniArr) == 0) {
            $this->log($LOG_PREFIX . "Failure parsing paypal.ini file for PayPal submission!", LOG_ERROR);
            $this->set('error', true);
            return;
        }

        // Read PRD or DEV values based on debug level
        $configVals = array();
        if (Configure::read('debug') == SD_GLOBAL::$SD_PRODUCTION_DEBUG) {
            $configVals = $ppIniArr[SD_GLOBAL::$PAYPAL_SUBMIT_INI_PRD];
        }
        else {
            $configVals = $ppIniArr[SD_GLOBAL::$PAYPAL_SUBMIT_INI_DEV];
        }

        // Read ini config values from array
        $ppBusiness = $configVals['business'];
        $ppCertId = $configVals['cert_id'];
        $ppEnvUrl = $configVals['env_url'];
        $ppPubCert = $configVals['pub_cert'];

        // Sandbox DEV vals
        /*
        $ppBusiness = "seller_1292086026_biz@studydeck.com";
        $ppCertId = "P3AUVEYDF6AQU";
        $ppEnvUrl = "https://www.sandbox.paypal.com";
        $ppPubCert = "certs/sandbox_cert.pem";
        */

        if ( !($ppBusiness && $ppCertId && $ppEnvUrl && $ppPubCert) ) {
            $this->log($LOG_PREFIX . "Failure parsing Paypal values from ini file.  Missing value!", LOG_ERROR);
            $this->set('error', true);
            return;
        }

		// Paypal	
		$buttonParams = array(
                        "cmd"			=> "_cart",
						"business" 		=> $ppBusiness,
						"cert_id"		=> $ppCertId,
						"charset"		=> "UTF-8",
						"upload"		=> '1',
						"currency_code"	=> 'USD',
						"return"		=> FULL_BASE_URL . '/users/payment_confirmation',
						"cancel_return"	=> FULL_BASE_URL,
						"notify_url"	=> FULL_BASE_URL . '/users/paypalIpn/'.$userId.'/oob3b0VKEyLY');
	
		$products = $this->Session->read('products');
	
		$count = 1;
		foreach ($products as $product)
        {
            $buttonParams = array_merge($buttonParams,
                                        array("item_name_".$count => $product['Product']['name'], 
                                            "item_number_".$count => $product['Product']['id'],
                                            "quantity_".$count	=> '1',
                                            "amount_".$count => $product['Product']['price']));
            $count++;
         }

		$buttonReturn = EWPServices::encryptButton(	$buttonParams,
											'certs/studydeck_pubcert.pem',
											'certs/studydeck_prvkey.pem',
											DEFAULT_EWP_PRIVATE_KEY_PWD,
											$ppPubCert,
											$ppEnvUrl,
											'');

		$button = $buttonReturn["encryptedButton"];
		$this->set('button', $button);
	}
	
	function paypalIpn() {
		$user_id = $this->params['pass'][0];
		
		if ($this->params['pass'][1] == 'oob3b0VKEyLY') {
			$paypal_params = $this->params['form'];
			$items_in_cart = $paypal_params['num_cart_items'];
			
			
			$this->Payment->set(array(
				'user_id' => $user_id,
				'amount' => $paypal_params['payment_gross'],
				'transaction_id' => $paypal_params['txn_id']
			));
			$this->Payment->save();
			for($x = 1; $x <= $items_in_cart; $x++) {
				$product = $this->Product->find('first', array('conditions' => array('Product.id' => $paypal_params['item_number'.$x])));

				if(number_format($product['Product']['price'], 2, '.', '') ==  $paypal_params['mc_gross_'.$x]){
					$this->PurchasedProduct->create();
					$this->PurchasedProduct->set(array(
						'user_id' => $user_id,
						'product_id' => $product['Product']['id'],
						'payment_id' => $this->Payment->id
					));
					$this->PurchasedProduct->save();
					$this->MyDeck->create();
					$this->MyDeck->set(array(
						'user_id' => $user_id,
						'deck_id' => $product['Product']['deck_id'],
						'type' => SD_Global::$USER_SAVED
					));
					$this->MyDeck->save();					
					
				}
				
			}
		}
		
	}
	
	function payment_confirmation() {
	
	}
	function not_authorized() {

	}


	//function for debuging probably should be removed eventually
	function view() {
		$this->set('userView', $this->User->find('first', array('conditions' => array('username' => $this->Auth->user('username')))));
	}


}
?>
