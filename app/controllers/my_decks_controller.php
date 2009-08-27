<?php
class MyDecksController extends AppController{
      var $name = 'MyDecks';
      var $scaffold;
      var $components = array('Auth');

      function delete($deckId = null){
      	       if($deckId != null){
      	       		   $myDeckToRemove = $this->MyDeck->find('first', array('conditions' => array('AND'=> array('MyDeck.deck_id' => $deckId),array('MyDeck.user_id' => $this->Auth->user('id')))));


	       
			$this->MyDeck->delete($myDeckToRemove['MyDeck']['id'], false);
	       }
	       $this->autoRender=false;
	       $this->redirect('/users/dashboard',null,true);
      }
}     

?>