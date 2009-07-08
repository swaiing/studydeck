<?php
class DecksController extends AppController {
      var $name = 'Decks';
      function index(){
      	       $this->set('decks', $this->Deck->find('all'));
      }
      function add() {
      	       if (!empty($this->data)){
	       	  if($this->Deck->save($this->data)){
		  $this->Session->setFlash('Your deck has been saved');
		  $this->redirect(array('action'=> 'index'));				  
		}
	       }
      }
      function test(){
      	  $this->set('decks', $this->Deck->find('all'));
      } 
     

}

?>