<?php

include 'sd_global.php';

class DecksController extends AppController {

	var $name = 'Decks';
    var $scaffold;
    var $uses = array('Deck','Card','Tag','MyDeck','DeckTag','Rating','Result');
    var $helpers = array('Html','Javascript','Form','RelativeTime');
    var $components = array('Auth','RequestHandler');


    function beforeFilter(){

    	// Call AppConroller::beforeFilter()
        parent::beforeFilter();
      
        // Set Auth support
        $this->Auth->allow('explore');
        $this->Auth->authError = "You must login or register to continue";
    } 

	function create(){

        if(!empty($this->data)) {
            $editDeck = false;
            $this->saveDeck($editDeck);
        }
            
        

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
			if($deckToRemove['Deck']['user_id'] == $this->Auth->user('id')) {
				//actually deletes the deck			    
				$this->Deck->delete($this->params['form']['id'],true);
			}
		}
	}

    function edit($deckId = null) {
        //if no deck Id provided send the user to the create page
        if(empty($this->data)){
            if($deckId == null) {
                $this->redirect(array('controller'=>'decks','action'=>'create'));
            }
            
            // Set user id
            $userId = $this->Auth->user('id');
            
            // Disable recursion
            $this->Deck->recursive = -1;
            $this->Card->recursive = -1;
            $this->DeckTag->recursive = -1;
            $this->Tag->recursive = -1;
            
            $deckParams =  array('conditions' =>  array('Deck.id' => $deckId));
            $deck = $this->Deck->find('first',$deckParams);
            
            //if this deck does not exist send them to the create page
            
            if($deck == null) {
                $this->redirect(array('controller'=>'decks','action'=>'create'));
            }
            
            //if this is not the users deck redirect them to this decks deck info page
            
            if($deck['Deck']['user_id'] != $this->Auth->user('id')) {
                $this->redirect(array('controller'=>'decks','action'=>'info',$deckId));
            }
            
            //get the cards that belong to the deck
            $cardsParams =  array('conditions' =>  array('Card.deck_id' => $deckId),'order' => 'Card.card_order ASC');
            $cards = $this->Card->find('all',$cardsParams);
            
           
            //get the tags that belong to the deck
            $deckTagParams = array('conditions' =>  array('DeckTag.deck_id' => $deckId),'fields' => array('DeckTag.tag_id'));
            $deckTags = $this->DeckTag->find('list',$deckTagParams);
            
            $tagsParams = array('conditions' =>  array('Tag.id' => $deckTags),'fields' => array('Tag.id','Tag.tag'));
            $tags = $this->Tag->find('all',$tagsParams);
            
            App::import('Sanitize');
            /*
            $tags = Sanitize::clean($tags);	
            $deck = Sanitize::clean($deck);	
            
            $cards = Sanitize::clean($cards, array('encode' => false,'escape' => false));	
            */
            
            $this->set('existingTags', $tags);           
            $this->set('existingDeck', $deck);
            $this->set('existingCards', $cards);
        
        }
        else {
            $editDeck = true;
            $this->saveDeck($editDeck);
            
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
	        $sortBy = 'Deck.quiz_count DESC';
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
   * Adds entry to my_decks table for deck.
   *
   */
  function favorite($deckId)
  {

    $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";
    if(!isset($deckId)) {
        $this->log($LOG_PREFIX . "deckId is null.");
        return null;
    }
    
    // Disable recursion
    $this->Deck->recursive = -1;

    // Set user id
    $userId = $this->Auth->user('id');

    // Create new record in my_decks and save
    $this->Deck->MyDeck->create(array(SD_Global::$MODEL_DECK_ID => $deckId,
                                      SD_Global::$MODEL_USER_ID => $userId,
                                      SD_Global::$MODEL_MYDECK_TYPE => SD_Global::$USER_SAVED));
    $this->Deck->MyDeck->save();

    // Reload dashboard?
    $this->redirect(array('controller'=>'users', 'action'=>'dashboard'));
  }
   
  /*
   * Helper which returns cards given a deck.
   *
   */
  private function getCards($deckId, $ratings)
  {
    if(!isset($deckId)) {
      $this->log("[" . get_class($this) . "->" . __FUNCTION__ . "] " . "deckId is null.");
      return null;
    }

    // Disable recursion
    $this->Deck->recursive = -1;
    $this->Deck->Card->recursive = -1;

    // Set user id
    $userId = $this->Auth->user('id');

    // TODO: Sanitize input?

    // Subquery to get all cards in the deck
    $subQueryOnCards = "(SELECT cards.id AS cid, cards.card_order AS co, cards.question AS cq, cards.answer AS ca FROM cards WHERE cards.deck_id=$deckId) AS Card";

    // Subquery to get all the users's ratings for the cards
    $subQueryOnRatings = "(SELECT ratings.id AS rid, ratings.rating AS rr, ratings.card_id as rcid, ratings.user_id AS ruid FROM ratings WHERE ratings.user_id=$userId) AS Rating";

    // Debug ratings
    //$this->log("[" . get_class($this) . "->" . __FUNCTION__ . "] " . "ratings: " . isset($ratings), LOG_DEBUG);

    // Build WHERE clause to filter rating
    if(isset($ratings) && count($ratings) > 0) {

        // Set selected ratings as a string
        $ratingsStr = "(" . implode(",", $ratings) . ")";

        $filter = "WHERE rr IN $ratingsStr";

        // Handle empty 'hard' ratings which have no records
        $specialCaseHardRating = 3;
        if(in_array($specialCaseHardRating, $ratings)) {
            $filter .= " OR rr is null";
        }
    }
    else {
        $filter = "";
    }

    // Build entire SQL string
    $query = "SELECT cid AS 'id', co as 'card_order', cq as 'question', ca AS 'answer', rid AS 'id', rr AS 'rating' FROM $subQueryOnCards LEFT JOIN $subQueryOnRatings ON cid=rcid $filter ORDER BY co ASC;";

    // Debug SQL
    //$this->log("[" . get_class($this) . "->" . __FUNCTION__ . "] " . "query: $query", LOG_DEBUG);

    // Run SQL
    $cardRecords = $this->Deck->Card->query($query);

    return $cardRecords;
  }


  /*
   * Helper which rearranges array by cardId
   *
   */
  private function indexByCardId($cardRecords)
  {
    $sorted = array();
    foreach($cardRecords as $card) {
        $id = $card['Card']['id'];
        $sorted[$id] = $card;
    }
    return $sorted;
  }

  /*
   * Helper which returns an array of cards' IDs
   *
   */
  private function getCardIds($cardRecords)
  {
    // Store card IDs in array for use ratings/results queries
    $cardIds = array();
    foreach($cardRecords as $card) {
       array_push($cardIds,$card['Card']['id']); 
    }
    return $cardIds;
  }

  /**
   * Takes above output and returns array with count of cards for each rating.
   *
   */
  private function getRatingsCount($cards)
  {
    if(!isset($cards)) {
        return null;
    }
    
    $ratingCount = array(SD_GLOBAL::$EASY_CARD => 0,
                         SD_GLOBAL::$MEDIUM_CARD => 0,
                         SD_GLOBAL::$HARD_CARD => 0,
                         SD_GLOBAL::$TOTAL_CARD => 0);
    foreach($cards as $cardRecord) {
        //$this->log("[" . get_class($this) . "->" . __FUNCTION__ . "] " . "foo: " . print_r($cardRecord));
        $rating = $cardRecord['Rating']['rating'];
        if($rating == null || $rating== SD_GLOBAL::$HARD_CARD) {
            $ratingCount[SD_GLOBAL::$HARD_CARD]++;
        }
        else if($rating == SD_GLOBAL::$MEDIUM_CARD) {
            $ratingCount[SD_GLOBAL::$MEDIUM_CARD]++;
        }
        else if($rating == SD_GLOBAL::$EASY_CARD) {
            $ratingCount[SD_GLOBAL::$EASY_CARD]++;
        }
        else {
            $this->log("[" . get_class($this) . "->" . __FUNCTION__ . "] " . "Unknown rating: $rating");
        }
        $ratingCount[SD_GLOBAL::$TOTAL_CARD]++;
    }
    return $ratingCount;
  }

  /*
   * Helper which returns cards' results given an array of card IDs.
   *
   */
  private function getResults($cardIds)
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

    /*
     * Get user quiz count for a deck
     */
    function getQuizCount($deckId) {

        // Set userId
        $userId = $this->Auth->user('id');

        // Run find
        $this->Deck->MyDeck->recursive = -1;
        $params = array(
                      'conditions' => array('MyDeck.user_id' => $userId,
                                            'MyDeck.deck_id' => $deckId),
                      'fields' => array('MyDeck.id', 'MyDeck.quiz_count'));
        return $this->Deck->MyDeck->find('first', $params);
    }

  /*
   * Deck landing page
   *
   */
    function info($deckId)
    {
        // Logging
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";
        if(!isset($deckId)) {
            $this->log($LOG_PREFIX . "deckId is null.");
            return null;
        }

        // Clear selected ratings 
        $this->Session->delete(SD_Global::$SESSION_RATINGS_SELECTED_KEY);

        // Call notAssociated method
        // Binds $notAssociated -> whether deck is in dashboard (i.e. in my_decks)
        $this->notAssociated($deckId);

        // Call study method
        // Binds $deckId, $deckData, $cards, $cardsRatingsCount, and $cardsResults to view
        $this->study($deckId);

        // Call quizResults method to bind session data
        // Binds $quiz to current session data for this deck
        $this->quizResults($deckId);

        // Set quiz count
        $myDeckResults = $this->getQuizCount($deckId);
        $this->set('userQuizCount', $myDeckResults['MyDeck']['quiz_count']);
    }

    /*
     *  Helper to send flag if deck is in dashboard (i.e. is in 'my_decks' table)
     */
    private function notAssociated($deckId)
    {
        // Disable recursion
        $this->Deck->recursive = -1;
        $this->Deck->MyDeck->recursive = -1;

        // Set user id
        $userId = $this->Auth->user('id');

        // Check if there is an association in my_decks for this deck
        $params = array(
                      'conditions' => array('MyDeck.user_id' => $userId,
                                            'MyDeck.deck_id' => $deckId),
                      'fields' => array('MyDeck.id', 'MyDeck.type'));
        $myDeckResults = $this->Deck->MyDeck->find('all', $params);

        // Send flag to view
        $notAssociated = (count($myDeckResults) == 0);
        $this->set('notAssociated', $notAssociated);
    }

    /*
     * Helper to access session data
     *
     */
    private function addSessionTreeNode($sessionData, $keys)
    {

        if($keys == null) {
            return $sessionData;
        }

        // For logging
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";
        $this->log($LOG_PREFIX . "Executing function", LOG_DEBUG);

        // Default initializations
        $curNode = &$sessionData;
        $i = 0;
        $key = $keys[$i];    

        // Go as deep as possible into sessionData
        if(isset($curNode)) {
            for( ; $i < count($keys); $i++) {
                $key = $keys[$i];    
                if(array_key_exists($key, $curNode)) {
                    $this->log($LOG_PREFIX . "Key: $key exists in sessionData.", LOG_DEBUG);
                    $curNode = &$curNode[$key];
                }
                else {
                    $this->log($LOG_PREFIX . "Key: $key does NOT exist in sessionData.", LOG_DEBUG);
                    break;    
                }
            }
        }

        // Keys leftover, build rest of sessionData array
        if($i != count($keys)) {

            for($j = $i; $j < count($keys)-2; $j++) {
                $key = $keys[$j];
                $this->log($LOG_PREFIX . "Creating key: $key in sessionData.", LOG_DEBUG);
                $curNode[$key] = array();
                $curNode = &$curNode[$key];
            }
        }

        // Add leaf value
        $leafKey = $keys[$j];
        $leafValue = $keys[$j+1];
        $curNode[$leafKey] = $leafValue;

        return $sessionData;
    }

  /*
   * Deck info Learn/Quiz action. Redirects to Study/Quiz
   *
   */
    function infoSubmit()
    {
        // Params passed by form
        $ratingsSelected = $this->data['Deck']['RatingsSelected'];
        $isQuizMode = (int) $this->data['Deck']['isQuizMode'];
        $deckId = (int) $this->data['Deck']['deckId'];
        $userId = $this->Auth->user('id');

        // Set log info
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Debug
        //$this->log($LOG_PREFIX . "empty: " . empty($ratingsSelected), LOG_DEBUG);

        // Write the selected ratings in session
        if(!empty($ratingsSelected)) {
            $this->Session->write(SD_Global::$SESSION_RATINGS_SELECTED_KEY, $ratingsSelected);
        }

        // Redirect to Quiz/Study
        if($isQuizMode) {

            //$this->Session->write(SD_Global::$SESSION_DECK_MODE_KEY.$deckId, SD_Global::$SESSION_DECK_MODE_QUIZ);
            
            // Set flag
            $this->Session->write(SD_Global::$SESSION_DECK_MODE_QUIZZED.$deckId, 1);

            // Clear session
            $this->clearSession($deckId, SD_Global::$SESSION_COMMIT_RESULT_TYPE);

            // Redirect
            $this->redirect(array('controller'=>'decks', 'action'=>'quiz', $deckId));

        }
        else {
            //$this->Session->write(SD_Global::$SESSION_DECK_MODE_KEY.$deckId, SD_Global::$SESSION_DECK_MODE_LEARN);
            $this->redirect(array('controller'=>'decks', 'action'=>'learn', $deckId));
        }
    }

    /*
     * Private method called by 'learn' and 'quiz' action.
     * Retrieves card records and ratings for view.
     *
     */
    private function study($id)
    {
        // Set log info
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        if(!isset($id)) {
            $this->log($LOG_PREFIX . "Deck ID is null");
            return false;
        }

        // Read selected ratings from session
        $ratingsSelected = $this->Session->read(SD_Global::$SESSION_RATINGS_SELECTED_KEY);

        // Clear the session contents
        $this->Session->delete(SD_Global::$SESSION_RATINGS_SELECTED_KEY);

        // Debug
        /*
        if(isset($ratingsSelected)) {
            foreach($ratingsSelected as $rating) {
                $this->log($LOG_PREFIX . "ratingsSelected: $rating", LOG_DEBUG);
            }
        }
        */

        // Set $deckRecord
        $this->Deck->id = $id;
        $this->Deck->recursive = -1;
        $deckRecord = $this->Deck->read();

        // Call helper to retrieve array of cards
        $cardRecords = $this->getCards($id, $ratingsSelected);
        $indexedCardRecords = $this->indexByCardId($cardRecords);

        // Call helpers to post-process data
        $cardIds = $this->getCardIds($cardRecords);
        $ratingsCount = $this->getRatingsCount($cardRecords);

        // Call helper to retrieve results
        $resultMap = $this->getResults($cardIds);

        // Set variables for view
        $this->set('deckId',$deckRecord['Deck']['id']);
        $this->set('deckData',$deckRecord);
        $this->set('cards',$cardRecords);
        $this->set('cardsIndexed',$indexedCardRecords);
        $this->set('cardsRatingsCount',$ratingsCount);
        $this->set('cardsResults',$resultMap);
        return true;
    }

    /*
     * Action for study mode
     * Calls private study method
     *
     */
    function learn($id)
    {
        // Call private study function
        // Difference between study/quiz in JavaScript
        $this->study($id);
    }

    /*
     * Action for quiz mode
     * Calls private study method
     *
     */
    function quiz($id)
    {
        // Call private study function
        // Difference between study/quiz in JavaScript
        $this->study($id);
    }

    /*
     * Called using AJAX to update rating card/deck data stored in session.
     *
     */
    function updateRating()
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
        $rating = Sanitize::paranoid($this->params['url']['rating']);

        // Set log info
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Validate rating, correct
        $ratingIsValid = preg_match("/[0-3]/",$rating);

        // Debug
        $this->log($LOG_PREFIX . "UPDATESTART - deckId: " . $deckId, LOG_DEBUG);
        $this->log($LOG_PREFIX . "UPDATESTART - cardId: " . $cardId, LOG_DEBUG);
        $this->log($LOG_PREFIX . "UPDATESTART - rating: " . $rating, LOG_DEBUG);
        $this->log($LOG_PREFIX . "UPDATESTART - ratingId: " . $ratingId, LOG_DEBUG);
        $this->log($LOG_PREFIX . "*********************************", LOG_DEBUG);

        /*
         * Store result/rating data in session object.
         *
         * Data structure:
         * [$SESSION_DECK_RATING_KEY.$deckId] -> [$cardId] -> [$SESSION_RATING_KEY] -> [$SESSION_ID_KEY]
         *                                                                          -> [$SESSION_RATING_VAL_KEY]
         */

        // Read deck object from session
        $deckObj = $this->Session->read(SD_Global::$SESSION_DECK_RATING_KEY.$deckId);

        // Card does not exist in session, init structure
        if(!(isset($deckObj) && array_key_exists($cardId, $deckObj))) {

            $deckObj[$cardId] = array(SD_Global::$SESSION_RATING_KEY => array(SD_Global::$SESSION_ID_KEY => null,
                                                                              SD_Global::$SESSION_RATING_VAL_KEY => null));
        }

        // Update rating in session object
        $deckObj[$cardId][SD_Global::$SESSION_RATING_KEY][SD_Global::$SESSION_ID_KEY] = $ratingId;
        $deckObj[$cardId][SD_Global::$SESSION_RATING_KEY][SD_Global::$SESSION_RATING_VAL_KEY] = $rating;

        // Log to debug
        $this->set("debug", $deckObj);

        // Write to the session
        $this->Session->write(SD_Global::$SESSION_DECK_RATING_KEY.$deckId, $deckObj);
        return true;
    }

    /*
     * Called using AJAX to update result card/deck data stored in session.
     *
     */
    function updateResult()
    {
        // Set layout to blank
        $this->layout = "";

        // Set user ID
        $userId = $this->Auth->user('id');

        // Grab data from url, params and sanitize input
        App::import('Sanitize');
        $deckId = Sanitize::paranoid($this->params['url']['did']);
        $cardId = Sanitize::paranoid($this->params['url']['cid']);
        $resultId = Sanitize::paranoid($this->params['url']['sid']);
        $correct = Sanitize::paranoid($this->params['url']['correct']);

        // Set log info
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Validate rating, correct
        $resultIsValid = preg_match("/[0|1]/", $correct);

        // Debug
        $this->log($LOG_PREFIX . "UPDATESTART - deckId: " . $deckId, LOG_DEBUG);
        $this->log($LOG_PREFIX . "UPDATESTART - cardId: " . $cardId, LOG_DEBUG);
        $this->log($LOG_PREFIX . "UPDATESTART - correct: " . $correct, LOG_DEBUG);
        $this->log($LOG_PREFIX . "UPDATESTART - resultId: " . $resultId, LOG_DEBUG);
        $this->log($LOG_PREFIX . "*********************************", LOG_DEBUG);

        /*
         * Store result/rating data in session object.
         *
         * Data structure:
         * [$SESSION_DECK_RATING_KEY.$deckId] -> [$cardId] -> [$SESSION_RESULT_KEY] -> [$SESSION_ID_KEY]
         *                                                 -> [$SESSION_RESULT_VAL_KEY]
         */

        // Read deck object from session
        $deckObj = $this->Session->read(SD_Global::$SESSION_DECK_RESULT_KEY.$deckId);

        // Card does not exist in session, init structure
        if(!(isset($deckObj) && array_key_exists($cardId, $deckObj))) {
            $deckObj[$cardId] = array(SD_Global::$SESSION_RESULT_KEY => array(SD_Global::$SESSION_ID_KEY => null,
                                                                              SD_Global::$SESSION_RESULT_VAL_KEY => null));
        }

        // Update result in session object
        $deckObj[$cardId][SD_Global::$SESSION_RESULT_KEY][SD_Global::$SESSION_ID_KEY] = $resultId;
        $deckObj[$cardId][SD_Global::$SESSION_RESULT_KEY][SD_Global::$SESSION_RESULT_VAL_KEY] = $correct;

        // Log to debug
        $this->set("debug", $deckObj);

        // Write to the session
        $this->Session->write(SD_Global::$SESSION_DECK_RESULT_KEY.$deckId, $deckObj);
        return true;
    }
    /**
     * Clears user deck session object
     */
    function clearSession($deckId, $commitType)
    {
        // setup log prefix for this function
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Define session to clear
        if(strcmp($commitType, SD_Global::$SESSION_COMMIT_RATING_TYPE) == 0) {
            $sessionToClear = SD_Global::$SESSION_DECK_RATING_KEY.$deckId;
        }
        else {
            $sessionToClear = SD_Global::$SESSION_DECK_RESULT_KEY.$deckId;
        }
        
        // Clear session
        $this->log($LOG_PREFIX . "Clearing session: " . $sessionToClear, LOG_DEBUG);
        $this->Session->delete($sessionToClear);
    }

    /*
     * Helper function called by quit which writes the session data to the model.
     *
     */
    function writeSession($deckId, $commitType)
    {
        // setup log prefix for this function
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Check for null ids
        if(!(isset($deckId) && isset($commitType))) {
            $this->log($LOG_PREFIX . "Invalid deckId: " . $deckId . " or commitType: " . $commitType, LOG_ERROR);
            return false;
        }

        $isUpdatingRating = (strcmp($commitType, SD_Global::$SESSION_COMMIT_RATING_TYPE) == 0);

        // Retrieve session
        if($isUpdatingRating) {
            $deckObj = $this->Session->read(SD_Global::$SESSION_DECK_RATING_KEY.$deckId);
            $this->writeRatingSession($deckId, $deckObj);
            $this->clearSession($deckId, SD_Global::$SESSION_COMMIT_RATING_TYPE);
        }
        else {
            $deckObj = $this->Session->read(SD_Global::$SESSION_DECK_RESULT_KEY.$deckId);
            $this->writeResultSession($deckId, $deckObj);
        }
        return;
    }

    // Writes rating session to Rating model
    private function writeRatingSession($deckId, $deckObj) {

        // setup log prefix for this function
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Check for null object
        if(!isset($deckObj)) {
            $this->log($LOG_PREFIX . "Deck session not present for: " . SD_Global::$SESSION_DECK_RATING_KEY.$deckId, LOG_ERROR);
            return false;
        }

        // userId as foreign key to save records
        $userId = $this->Auth->user('id');

        // Save Card Rating/Result models
        foreach($deckObj as $cardId => $card) {

            // Obtain rating/result info for card
            $ratingId = $card[SD_Global::$SESSION_RATING_KEY][SD_Global::$SESSION_ID_KEY];
            $rating = $card[SD_Global::$SESSION_RATING_KEY][SD_Global::$SESSION_RATING_VAL_KEY];

            // Validate rating, correct
            $ratingIsValid = preg_match("/[0-3]/", $rating);
            $ratingIdIsValid = preg_match("/[\d]+/", $ratingId);

            // Debug
            $this->log($LOG_PREFIX . "*********************************", LOG_DEBUG);
            $this->log($LOG_PREFIX . "writeSession - cardId: " . $cardId, LOG_DEBUG);
            $this->log($LOG_PREFIX . "writeSession - ratingId: " . $ratingId, LOG_DEBUG);
            $this->log($LOG_PREFIX . "writeSession - ratingIdIsValid: " . $ratingIdIsValid, LOG_DEBUG);
            $this->log($LOG_PREFIX . "writeSession - rating: " . $rating, LOG_DEBUG);
            $this->log($LOG_PREFIX . "writeSession - ratingIsValid: " . $ratingIsValid, LOG_DEBUG);

            if($ratingIsValid) {

                // Set record to update or create new
                if($ratingIdIsValid) {
                    $this->log($LOG_PREFIX . "Setting Rating model key to " . $ratingId, LOG_DEBUG); 
                    $this->Deck->Card->Rating->id = $ratingId;
                }
                else {
                    $this->log($LOG_PREFIX . "Creating rating record with cardId foreign key (" . $cardId . ")  and userId foreign key (" . $userId . ")", LOG_DEBUG);
                    $this->Deck->Card->Rating->create(array(SD_Global::$MODEL_CARD_ID => $cardId,
                                                            SD_Global::$MODEL_USER_ID => $userId));
                }

                // Save rating
                $this->log($LOG_PREFIX . "Saving rating...ratingId(" . $ratingId . ") | rating: (" . $rating . ")", LOG_DEBUG);
                $this->Deck->Card->Rating->set(SD_Global::$MODEL_RATING_RATING, $rating);
                $this->Deck->Card->Rating->save();
            }

        } //end foreach
        return true;
    }

    // Writes Result session to Result model
    private function writeResultSession($deckId, $deckObj) {

        // setup log prefix for this function
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Check for null object
        if(!isset($deckObj)) {
            $this->log($LOG_PREFIX . "Deck session not present for: " . SD_Global::$SESSION_DECK_RATING_KEY.$deckId, LOG_ERROR);
            return false;
        }

        // userId as foreign key to save records
        $userId = $this->Auth->user('id');

        // Save Card Rating/Result models
        foreach($deckObj as $cardId => $card) {

            // Obtain rating/result info for card
            $resultId = $card[SD_Global::$SESSION_RESULT_KEY][SD_Global::$SESSION_ID_KEY];
            $result = $card[SD_Global::$SESSION_RESULT_KEY][SD_Global::$SESSION_RESULT_VAL_KEY];

            // Validate rating, correct
            $resultIsValid = preg_match("/[0|1]/", $result);
            $resultIdIsValid = preg_match("/[\d]+/", $resultId);

            // Debug
            /*
            $this->log($LOG_PREFIX . "*********************************", LOG_DEBUG);
            $this->log($LOG_PREFIX . "writeSession - cardId: " . $cardId, LOG_DEBUG);
            $this->log($LOG_PREFIX . "writeSession - resultId: " . $resultId, LOG_DEBUG);
            //$this->log($LOG_PREFIX . "writeSession - resultIdIsValid: " . $resultIdIsValid, LOG_DEBUG);
            $this->log($LOG_PREFIX . "writeSession - result: " . $result, LOG_DEBUG);
            //$this->log($LOG_PREFIX . "writeSession - resultIsValid: " . $resultIsValid, LOG_DEBUG);
            */

            if($resultIsValid) {

                // Set record to update or create new
                if($resultIdIsValid) {
                    $this->log($LOG_PREFIX . "Setting Result model key to " . $resultId, LOG_DEBUG); 
                    $this->Deck->Card->Result->id = $resultId;

                    // Retrieve and update cumulative total fields
                    if($result) {
                        $tmpTot = $this->Deck->Card->Result->read(SD_Global::$MODEL_RESULT_TOT_CORRECT);
                        $totalCorrect = $tmpTot[SD_Global::$MODEL_RESULT][SD_Global::$MODEL_RESULT_TOT_CORRECT] + 1;
                        $this->Deck->Card->Result->set(SD_Global::$MODEL_RESULT_TOT_CORRECT, $totalCorrect);
                    }
                    else {
                        $tmpTot = $this->Deck->Card->Result->read(SD_Global::$MODEL_RESULT_TOT_INCORRECT);
                        $totalIncorrect = $tmpTot[SD_Global::$MODEL_RESULT][SD_Global::$MODEL_RESULT_TOT_INCORRECT] + 1;
                        $this->Deck->Card->Result->set(SD_Global::$MODEL_RESULT_TOT_INCORRECT, $totalIncorrect);
                    }

                }
                else {
                    $this->log($LOG_PREFIX . "Creating result record with cardId foreign key (" . $cardId . ")  and userId foreign key (" . $userId . ")", LOG_DEBUG);
                    $this->Deck->Card->Result->create(array(SD_Global::$MODEL_CARD_ID => $cardId,
                                                            SD_Global::$MODEL_USER_ID => $userId));
                    // Set count to 1
                    if($result){
                        $this->Deck->Card->Result->set(SD_Global::$MODEL_RESULT_TOT_CORRECT,1);
                    }
                    else {
                        $this->Deck->Card->Result->set(SD_Global::$MODEL_RESULT_TOT_INCORRECT,1);
                    }
                }

                // Save result
                $this->log($LOG_PREFIX . "Saving result...resultId( " . $resultId . ") | result: (" . $result . ")",LOG_DEBUG);
                $this->Deck->Card->Result->set(SD_Global::$MODEL_RESULT_LAST_GUESS, $result);
                $this->Deck->Card->Result->save();
            }

        } //end foreach

        // Increment global quiz count field in 'decks' table
        $this->Deck->recursive = -1;
        $this->Deck->id = $deckId;
        $tmp = $this->Deck->read(SD_Global::$MODEL_DECK_QUIZ_COUNT);
        $quizCount = $tmp['Deck']['quiz_count'];
        $quizCount++;
        $this->Deck->set(SD_Global::$MODEL_DECK_QUIZ_COUNT, $quizCount);
        $this->Deck->save();

        // Increment user quiz count in 'my_decks' table
        $myDeckResults = $this->getQuizCount($deckId);
        $userQuizCount = $myDeckResults['MyDeck']['quiz_count'];
        $userQuizCount++;
        $myDeckId = $myDeckResults['MyDeck']['id'];
        $this->Deck->MyDeck->id = $myDeckId;
        $this->Deck->MyDeck->set(SD_Global::$MODEL_DECK_QUIZ_COUNT, $userQuizCount);
        $this->Deck->MyDeck->save();

        return true;
    }

    /*
     * Ends a learn/quit session
     *
     */
    function quit($deckId, $commitType)
    {
        // Call writeSession to commit to model
        $success = $this->writeSession($deckId, $commitType);

        // Redirect to deck info
        $this->redirect(array('controller'=>'decks', 'action'=>'info', $deckId));
    }

    /*
     * Generates the review page data, clears session
     *
     */
    function quizResults($deckId)
    {
        // Set log info
        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";

        // Flag set for quiz in this session
        $quizzedInSession = $this->Session->read(SD_Global::$SESSION_DECK_MODE_QUIZZED.$deckId);

        // Get deck session data
        $sessionData = $this->Session->read(SD_Global::$SESSION_DECK_RESULT_KEY.$deckId);

        // Set quiz results only if a quiz has taken place in this session
        if(isset($quizzedInSession) && isset($sessionData)) {
            $this->set('quiz', $sessionData);
            $this->log($LOG_PREFIX . "Binding 'quiz' to 'sessionData'", LOG_DEBUG);
            return true;
        }
        return false;
    }
    
    //function that handles creating and editing of deck
    private function saveDeck($edit = null) {
       
        

        //gets authenticated user Id
        $userId = $this->Auth->user('id');


        // add user id into deck for non-edit
        if(!$edit) {
            $this->data['Deck']['user_id'] = $userId; 
        }

        $this->Card->recursive = -1;
        $cardsToDelete = array();
        
        //adds all previous existing cardsToDelete array
        if($edit) {
            $cardsParams =  array('conditions' =>  array('Card.deck_id' => $this->data['Deck']['id']),'fields' => array('Card.id'));
            $cards = $this->Card->find('all',$cardsParams);
            $cardCount = count($cards);
            $deckId = $this->data['Deck']['id'];
            
            for($i = 0; $i < $cardCount; $i++) {
                $tempCardId = $cards[$i]['Card']['id'];
                $cardsToDelete[$tempCardId] = $tempCardId;
            }

        }
        
        
        $subCount = 0;
        
        //finds the number of cards being entered
        $num = count($this->data['Card']);

        //traverses the cards and unsets empty card rows
        for($x = 0; $x < $num; $x ++) {
            //remove empty cards from creating
            if(empty($this->data['Card'][$x]['question']) && empty($this->data['Card'][$x]['answer'])) {
                unset($this->data['Card'][$x]);
                $subCount++;
            }
            else {
                //keeps the ordering sequential
                $this->data['Card'][$x]['card_order'] = $this->data['Card'][$x]['card_order'] - $subCount;
                
                //removes cards from cardsToDelete array
                if($edit) {
                    $currentCardId = -1;
                    if(isset($this->data['Card'][$x]['id'])){
                        $currentCardId = $this->data['Card'][$x]['id'];
                    }
                    if(isset($cardsToDelete[$currentCardId])) {
                        unset($cardsToDelete[$currentCardId]);
                    
                    }
                }
            }

        }
        

        if($this->Deck->saveAll($this->data,array('validate' => 'only'))) {
           
            $this->Deck->saveAll($this->data,array('validate' => 'false'));
            
            //removes cards that user deleted during editing process
            if($edit) {
                foreach($cardsToDelete as $removedCard) {
                    //actually deletes the deck			    
                    $this->Card->delete($removedCard,true);
                }
            }
            
            $deckId = $this->Deck->id;

            $tagList = $this->data['Tag']['tag'];
            $newTagArray = array();
            $deckTagArray = array();
           
            //needed for proper indexing, resets the searchable behavior to work with tags now instead of decks
            $this->Tag->Behaviors->attach('Searchable');
            
            $tagListArray = explode(" ", $tagList);
            $tagListArrayLength = count($tagListArray);
            for($tagIndex = 0; $tagIndex < $tagListArrayLength; $tagIndex ++) {            
               
                $tag = trim($tagListArray[$tagIndex]);
                $tempTag = $this->Tag->find('first',array('conditions' => array('Tag.tag' => $tag)),array('fields' => 'Tag.id'));
                if(!empty($tag)) {
                    if($tempTag == null) { 
                        $newTagArray['Tag']['tag'] = $tag;
                        
                        $this->Tag->create();
                        $this->Tag->save($newTagArray, array('validate' => 'false'));

                        $deckTagArray['DeckTag'][$tagIndex]['tag_id'] = $this->Tag->id;
                        
                    }
                    else {                
                        $deckTagArray['DeckTag'][$tagIndex]['tag_id'] = $tempTag['Tag']['id']; 
                        
                    }                                                          
                    $deckTagArray['DeckTag'][$tagIndex]['deck_id'] = $deckId;                             
                }
            }
                               
            if($deckTagArray != null) {
                //finds newly created tags and tags to delete from deck
                if($edit) {
                    $tempDeckTagParams = array('conditions' =>  array('DeckTag.deck_id' => $deckId),'fields' => array('DeckTag.tag_id'));
                    $tempDeckTags = $this->DeckTag->find('list',$tempDeckTagParams);
                    
                    $deckTagCount = count($deckTagArray['DeckTag']);
                    for($i = 0; $i < $deckTagCount; $i++) {
                        
                        foreach($tempDeckTags as $deckTagId => $tagId){
                            
                            if($deckTagArray['DeckTag'][$i]['tag_id'] == $tagId) {
                                
                                unset($deckTagArray['DeckTag'][$i]);
                                unset($tempDeckTags[$deckTagId]);
                                
                                //breaks out of foreach after match is found
                                break;
                            }
                        }
                    }
                    foreach($tempDeckTags as $deckTagId => $tagId){
                        //actually deletes the deck			    
                        $this->DeckTag->delete($deckTagId, false);
                    }
                
                }
                //saves the deck
                if(count($deckTagArray['DeckTag']) > 0) {
                    $this->DeckTag->saveAll($deckTagArray['DeckTag']);
                }
                
                
            }
            
            //if newly created deck add to mydecks 
            if(!$edit) {    
                $this->data['MyDeck']['deck_id'] = $deckId;
                $this->data['MyDeck']['user_id'] = $userId;
                $this->MyDeck->save($this->data); 
            }

            $this->redirect(array('controller'=>'decks','action'=>'info',$deckId));
                
        }
    
    }

    /*
     * Called when writeSession fails.
     *
     */
    function failure() {

    }

    function uploadCSV(){
    	    
    //disable need for a view		
    $this->autoRender=false;

	
	$file = new File($this->data['Deck']['csv_file']['tmp_name']);
	
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
                            'fields' => array('Card.question', 'Card.answer','Card.card_order'));
        $this->set('deck', $this->Card->find('all',$findParams));
    }

}

?>
