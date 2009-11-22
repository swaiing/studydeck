<?php
class TempUsersController extends AppController {
	var $name = 'TempUsers';
	var $scaffold;
	var $components = array('Auth');
	var $helpers = array('Time');

	function beforeFilter() {
      
        //list of actions that do not need authentication
		$this->Auth->allow('userCleanUp');
        
	
        
    }
      
	//this function will delete TempUser accounts which have not been activated
	function userCleanUp() {
		//disable need for view
		//$this->autoRender = false;
		
		//$findTemp = $this->TempUser->find('all',array('conditions' => array( 
		
		
		$checkTime = $time->gmt();
		$this->set('checkTime',$checkTime);
	}
      
}
?>