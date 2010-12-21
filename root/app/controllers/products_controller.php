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

        // Query products
        $products = $this->Product->find('all');

        // Find products which are already purchased
        $userId = $this->Auth->user('id');

        // Bind to view
        $this->set('allProducts', $products);
    }

    // Forwards to Order Summary page
    function confirmOrder() {

        $LOG_PREFIX = "[" . get_class($this) . "->" . __FUNCTION__ . "] ";
        $this->log($LOG_PREFIX . "Confirming order now");

        $this->set('foo', $this->data);

        // Iterate purchased deck IDs

        // If logged in user, send directly to Paypal

        // If user is new, send to user registration/order confirmation page

    }

}
