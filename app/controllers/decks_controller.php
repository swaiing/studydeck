<?php

include 'sd_global.php';

class DecksController extends AppController {

    var $name = 'Decks';
    var $scaffold;
    var $uses = array('Deck','Card','Tag','MyDeck','DeckTag','Rating','Result');
    var $helpers = array('Html','Javascript');
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

    function explore($sortBy = null,$page = null,$query = null) {

        
	    //sets sort variable for view   
	    $this->set('sort', $sortBy);

      	//sets sql sort based on sort parameter       
      	if($sortBy == 'recent'){
	        $sortBy = 'Deck.created DESC';
	    }
	    elseif ($sortBy == 'popular') {
	        $sortBy = 'Deck.view_count DESC';
	    }
	    elseif ($sortBy == 'alphabetical'){
	        $sortBy = 'Deck.deck_name ASC';
	    }
	    else {
	        $this->set('sort', 'recent');
		    $sortBy = 'Deck.created DESC';
	    }
	    //sets $page variable to 1 in none specified
	       if ($page == null){
 	       	      $page = 1;
	       }
	       
	       $queryString = $this->data['Deck']['searchQuery'];
	       //set querystring for query equal to the query parameter
	       if ($query != null){
	       	  $queryString = $query;
	       }
	       //declares a null exploredecks array which holds the Decks to display on the page   
	       $exploreDecks = array();
	       //pulls first 20 decks of proper order if no query given
	       if($queryString == null){
	       		$exploreDecks = $this->Deck->find('all',array('conditions'=> array('Deck.privacy'=> SD_Global::$PUBLIC_DECK),'limit' => 20,'page' => $page,'order'=> $sortBy));
	       		$this->set('decks',$exploreDecks);
			//sets the pages count, 20 results per page
	       		$this->set('pages', ceil(count($exploreDecks)/20));
	       }
	       else {
			//queries the decks
			$deckQuery = $this->Deck->search($queryString);
			
			$arrayOfDeckIds = array();
			//loops through decks and saves the deck ids
			foreach ($deckQuery as $result){
			    array_push($arrayOfDeckIds, $result['Deck']['id']);
			    
			}

			$arrayOfTagIds = array();
			//queries the tags
			$tagQuery = $this->Tag->search($queryString);
			//pulls all the tag ids that are found
			foreach($tagQuery as $tagResult){
				array_push($arrayOfTagIds, $tagResult['Tag']['id']);
			}
			//pulls all the deckTag relations for the given tag ids
			$arrayOfDeckTags = $this->DeckTag->find('all',array('conditions'=> array('DeckTag.tag_id' => $arrayOfTagIds)));
			//gets the deck ids and adds them to array
			foreach ($arrayOfDeckTags as $deckTags){
				array_push($arrayOfDeckIds, $deckTags['DeckTag']['deck_id']);
			}
			
			//takes all the deck ids gathered pulls the decks
			$exploreDecks = $this->Deck->find('all',array('limit' => 20,'page' => $page,'conditions'=> array('Deck.id' => $arrayOfDeckIds,'Deck.privacy' => SD_Global::$PUBLIC_DECK),'order'=> $sortBy));
			$this->set('decks',$exploreDecks); 
	       		$this->set('pages', ceil(count($exploreDecks)/20));
			
	       }
	       
	       $tagArray = array();
	       //goes through all the decks and retrieves the tags
	       //adds all the tags to an array with the tag id as the index
	       foreach($exploreDecks as $eDeck){
	       	     $tempTagArray = $eDeck['DeckTag'];
		     
		     foreach ($tempTagArray as $tempTag){
		     	     $tempTagId = $tempTag['tag_id'];
			     if(! isset($tagArray[$tempTagId])){
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

    function study($id = null)
    {
        // Set deck recursion to 0, so that deck associations aren't traversed
        $this->Deck->recursive = 0;    
        $this->Card->recursive = 1;

        // Set deck meta info
        $this->Deck->id = $id;
        $this->set('deckInfo', $this->Deck->read());

        // Retrieve cards in deck by deck_id
        $findCardsParams = array(
                            'conditions' => array('Card.deck_id' => $this->Deck->id),
                            'fields' => array('Card.question','Card.answer'));
        $this->set('deck', $this->Card->find('all',$findCardsParams));

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
        $url = $this->params['url']['url'];
        $tempStr = "url: " . $url . "<br/>cardId: " . $cardId . "<br/>ratingId: " . $ratingId . "<br/>resultId: " . $resultId . "<br/> rating: " . $rating . "<br/> correct: " . $correct . "\n";

        // Set primary key of card model
        $this->Card->id = $cardId;

        /**
         * Update rating model
         * Create new/Update rating record
         *
         */
        // Validate $rating is easy (1), medium (2), hard(3)
        if(preg_match("/[1-3]/",$rating)) {

            // Create new record if ratingId is null
            if(strcmp($ratingId,SD_Global::$NULL_STR) == 0) {
                $this->Card->Rating->create(array('card_id'=>$cardId,'user_id'=>$userId));
            }
            else {
                $this->Card->Rating->id = $ratingId;
            }
            $this->Card->Rating->set('rating',$rating);
            $this->Card->Rating->save();
        }

        /**
         * Update result model
         * Create new/Update result record
         *
         */
        // Validate $correct is 1 or 0
        if(preg_match("/[0|1]/",$correct)) {

            // Create new record if resultId is null
            if(strcmp($resultId,SD_Global::$NULL_STR) == 0) {
                $this->Card->Result->create(array('card_id'=>$cardId,'user_id'=>$userId));

                // Set counter
                if($correct) {
                    $this->Card->Result->set('total_correct',1);
                }
                else {
                    $this->Card->Result->set('total_incorrect',1);
                }

            }
            else {
                $this->Card->Result->id = $resultId;

                // Retrieve totals to update
                if($correct) {
                    $totalCorrect = $this->Card->Result->total_correct + 1;
                    $this->Card->Result->set('total_correct',$totalCorrect);
                }
                else {
                    $totalIncorrect = $this->Card->Result->total_incorrect + 1;
                    $this->Card->Result->set('total_incorrect',$totalIncorrect);
                }

            }
            $this->Card->Result->set('last_guess',$correct);
            $this->Card->Result->save();
        }
    
        // Send a response back
        $this->set('response', $tempStr);
    }

    
    function delete(){
			
     	$this->autoRender=false;
      	if($this->RequestHandler->isAjax()){
      	       $deckToRemove = $this->Deck->find('first', array('conditions' =>  array('Deck.id' => $this->params['form']['id'])));
	
		if($deckToRemove['Deck']['user_id'] == $this->Auth->user('id')){
			$this->Session->setFlash("go this ".$deckToRemove['Deck']['id']);	
			$this->Deck->delete($this->params['form']['id'],true);
		}
	}
	      
	      
    }
}

?>
