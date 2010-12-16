<?php

// Contains global constants
include 'sd_global.php';

class ProductsController extends AppController {

	var $name = 'Products';
    var $scaffold;
    var $uses = array('Product','Deck','MyDeck');
    var $helpers = array('Html','Javascript','Form');
    var $components = array('Auth');

    // Method called on whenever controller is executed
    function beforeFilter()
    {
        // Call AppController::beforeFilter()
        parent::beforeFilter();

        // Set Auth support
        $this->Auth->allow('view');
        $this->Auth->authError = "You must login or buy a Studydeck to continue";
    }

    // Action fired for display of products in the Store
    function view() {

        $this->Deck->recursive = -1;
        $this->Deck->MyDeck->recursive = -1;
        $this->Product->recursive = -1;
        $userId = $this->Auth->user('id');

        // Query products
        $products = $this->Product->find('all');
        $productDeckIdsMap = array();
        foreach ($products as $product) {
            $dId = $product['Product']['deck_id'];
            $productDeckIdsMap[$dId] = true;
        }
        $this->set('allProducts', $products);

        // Do not continue if NOT logged in
        if (!isset($userId)) {
            return;
        }

        // User is logged in
        // Find Product decks which are already associated with user
        $productDecksOwned = array();
        $params = array('conditions' => array('MyDeck.user_id' => $userId));
        $myDecks = $this->MyDeck->find ('all', $params);
        foreach ($myDecks as $deck) {
            $deckId = $deck['MyDeck']['deck_id'];
            if (array_key_exists($deckId, $productDeckIdsMap) && $productDeckIdsMap[$deckId]) {
                $productDecksOwned[$deckId] = true;
            }
        }
        $this->set('productsOwned', $productDecksOwned);
    }

}
