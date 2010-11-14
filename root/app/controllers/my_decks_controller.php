<?php
class MyDecksController extends AppController {
	var $name = 'MyDecks';
	var $scaffold;
	var $helpers = array('Javascript');
    var $components = array('Auth','RequestHandler');
	  

	//deletes a mydeck association via AJAX request	
	function delete() {

		//disable need for view
		$this->autoRender=false;
		//checks if this an ajax request
	    if($this->RequestHandler->isAjax()) {

			$myDeckToRemoveParams = array('conditions' => array('AND'=> array('MyDeck.deck_id' => $this->params['form']['id']),array('MyDeck.user_id' => $this->Auth->user('id'))));
      		$myDeckToRemove = $this->MyDeck->find('first',$myDeckToRemoveParams);
			
			$this->MyDeck->delete($myDeckToRemove['MyDeck']['id'], false);
	    }
	      
	      
	}
}     

?>