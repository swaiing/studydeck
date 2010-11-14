<?php

include 'sd_global.php';

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
		$this->autoRender = false;
		
		
		App::import('Helper','Time');
		$time = new TimeHelper();

		/**
		* calculates the offset based on days to keep
		* $time-> serverOffset return server GMT offset in seconds
		* it is converted to hours
		* days to keep is also converted to hours
		*/
		$findOffset = ((24 * SD_Global::$DAYS_TO_KEEP) - ($time->serverOffset()/3600));

		//converts to a sql time based on current time minus days to keep offset
		$queryTime = $time->format('Y-m-d H:i:s',$time->nice(),'', - $findOffset);

		//deletes all accounts older than the date to keep
		$this->TempUser->deleteAll(array('created <=' => $queryTime));

				
	}
      
}
?>