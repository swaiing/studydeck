<?php

// AppController parent class
// Add code shared among all controllers here.
class AppController extends Controller {

    var $components = array('Auth');
    
    function beforeFilter() {

        // Allow index root to be displayed
        $this->Auth->allow('display');

        // Set $activeUser to the current logged-in user
        $this->set('activeUser', $this->Auth->user('username'));
    }
}

?>