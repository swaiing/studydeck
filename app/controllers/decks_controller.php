<?php
class DecksController extends AppController {

    var $name = 'Decks';
    var $scaffold;
    var $uses = array('Deck','Card','Tag','MyDeck','DeckTag');
    var $helpers = array('Html','Javascript');
    var $components = array('Auth');


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

        $this->DeckTag->recursive = 1;
	       
	    $this->set('sort', $sortBy);
      	       
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

	       if ($page == null){
 	       	      $page = 1;
	       }
	       $queryString = $this->data['Deck']['searchQuery'];
	       if ($query != null){
	       	  $queryString = $query;
	       }
	          
	       $exploreDecks = array();

	       if($queryString == null){
	       		$exploreDecks = $this->Deck->find('all',array('conditions'=> array('Deck.privacy'=> 2),'limit' => 20,'page' => $page,'order'=> $sortBy));
	       		$this->set('decks',$exploreDecks);
	       		$this->set('pages', ceil(count($exploreDecks)/20));
	       }
	       else {
			$deckQuery = $this->Deck->search($queryString);
			
			$arrayOfDeckIds = array();
			foreach ($deckQuery as $result){
			    array_push($arrayOfDeckIds, $result['Deck']['id']);
			    
			}

			$arrayOfTagIds = array();
			$tagQuery = $this->Tag->search($queryString);
			foreach($tagQuery as $tagResult){
				array_push($arrayOfTagIds, $tagResult['Tag']['id']);
			}
			$arrayOfDeckTags = $this->DeckTag->find('all',array('conditions'=> array('DeckTag.tag_id' => $arrayOfTagIds)));
			foreach ($arrayOfDeckTags as $deckTags){
				array_push($arrayOfDeckIds, $deckTags['DeckTag']['deck_id']);
			}
			
			$exploreDecks = $this->Deck->find('all',array('limit' => 20,'page' => $page,'conditions'=> array('Deck.id' => $arrayOfDeckIds,'Deck.privacy' => 2),'order'=> $sortBy));
			$this->set('decks',$exploreDecks); 
	       		$this->set('pages', ceil(count($exploreDecks)/20));
			
	       }
	       
	       $tagArray = array();
	       foreach($exploreDecks as $eDeck){
	       	     $tempTagArray = $eDeck['DeckTag'];
		     
		     foreach ($tempTagArray as $tempTag){
		     	     $tempTagId = $tempTag['tag_id'];
			     if(! isset($tagArray[$tempTagId])){
			     	$tag =$this->Tag->find('first',array('conditions'=> array('Tag.id' => $tempTagId)));
				//$this->set('temp', $tag);
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
        // Set deck meta info
        $this->Deck->id = $id;
        $this->set('deckInfo', $this->Deck->read());

        // Retrieve cards in deck by deck_id
        $findParams = array(
                            'conditions' => array('Card.deck_id' => $this->Deck->id),
                            'fields' => array('Card.question', 'Card.answer'));
        $this->set('deck', $this->Card->find('all',$findParams));
    }

      function delete($deckId = null){
        if($deckId != null){
            $this->Deck->delete($deckId, false);
         }
         $this->autoRender=false;
         $this->redirect('/users/dashboard',null,true);
      }
}

?>
