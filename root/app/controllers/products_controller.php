<?php

// Contains global constants
include 'sd_global.php';

class ProductsController extends AppController {

	var $name = 'Products';
    //var $scaffold;
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

        // Set page title
        $this->pageTitle = SD_GLOBAL::$PAGE_TITLE_PRODUCTS;

        // Query products
        $products = $this->Product->find('all');
        $productDeckIdsMap = array();
        $productDeckIdsArray = array();
        foreach ($products as $product) {
            $dId = $product['Product']['deck_id'];
            $productDeckIdsMap[$dId] = true;
            array_push($productDeckIdsArray, $dId);
        }
        $this->set('allProducts', $products);

        // Get deck descriptions
        $descParams = array('conditions' => array('Deck.id' => $productDeckIdsArray),
                            'fields' => array('Deck.id', 'Deck.description', 'Deck.deck_name'));
        $descriptions = $this->Deck->find('all', $descParams);
        $descDeckIdsMap = array();
        foreach ($descriptions as $desc) {
            $dId = $desc['Deck']['id'];
            $descDeckIdsMap[$dId] = $desc;
        }
        $this->set('descriptions', $descDeckIdsMap);

        // Do not continue if NOT logged in
        if (!isset($userId)) {
            return;
        }
        $this->set('userLoggedIn', true);

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
