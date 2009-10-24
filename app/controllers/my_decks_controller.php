<?php
class MyDecksController extends AppController{
      var $name = 'MyDecks';
      var $scaffold;
      var $helpers = array('Javascript');
      var $components = array('Auth','RequestHandler');

      function delete(){

      	        $this->autoRender=false;
      	       if($this->RequestHandler->isAjax()){
      	       		$myDeckToRemove = $this->MyDeck->find('first', array('conditions' => array('AND'=> array('MyDeck.deck_id' => $this->params['form']['id']),array('MyDeck.user_id' => $this->Auth->user('id')))));

			$this->MyDeck->delete($myDeckToRemove['MyDeck']['id'], false);
	       }
	      
	      
      }
}     

?>