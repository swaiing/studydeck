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

            // add user id into deck
            $this->data['Deck']['user_id']= $this->Auth->user('id');

            //save the deck
            $deck = $this->Deck->save($this->data);

            //moves forward if the deck did save
            if(!empty($deck)){

                //pulls tag value from form
                $tag_value = $this->data['DeckTag']['tag_id'];
                //$tag_value = $this->DeckTag->tag_id;
			
                //finds if the tag if it  exists
                $tempTag = $this->Tag->find('first',array('conditions' => array('Tag.tag' => $tag_value)),array('fields' => 'Tag.id'));
                //does action based on whether a new tag or not

                if($tempTag != NULL){

                    //if tag exists sets the decktag.tag_id to the tags id
                    $this->data['DeckTag']['tag_id'] = $tempTag['Tag']['id'];
                }
                else {
                    // if tag doesn't exist save it to the tag table as a new entry
                    $newTag['Tag']['tag'] = $tag_value;
                    $this->Tag->save($newTag);
                    //sets new tag entry id to decktag.tag_id
                    $this->data['DeckTag']['tag_id'] = $this->Tag->id;
                }

                $theDeckId = $this->Deck->id;
                //set decktag.deck_id to id of newly created deck

                $this->data['DeckTag']['deck_id'] = $theDeckId;
                //saves to decktag table

                $decktag = $this->DeckTag->save($this->data);
            }       

			if(!empty($deck)){

                //finds the number of cards being entered
                $num = count($this->data['Card']);

                //traverses the cards being entered and sets their deck id to the new deck id
                for($x = 0; $x < $num; $x ++){
                    $this->data['Card'][$x]['deck_id'] = $theDeckId;
                }
                //saves all the cards
                $this->Card->saveAll($this->data['Card'], array('validate' => 'first'));
			}
			
			if(!empty($deck)){
				$this->data['MyDeck']['deck_id']=$theDeckId;
				$this->data['MyDeck']['user_id']= $this->data['Deck']['user_id'];
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
   
   

    function study($id = null)
    {
        // Prevent Deck associations from recursing
        $this->Deck->recursive = -1;
        $this->Deck->Card->recursive = -1;
        $this->Deck->Card->Rating->recursive = -1;
        $this->Deck->Card->Results->recursive = -1;

        // Set $deckRecord
        $this->Deck->id = $id;
        $deckRecord = $this->Deck->read();

        // Set user id
        $userId = $this->Auth->user('id');

        // Retrieve Card model data for deck
        $cardsParams = array(
                            'conditions' => array('Card.deck_id' => $this->Deck->id),
                            'fields' => array('Card.id','Card.question','Card.answer')
        );
        $cardRecords = $this->Deck->Card->find('all',$cardsParams);

        // Store card IDs in array for use ratings/results queries
        $cardIds = array();
        foreach($cardRecords as $card) {
           array_push($cardIds,$card['Card']['id']); 
        }

        // Retrieve ratings by card_id and user_id
        $ratingParams = array(
                            'conditions' => array('Rating.user_id' => $userId,
                                                  'Rating.card_id' => $cardIds),
                            'fields' => array('Rating.id','Rating.card_id','Rating.rating')
        );
        $ratingRecords = $this->Deck->Card->Rating->find('all',$ratingParams);

        // Index ratings by card_id
        $ratingMap = array();
        foreach($ratingRecords as $rating) {
           $ratingMap[$rating['Rating']['card_id']] = array('id' => $rating['Rating']['id'],
                                                            'rating' => $rating['Rating']['rating']);
                                                            
        }

        // Retrieve results by card_id and user_id
        $resultParams = array(
                            'conditions' => array('Result.user_id' => $userId,
                                                  'Result.card_id' => $cardIds),
                            'fields' => array('Result.id','Result.card_id','Result.last_guess',
                                              'Result.total_correct','Result.total_incorrect')
        );
        $resultRecords = $this->Deck->Card->Result->find('all',$resultParams);

        // Index results by card_id
        $resultMap = array();
        foreach($resultRecords as $result) {
           $resultMap[$result['Result']['card_id']] = array('id' => $result['Result']['id'],
                                                            'last_guess' => $result['Result']['last_guess'],
                                                            'total_correct' => $result['Result']['total_correct'],
                                                            'total_incorrect' => $result['Result']['total_incorrect']);
        }

        // debug
        //$this->set('debug',$resultMap);
        //$this->set('debug',$ratingMap);

        // Set variables for view
        $this->set('deckData',$deckRecord);
        $this->set('cards',$cardRecords);
        $this->set('cardsRatings',$ratingMap);
        $this->set('cardsResults',$resultMap);
    }

    function quiz($id = null)
    {
      $this->study($id);
    }

    /*
     * Called using AJAX to update card/deck data
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
        $cardId = Sanitize::paranoid($this->params['url']['cid']);
        $ratingId = Sanitize::paranoid($this->params['url']['rid']);
        $resultId = Sanitize::paranoid($this->params['url']['sid']);
        $rating = Sanitize::paranoid($this->params['url']['rating']);
        $correct = Sanitize::paranoid($this->params['url']['correct']);

        // DEBUG
        //$url = $this->params['url']['url'];
        $tempStr = "cardId: " . $cardId . "<br/>ratingId: " . $ratingId . "<br/>resultId: " . $resultId . "<br/> rating: " . $rating . "<br/> correct: " . $correct . "\n";

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
