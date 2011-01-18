<?php 
include 'sd_global.php';
require_once "constants.php";


class AdminsController extends AppController {
	var $name = 'Admins';
	var $components =array('Auth');
	var $helpers = array('Html','Javascript','RelativeTime');
	var $uses = array('User','MyDeck','Deck','Rating','TempUser','Card','Product','Payment','PurchasedProduct');


	function beforeFilter() {
      
        // Call AppConroller::beforeFilter()
    	parent::beforeFilter();
		$this->_isAdmin();
        
    } 

    function panel() {

    }
     
	

}
?>
