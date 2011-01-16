<?php

// AppController parent class
// Add code shared among all controllers here.
class AppController extends Controller {

    var $components = array('Auth');
	var $uses = array('PurchasedProduct');

    
    function beforeFilter() {
    	//$this->set('lastPage',$this->Session->read('Auth.redirect'));    
        // Allow index root to be displayed
        $this->Auth->allow('display');

        // Set $activeUser to the current logged-in user
        $this->set('activeUser', $this->Auth->user('username'));
    }
	
	//verifies that user has purchased a deck if not redirects them to store
	function _hasPurchased() {
		// Set user id
        $userId = $this->Auth->user('id');
		//if no products purchased then redirect
		if (!$this->PurchasedProduct->find('first', array('conditions' => array('PurchasedProduct.user_id' => $userId)))){
			$this->redirect(array('controller' => 'products' ,'action' => 'view'));
		}
	}
}

?>
