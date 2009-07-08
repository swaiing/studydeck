<?php
class TagsController extends AppController {
      var $name = 'Tags';
      var $scaffold;
    
      function add() {
      	       if (!empty($this->data)){
	       	  if($this->Tag->save($this->data)){
		  $this->Session->setFlash('Your Tags has been saved');
		  $this->redirect(array('action'=> 'index'));				  
		}
	       }
      }
      
     

}

?>