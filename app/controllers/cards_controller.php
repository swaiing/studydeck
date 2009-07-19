<?php
class CardsController extends AppController {
      var $name = 'Cards';
      var $uses = array('Card','Deck','Tag','MyDeck','DeckTag');
      var $helpers = array('Html','Javascript');

      function index(){
      	       $this->set('cards', $this->Card->find('all'));
      }
      function addDeckPlusCards(){
      	       $this->pageTitle = 'Create and Edit Decks!';
	       $this->layout='create_edit';
	       
	       //creates tag dropdown
	      // $tag_array = $this->Tag->find('list',array('fields'=>'Tag.tag'));
	      // array_push($tag_array, "---Select Category---");
	      // sort($tag_array);
	      // $this->set('tagdata',$tag_array);

		 if(!empty($this->data)){
			//save the deck
			$deck = $this->Deck->save($this->data);
			//moves forward if the deck did save
			if(!empty($deck)){
			//pulls tag value from form
			$tag_value = $this->data['DeckTag']['tag_id'];
			//$tag_value = $this->DeckTag->tag_id;
			//$this->Session->setFlash($this->processCSV("test.tmp"));
			//finds if the tag if it  exists
			$tempTag = $this->Tag->find('first',array('conditions' => array('Tag.tag' => $tag_value)),array('fields' => 'Tag.id'));
			//does action based on whether a new tag or not
			if($tempTag != NULL){
			//if tag exists sets the decktag.tag_id to the tags id
			$this->data['DeckTag']['tag_id'] =$tempTag['Tag']['id'];
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
	       


	       }      


      }
      function processCSV()
      {
	if(!empty($this->data) && is_uploaded_file($this->data['Card']['CSVFile']['tmp_name'])){
		$myFileInfo = $this->data['Card']['CSVFile'];	       
		$this->set('fname', $myFileInfo['name']);
		$this->set('fsize', $myFileInfo['size']);
		$this->Session->setFlash($myFileInfo['tmp_name']);
		$myFile = $myFileInfo['tmp_name'];
		$fh = fopen($myFile,'r');
		//$fdata= fread($fh,filesize($myFile));
		$this->set('csvArray', fgetcsv($fh));
		fclose($fh);
		
	}
		
      }


}


?>