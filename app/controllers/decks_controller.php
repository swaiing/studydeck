<?php

include 'sd_global.php';

class DecksController extends AppController {

	var $name = 'Decks';
    var $scaffold;
    var $uses = array('Deck','Card','Tag','MyDeck','DeckTag','Rating','Result');
    var $helpers = array('Html','Javascript','Form');
    var $components = array('Auth','RequestHandler');


    function beforeFilter(){

    	// Call AppConroller::beforeFilter()
        parent::beforeFilter();
      
        $this->Auth->allow('explore');

    } 

	function create(){
        $this->pageTitle = 'Create a Deck';
        //$this->layout='create_edit';
        //creates tag dropdown
        // $tag_array = $this->Tag->find('list',array('fields'=>'Tag.tag'));
        // array_push($tag_array, "---Select Category---");
        //sort($tag_array);
        //$this->set('tagdata',$tag_array);

        if(!empty($this->data)){
			//sanitize the input data
			App::import('Sanitize');
			$this->data = Sanitize::clean($this->data);	
			
			//gets authenticated user Id
			$authUserId = $this->Auth->user('id');
			
			
            // add user id into deck
            $this->data['Deck']['user_id']= $authUserId; 

            
			//finds the number of cards being entered
            $num = count($this->data['Card']);

            //traverses the cards being entered and sets their deck id to the new deck id
            for($x = 0; $x < $num; $x ++){
				//remove empty cards from creating
				if($this->data['Card'][$x]['question'] == '' && $this->data['Card'][$x]['answer'] == '') {
					unset($this->data['Card'][$x]);
				}
				
			}
            
            //$this->log("[" . get_class($this) . "-> create] " . $debugMsg, LOG_DEBUG);
            
            
			if($this->Deck->saveAll($this->data,array('validate' => 'only'))) {
                
                
                $this->Deck->saveAll($this->data,array('validate' => 'false'));
                
                $deckId = $this->Deck->id;
                $tagList = $this->data['Tag']['tag'];
                //$debugMsg ="";
                $newTagArray = array();
                $existingTagArray = array();
                
                
                $tagListArray = explode(",", $tagList);
                $tagListArrayLength = count($tagListArray);
                   
                for( $tagIndex = 0; $tagIndex < $tagListArrayLength; $tagIndex ++) {            
                    //$debugMsg = $debugMsg." ".trim($tagListArray[$tagIndex]); 
                   
                    $tag = trim($tagListArray[$tagIndex]);
                    $tempTag = $this->Tag->find('first',array('conditions' => array('Tag.tag' => $tag)),array('fields' => 'Tag.id'));
                    if($tag != "") {
                        if($tempTag == NULL) { 
                            $newTagArray['Tag']['tag'] = $tag;
                            $this->Tag->save($newTagArray, array('validate' => 'false'));
                            $existingTagArray['DeckTag'][$tagIndex]['tag_id'] = $this->Tag->id; 
                            //$debugMsg = $debugMsg." new tag: ".$tag." id: ".$this->Tag->id;
                        }
                        else {                
                            $existingTagArray['DeckTag'][$tagIndex]['tag_id'] = $tempTag['Tag']['id']; 
                            //$debugMsg = $debugMsg." existing tag: ".$tempTag['Tag']['id']; 
                        }                                                          
                        $existingTagArray['DeckTag'][$tagIndex]['deck_id'] = $deckId;                             
                    }
                }
                                    
                if($existingTagArray != null) {
                    $debugMsg = $debugMsg." reached here"; 
                    print_r($existingTagArray);
                    $this->DeckTag->saveAll($existingTagArray['DeckTag']);
                    
                }
                                    
                    

                $this->data['MyDeck']['deck_id'] = $deckId;
                $this->data['MyDeck']['user_id'] = $this->data['Deck']['user_id'];
                $this->MyDeck->save($this->data); 
                
                        
			}
			
					
			
        } // end if(!empty($this->data))

    }



    //deletes a deck and all related cards
	function delete() {
		//disable need for a view		
     	$this->autoRender=false;
		//checks to see if this is ajax request
      	if ($this->RequestHandler->isAjax()) {
	    	//finds the deck whose id is passed in by ajax parameter
			$deckToRemoveParams =  array('conditions' =>  array('Deck.id' => $this->params['form']['id']));
      	   	$deckToRemove = $this->Deck->find('first',$deckToRemoveParams);
	       	//confirms that the login user is the owner of hte deck	     
			if($deckToRemove['Deck']['user_id'] == $this->Auth->user('id')){
				//actually deletes the deck			    
				$this->Deck->delete($this->params['form']['id'],true);
			}
		}
	}


	//action for exploring decks
	function explore($sortBy = null,$page = null,$query = null) {   
		//sets sort variable for view   
		$this->set('sort', $sortBy);
      	//sets sql sort based on sort parameter       
      	if($sortBy == 'recent') {
	    	$sortBy = 'Deck.created DESC';
	    }
	    elseif ($sortBy == 'popular') {
	        $sortBy = 'Deck.view_count DESC';
	    }
	    elseif ($sortBy == 'alphabetical') {
	        $sortBy = 'Deck.deck_name ASC';
	    }
	    else {
	        $this->set('sort', 'recent');
		    $sortBy = 'Deck.created DESC';
	    }
	    //sets $page variable to 1 in none specified
	    if ($page == null) {
 	    	$page = 1;
	    }
	    
		$queryString = $this->data['Deck']['searchQuery'];
	    //set querystring for query equal to the query parameter
	    if ($query != null) {
	    	$queryString = $query;
	    }
	    
		//declares a null exploredecks array which holds the Decks to display on the page   
	    $exploreDecks = array();
	    //pulls first 20 decks of proper order if no query given
		if ($queryString == null) {
	    	
			$exploreDecksParams = array('conditions'=> array('Deck.privacy'=> SD_Global::$PUBLIC_DECK),'limit' => 20,'page' => $page,'order'=> $sortBy);
			$exploreDecks = $this->Deck->find('all',$exploreDecksParams);
	       	$this->set('decks',$exploreDecks);
			
			//sets the pages count, 20 results per page
			$findDeckCount = $this->Deck->find('count',array('conditions'=> array('Deck.privacy'=> SD_Global::$PUBLIC_DECK)));
	       	$this->set('pages', ceil($findDeckCount/20));
		}
		else {
			//queries the decks
			$deckQuery = $this->Deck->search($queryString);
			
			$arrayOfDeckIds = array();

			//loops through decks and saves the deck ids
			foreach ($deckQuery as $result) {
				array_push($arrayOfDeckIds, $result['Deck']['id']);
			    
			}

			$arrayOfTagIds = array();
			//queries the tags
			$tagQuery = $this->Tag->search($queryString);
			//pulls all the tag ids that are found
			foreach($tagQuery as $tagResult) {
				array_push($arrayOfTagIds, $tagResult['Tag']['id']);
			}

			//pulls all the deckTag relations for the given tag ids
			$arrayOfDeckTags = $this->DeckTag->find('all',array('conditions'=> array('DeckTag.tag_id' => $arrayOfTagIds)));
			//gets the deck ids and adds them to array
			foreach ($arrayOfDeckTags as $deckTags) {
				array_push($arrayOfDeckIds, $deckTags['DeckTag']['deck_id']);
			}
			
			//takes all the deck ids gathered pulls the decks
			$exploreDecksConditions = array('Deck.id' => $arrayOfDeckIds,'Deck.privacy' => SD_Global::$PUBLIC_DECK);
			$exploreDecksParams = array('limit' => 20,'page' => $page,'conditions'=> $exploreDecksConditions,'order'=> $sortBy);
			$exploreDecks = $this->Deck->find('all',$exploreDecksParams);
			$this->set('decks',$exploreDecks); 

			$findDeckCount = $this->Deck->find('count',array('conditions'=> array('Deck.id' => $arrayOfDeckIds,'Deck.privacy' => SD_Global::$PUBLIC_DECK)));
      		$this->set('pages', ceil($findDeckCount/20));
			
		}
	       
	    $tagArray = array();
	    //goes through all the decks and retrieves the tags
	    //adds all the tags to an array with the tag id as the index
	    foreach ($exploreDecks as $eDeck) {
	    	$tempTagArray = $eDeck['DeckTag'];
		    foreach ($tempTagArray as $tempTag) {
		    	$tempTagId = $tempTag['tag_id'];
			    if(!isset($tagArray[$tempTagId])) {
			    	$tag =$this->Tag->find('first',array('conditions'=> array('Tag.id' => $tempTagId)));
					$tagArray[$tempTagId]=$tag['Tag']['tag'];
				}

		    }
		     
		}
	       	    
	    $this->set('queryString',$queryString);
	    $this->data['Deck']['searchQuery'] = $queryString;
		$this->set('tagArray',$tagArray);
	    $this->set('page', $page);
	}
   
  /*
   * Helper which returns cards given a deck.
   *
   */
  function getCards($deckId)
  {
    if(!isset($deckId)) {
      $this->log("[" . get_class($this) . "->" . __FUNCTION__ . "] " . "deckId is null.");
      return null;
    }
    $this->Deck->recursive = -1;
    $this->Deck->Card->recursive = -1;

    // Set user id
    $userId = $this->Auth->user('id');

    // Retrieve Card model data for deck
    $cardsParams = array(
                        'conditions' => array('Card.deck_id' => $deckId),
                        'fields' => array('Card.id','Card.question','Card.answer')
    );
    $cardRecords = $this->Deck->Card->find('all',$cardsParams);
    return $cardRecords;
  }

  /*
   * Helper which returns an array of cards' IDs
   *
   */
  function getCardIds($cardRecords)
  {
    // Store card IDs in array for use ratings/results queries
    $cardIds = array();
    foreach($cardRecords as $card) {
       array_push($cardIds,$card['Card']['id']); 
    }
    return $cardIds;
  }

  /*
   * Helper which returns cards' ratings given an array of card IDs.
   * 
   */
  function getRatings($cardIds)
  {
    if(!isset($cardIds)) {
      $this->log("[" . get_class($this) . "->" . __FUNCTION__ . "] " . "cardIds is null.");
      return null;
    }
    $this->Deck->recursive = -1;
    $this->Deck->Card->Rating->recursive = -1;

    // Set user id
    $userId = $this->Auth->user('id');

    // Retrieve ratings by card_id and user_id
    $ratingParams = array(
                          'conditions' => array('Rating.user_id' => $userId,
                                                'Rating.card_id' => $cardIds),
                          'fields' => array('Rating.id','Rating.card_id','Rating.rating')
    );
    $ratingRecords = $this->Deck->Card->Rating->find('all',$ratingParams);
      
    // Index ratings into array by card ID.
    $ratingMap = array();
    foreach($ratingRecords as $rating) {
       $ratingMap[$rating['Rating']['card_id']] = array('id' => $rating['Rating']['id'],
                                                        'rating' => $rating['Rating']['rating']);
    }

    return $ratingMap;
  }

  /*
   * Helper which returns cards' results given an array of card IDs.
   *
   */
  function getResults($cardIds)
  {
    if(!isset($cardIds)) {
      $this->log("[" . get_class($this) . "->" . __FUNCTION__ . "] " . "cardIds is null.");
      return null;
    }
    $this->Deck->recursive = -1;
    $this->Deck->Card->Results->recursive = -1;

    // Set user id
    $userId = $this->Auth->user('id');

    // Retrieve results by card_id and user_id
    $resultParams = array(
                          'conditions' => array('Result.user_id' => $userId,
                                                'Result.card_id' => $cardIds),
                          'fields' => array('Result.id','Result.card_id','Result.last_guess',
                                            'Result.total_correct','Result.total_incorrect')
    );
    $resultRecords = $this->Deck->Card->Result->find('all',$resultParams);

    // Index results into array by card ID
    $resultMap = array();
    foreach($resultRecords as $result) {
       $resultMap[$result['Result']['card_id']] = array('id' => $result['Result']['id'],
                                                        'last_guess' => $result['Result']['last_guess'],
                                                        'total_correct' => $result['Result']['total_correct'],
                                                        'total_incorrect' => $result['Result']['total_incorrect']);
    }

    return $resultMap;
}

    function study($id = null)
    {
        // Set $deckRecord
        $this->Deck->id = $id;
        $deckRecord = $this->Deck->read();

        // Call helper to retrieve array of cards
        $cardRecords = $this->getCards($id);
        $cardIds = $this->getCardIds($cardRecords);

        // Call helper to retrieve ratings
        $ratingMap = $this->getRatings($cardIds);

        // Call helper to retrieve results
        $resultMap = $this->getResults($cardIds);

        // debug
        //$this->set('debug',$resultMap);
        //$this->set('debug',$ratingMap);

        // Set variables for view
        $this->set('deckId',$deckRecord['Deck']['id']);
        $this->set('deckData',$deckRecord);
        $this->set('cards',$cardRecords);
        $this->set('cardsRatings',$ratingMap);
        $this->set('cardsResults',$resultMap);
    }

    /*
     * Identical to study action.
     *
     */
    function quiz($id = null)
    {
      $this->study($id);
    }

    /*
     * Called using AJAX to update card/deck data
     * stored in session.
     *
     */
    function update()
    {
        // Set layout to blank
        $this->layout = "";

        // Set user ID
        $userId = $this->Auth->user('id');

        // Grab data from url, params and sanitize input
        App::import('Sanitize');
        $deckId = Sanitize::paranoid($this->params['url']['did']);
        $cardId = Sanitize::paranoid($this->params['url']['cid']);
        $ratingId = Sanitize::paranoid($this->params['url']['rid']);
        $resultId = Sanitize::paranoid($this->params['url']['sid']);
        $rating = Sanitize::paranoid($this->params['url']['rating']);
        $correct = Sanitize::paranoid($this->params['url']['correct']);

        // Set log info
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Validate rating
        if(!preg_match("/[0-3]/",$rating)) {
          //$this->log($LOG_PREFIX . "Rating is an invalid value: " . $rating,LOG_DEBUG);
        }
  
        // Validate result
        if(!preg_match("/[0|1]/",$correct)) {
          //$this->log($LOG_PREFIX . "Result is an invalid value: " . $correct,LOG_DEBUG);
        }

        // Debug
        //$this->log($LOG_PREFIX . "deckId: " . $deckId, LOG_DEBUG);

        /*
         * This next big routine goes through nested-session arrays which contain card rating/result data.
         *
         * Session -> User -> Deck -> Card -> Rating
         *                                 -> Result
         *
         * TODO: I'm sure there's a much better recursive way to do this that is much cleaner.
         *
         */
        // Read 'Users' session array
        $userSessions = $this->Session->read(SD_Global::$SESSION_USERS_KEY);

        // Test user exists in session
        if(($userSessions != null) && (array_key_exists($userId,$userSessions))) {
          $userData = $userSessions[$userId];

          // Test deck exists in session
          if(array_key_exists($deckId,$userData)) {
            $deckData = $userData[$deckId];

            // Test card exists in User object
            if(array_key_exists($cardId,$deckData)) {
              $cardData = $deckData[$cardId];

              // Test rating exists in User->Card object
              if(array_key_exists(SD_Global::$SESSION_RATING_KEY,$cardData)) {
                $ratingData = $cardData[SD_Global::$SESSION_RATING_KEY];

                // Add rating values to object
                $userSessions[$userId][$deckId][$cardId][SD_Global::$SESSION_RATING_KEY][SD_Global::$SESSION_ID_KEY] = $ratingId;
                $userSessions[$userId][$deckId][$cardId][SD_Global::$SESSION_RATING_KEY][SD_Global::$MODEL_RATING_RATING] = $rating;
              }
              else {
                $this->log($LOG_PREFIX . "No rating record for ratingId: " . $ratingId . " => " . $rating, LOG_DEBUG);
                $userSessions[$userId][$deckId][$cardId][SD_Global::$SESSION_RATING_KEY] = array(SD_Global::$SESSION_ID_KEY => $ratingId, SD_Global::$MODEL_RATING_RATING => $rating);
              }

              // Test result exists in User->Card object
              if(array_key_exists(SD_Global::$SESSION_RESULT_KEY,$cardData)) {
                $resultData = $cardData[SD_Global::$SESSION_RESULT_KEY];

                // Add results values to object
                $userSessions[$userId][$deckId][$cardId][SD_Global::$SESSION_RESULT_KEY][SD_Global::$SESSION_ID_KEY] = $resultId;
                $userSessions[$userId][$deckId][$cardId][SD_Global::$SESSION_RESULT_KEY][SD_Global::$MODEL_RESULT_LAST_GUESS] = $correct;
              }
              else {
                $this->log($LOG_PREFIX . "No result record for resultId: " . $resultId . " => " . $correct, LOG_DEBUG);
                $userSessions[$userId][$deckId][$cardId][SD_Global::$SESSION_RESULT_KEY] = array(SD_Global::$SESSION_ID_KEY => $resultId, SD_Global::$MODEL_RESULT_LAST_GUESS => $correct);
              }
            }
            // No card data exists, create object
            else {
              $this->log($LOG_PREFIX . "No card record for cardId: " . $cardId, LOG_DEBUG);
              $userSessions[$userId][$deckId][$cardId] = array(SD_Global::$SESSION_RATING_KEY => array(SD_Global::$SESSION_ID_KEY => $ratingId, SD_Global::$MODEL_RATING_RATING => $rating),
                                                               SD_Global::$SESSION_RESULT_KEY => array(SD_Global::$SESSION_ID_KEY => $resultId, SD_Global::$MODEL_RESULT_LAST_GUESS => $correct));
            }
          }
          // No deck data exists, create object
          else {
            $this->log($LOG_PREFIX . "No deck record for deckId: " . $deckId, LOG_DEBUG);
            $userSessions[$userId][$deckId] = array($cardId => array(SD_Global::$SESSION_RATING_KEY => array(SD_Global::$SESSION_ID_KEY => $ratingId, SD_Global::$MODEL_RATING_RATING => $rating),
                                                                     SD_Global::$SESSION_RESULT_KEY => array(SD_Global::$SESSION_ID_KEY => $resultId, SD_Global::$MODEL_RESULT_LAST_GUESS => $correct)));
          }
        }
        // No user session exists, create ojbect
        else {
          // Create new entry for user in session
          $this->log($LOG_PREFIX . "No user record for userId: " . $userId, LOG_DEBUG);
          $userSessions[$userId] = array($deckId => array($cardId => array(SD_Global::$SESSION_RATING_KEY => array(SD_Global::$SESSION_ID_KEY => $ratingId, SD_Global::$MODEL_RATING_RATING => $rating),
                                                                            SD_Global::$SESSION_RESULT_KEY => array(SD_Global::$SESSION_ID_KEY => $resultId, SD_Global::$MODEL_RESULT_LAST_GUESS => $correct))));
        }

        // Log to debug
        $this->set("debug",$userSessions);

        // Write to the session
        $this->Session->write(SD_Global::$SESSION_USERS_KEY,$userSessions);
    }

    /*
     * Helper function called by quit which writes the session data to the model.
     *
     */
    function writeSession($userId,$deckId) {

      // Setup log prefix for this function
      $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

      // Check for null IDs
      if(($userId == null) || ($deckId == null)) {
        $this->log($LOG_PREFIX . "Valid ID.  userId: " . $userId . " | deckId: " . $deckId);
        return False;
      }

      // Get session data
      $sessionData = $this->Session->read(SD_Global::$SESSION_USERS_KEY);
      if($sessionData == null) {
        $this->log($LOG_PREFIX . "No session data found.");
        return False;
      }

      // Get Deck object in session 
      $deckSessionData = $sessionData[$userId][$deckId];
      if($deckSessionData == null) {
        $this->log($LOG_PREFIX . "No deck session data found.");
        return False;
      }
      
      // Save Card Rating/Result models
      foreach($deckSessionData as $cardId => $card) {

        // Save rating if valid
        $rating = $card[SD_Global::$SESSION_RATING_KEY][SD_Global::$MODEL_RATING_RATING];
        if(!preg_match("/[0-3]/",$rating)) {
          $this->log($LOG_PREFIX . "Rating: (" . $rating . ") not valid for cardId: (" . $cardId . ").");
        }
        else {

          // Set record to update or create new record
          $ratingId = $card[SD_Global::$SESSION_RATING_KEY][SD_Global::$SESSION_ID_KEY];
          if(strcmp($ratingId,SD_Global::$NULL_STR) != 0) {
            $this->log($LOG_PREFIX . "Setting Rating model key to " . $ratingId,LOG_DEBUG); 
            $this->Deck->Card->Rating->id = $ratingId;
          }
          else {
            $this->log($LOG_PREFIX . "Creating rating record with cardId foreign key ( " . $cardId . ")  and userId foreign key (" . $userId . ")",LOG_DEBUG);
            $this->Deck->Card->Rating->create(array(SD_Global::$MODEL_CARD_ID => $cardId,SD_Global::$MODEL_USER_ID => $userId));
          }

          // Save rating
          $this->log($LOG_PREFIX . "Saving rating...ratingId( " . $ratingId . ") | rating: (" . $rating . ")",LOG_DEBUG);
          $this->Deck->Card->Rating->set(SD_Global::$MODEL_RATING_RATING,$rating);
          $this->Deck->Card->Rating->save();
        }

        // Save result if valid
        $result = $card[SD_Global::$SESSION_RESULT_KEY][SD_Global::$MODEL_RESULT_LAST_GUESS];
        if(!preg_match("/[0|1]/",$result)) {
          $this->log($LOG_PREFIX . "Result: (" . $result . ") not valid for cardId: (" . $cardId . ").");
        }
        else {

          // Set record to update or create new record
          $resultId = $card[SD_Global::$SESSION_RESULT_KEY][SD_Global::$SESSION_ID_KEY];
          if(strcmp($resultId,SD_Global::$NULL_STR) != 0) {
            $this->log($LOG_PREFIX . "Setting Result model key to " . $resultId,LOG_DEBUG); 
            $this->Deck->Card->Result->id = $resultId;

            // Retrieve totals in record to update
            if($result) {
                $tmpTot = $this->Deck->Card->Result->read(SD_Global::$MODEL_RESULT_TOT_CORRECT);
                $totalCorrect = $tmpTot[SD_Global::$MODEL_RESULT][SD_Global::$MODEL_RESULT_TOT_CORRECT] + 1;
                $this->Deck->Card->Result->set(SD_Global::$MODEL_RESULT_TOT_CORRECT,$totalCorrect);
            }
            else {
                $tmpTot = $this->Deck->Card->Result->read(SD_Global::$MODEL_RESULT_TOT_INCORRECT);
                $totalIncorrect = $tmpTot[SD_Global::$MODEL_RESULT][SD_Global::$MODEL_RESULT_TOT_INCORRECT] + 1;
                $this->Deck->Card->Result->set(SD_Global::$MODEL_RESULT_TOT_INCORRECT,$totalIncorrect);
            }

          }
          else {
            $this->log($LOG_PREFIX . "Creating result record with cardId foreign key ( " . $cardId . ")  and userId foreign key (" . $userId . ")",LOG_DEBUG);
            $this->Deck->Card->Result->create(array(SD_Global::$MODEL_CARD_ID => $cardId,SD_Global::$MODEL_USER_ID => $userId));

            // Increment count to 1
            if($result){
                $this->Deck->Card->Result->set(SD_Global::$MODEL_RESULT_TOT_CORRECT,1);
            }
            else {
                $this->Deck->Card->Result->set(SD_Global::$MODEL_RESULT_TOT_INCORRECT,1);
            }
          }

          // Save result
          $this->log($LOG_PREFIX . "Saving result...resultId( " . $resultId . ") | result: (" . $result . ")",LOG_DEBUG);
          $this->Deck->Card->Result->set(SD_Global::$MODEL_RESULT_LAST_GUESS,$result);
          $this->Deck->Card->Result->save();
        }

      } //end foreach

      // Return success
      return True;
    }

    function quitSession($deckId)
    {
      // Store userId
      $userId = $this->Auth->user('id');

      // Call writeSession to commit to model
      $success = False;
      $success = $this->writeSession($userId,$deckId);

      return $success;
    }

    /*
     * Ends a study session, writes ratings to DB.
     *
     */
    function quitStudy($deckId)
    {
      // Write session data, which consists of ratings
      $success = $this->quitSession($deckId);

      // Redirect to appropriate page
      if($success) {
        $this->redirect(array('controller'=>'decks','action'=>'view',$deckId));
      }
      else {
        $this->redirect(array('controller'=>'decks','action'=>'failure'));
      }
    }

    /*
     * Ends a quiz session, writes results to DB.
     *
     */
    function quitQuiz($deckId)
    {
      // Write session data, which consists of results
      $success = $this->quitSession($deckId);

      // Redirect to review page
      if($success) {
        $this->redirect(array('controller'=>'decks','action'=>'review',$deckId));
      }
      else {
        $this->redirect(array('controller'=>'decks','action'=>'failure'));
      }
    }

    /*
     * Generates the review page data, clears session
     *
     */
    function review($deckId) {

      // Store userId
      $userId = $this->Auth->user('id');

      // Set log info
      $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

      // Get deck session data
      $sessionData = $this->Session->read(SD_Global::$SESSION_USERS_KEY);
      $deckSessionData = $sessionData[$userId][$deckId];

      // Retrieve card question/answers for view
      $cardRecords = $this->getCards($deckId);

      // Generate array of cardIds to pass to getRatings & getResults
      $cardIds = $this->getCardIds($cardRecords);

      // Retrieve card ratings/results for view
      $ratingMap = $this->getRatings($cardIds);
      $resultMap = $this->getResults($cardIds);

      // Bind data to view
      $this->set('quizResults',$deckSessionData);
      $this->set('cards',$cardRecords);
      $this->set('cardsRatings',$ratingMap);
      $this->set('cardsResults',$resultMap);

      // Clear session
      $sessionToClear = SD_Global::$SESSION_USERS_KEY . "." . $userId . "." . $deckId;
      $this->log($LOG_PREFIX . "Clearing session: " . $sessionToClear,LOG_DEBUG);
      $this->Session->delete($sessionToClear);
    }

    /*
     * Called when writeSession fails.
     *
     */
    function failure() {

    }

    /*
     * DEPRECATED: This updated the model during each ajax call.
     * Moved this so that it writes the data to a session instead.
     * 
     * Called using AJAX to update card/deck data
     * stored in database.
     *
     */
    function update_old()
    {
        // Set layout to blank
        $this->layout = "";

        // Set user ID
        $userId = $this->Auth->user('id');

        // Grab data from url, params and sanitize input
        App::import('Sanitize');
        $cardId = Sanitize::paranoid($this->params['url']['cid']);
        $ratingId = Sanitize::paranoid($this->params['url']['rid']);
        $resultId = Sanitize::paranoid($this->params['url']['sid']);
        $rating = Sanitize::paranoid($this->params['url']['rating']);
        $correct = Sanitize::paranoid($this->params['url']['correct']);

        // DEBUG
        //$url = $this->params['url']['url'];
        //$tempStr = "cardId: " . $cardId . "<br/>ratingId: " . $ratingId . "<br/>resultId: " . $resultId . "<br/> rating: " . $rating . "<br/> correct: " . $correct . "\n";

        // Set primary key of card model
        $this->Card->id = $cardId;

        /**
         * Update rating model
         * Create new/Update rating record
         *
         */
        // Must be valid rating (0-3)
        if(preg_match("/[0-3]/",$rating)) {
            
            $recordAlreadyExists = 1;

            // There is no passed ratingId, check to see if one was created
            // during this session.  If not, then create a new rating record.
            if(strcmp($ratingId,SD_Global::$NULL_STR) == 0) {

                // Check if record was created in this session first
                $ratingParams = array(
                                    'fields' => array('Rating.id'),
                                    'conditions' => array('Rating.card_id' => $cardId,'Rating.user_id' => $userId)
                );
                $this->Card->Rating->recursive = -1;
                $ratingRecord = $this->Card->Rating->find('first',$ratingParams);

                // Debug
                $this->set('debug',$ratingRecord);

                // Set id if record was retrieved
                if($ratingRecord['Rating']['id']) {
                    $ratingId = $ratingRecord['Rating']['id'];
                }
                else {
                    // Create new record
                    $this->Card->Rating->create(array('card_id' => $cardId,'user_id' => $userId));
                    $recordAlreadyExists = 0;
                }
            }

            // Set and save
            if($recordAlreadyExists) {
                $this->Card->Rating->id = $ratingId;
            }
            $this->Card->Rating->set('rating',$rating);
            $this->Card->Rating->save();
        }
        /**
         * End Update rating model
         */

        /**
         * Update result model
         * Create new/Update result record
         *
         */
        // Validate $correct is 1 or 0
        if(preg_match("/[0|1]/",$correct)) {

            $recordAlreadyExists = 1;

            // Create new record if resultId is null
            if(strcmp($resultId,SD_Global::$NULL_STR) == 0) {

                // Check if record was created in this session first
                $resultsParams = array(
                                    'fields' => array('Result.id'),
                                    'conditions' => array('Result.card_id' => $cardId,'Result.user_id' => $userId)
                );
                $this->Card->Result->recursive = -1;
                $resultRecord = $this->Card->Result->find('first',$resultsParams);

                // Debug
                $this->set('debug2',$resultRecord);
                    
                // Set id if record was retrieved
                if($resultRecord['Result']['id']) {
                    $resultId = $resultRecord['Result']['id'];
                }
                else {
                    // Create new record
                    $this->Card->Result->create(array('card_id'=>$cardId,'user_id'=>$userId));
                    $recordAlreadyExists = 0;
                }
            }

            // Update total correct/incorrect history
            // Update existing totals
            if($recordAlreadyExists){

                // Set resultId
                $this->Card->Result->id = $resultId;

                // Retrieve totals to update
                if($correct) {
                    $tmpTot = $this->Card->Result->read('total_correct');
                    $totalCorrect = $tmpTot['Result']['total_correct'] + 1;
                    $this->Card->Result->set('total_correct',$totalCorrect);
                }
                else {
                    $tmpTot = $this->Card->Result->read('total_incorrect');
                    $totalIncorrect = $tmpTot['Result']['total_incorrect'] + 1;
                    $this->Card->Result->set('total_incorrect',$totalIncorrect);
                }

            }
            // Set new record total to 1
            else {

                // Increment count to 1
                if($correct) {
                    $this->Card->Result->set('total_correct',1);
                }
                else {
                    $this->Card->Result->set('total_incorrect',1);
                }

            }
            $this->Card->Result->set('last_guess',$correct);
            $this->Card->Result->save();
        }
        /**
         * End update result model
         */
    
        // Send a response back
        $this->set('response',$tempStr);
    }

    function uploadCSV(){
    	    
    	//disable need for a view		
     	$this->autoRender=false;

	
	$file = new File($this->data['Deck']['csv_file']['tmp_name']);
	
	//$data = h($file->read()); //read file contents and pass through htmlspecialchars function
	Configure::write ('debug',0);
	$row = 1;
	$handle=fopen($this->data['Deck']['csv_file']['tmp_name'],"r");
	$csvReturn=array();
	while($fileContents = fgetcsv($handle)){
		$csvReturn[$row]['q'] = $fileContents[0];
		$csvReturn[$row]['a'] = $fileContents[1];
		$row++;
	}
	fclose($handle);
	//$file->close();	
	//$this->Session->setFlash("temp name:".$data);

	//$this->Session->setFlash("temp name:".$this->data['Deck']['csv_file']['tmp_name']." type:".$this->data['Deck']['csv_file']['type']." Extention: ".$ext);

	/*
	$csvReturn[1]['q'] = "question 1";
	$csvReturn[1]['a'] = "answer 1";
	$csvReturn[2]['q'] = "question 2";
	$csvReturn[2]['a'] = "answer 2";

	*/
	$csvReturn['totalCount'] = count($csvReturn);
	$result = json_encode($csvReturn);
	
	
	echo $result;
    }

	function view($id = null)
    {
        // Set deck meta info
        $this->Deck->id = $id;
        $this->set('deckInfo', $this->Deck->read());

        // Retrieve cards in deck by deck_id
        $findParams = array(
                            'conditions' => array('Card.deck_id' => $this->Deck->id),
                            'fields' => array('Card.question', 'Card.answer'));
        $this->set('deck', $this->Card->find('all',$findParams));
    }

}

?>
