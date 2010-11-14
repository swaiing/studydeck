<?php
class TagsController extends AppController {
    var $name = 'Tags';
    var $helpers = array('Html','Javascript','Form','Ajax');
    var $uses = array('Tag','SearchIndex');
    var $scaffold;
    
    
    function add() {
    	if (!empty($this->data)){
	       	if($this->Tag->save($this->data)){
                $this->Session->setFlash('Your Tags has been saved');
                $this->redirect(array('action'=> 'index'));				  
            }
	    }
    }
    
    function autoComplete() {
        //Partial strings will come from the autocomplete field as
        //$this->data['Tag']['Tag']
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        
        //query to retrieve the autocomplete values
        $tagFields = array('tag');
        $tagConditions = array('Tag.tag LIKE' => $this->params['url']['q'].'%');
        $tagParams = array('conditions' => $tagConditions,'fields' => $tagFields);
        
        
        $this->set('tags', $this->Tag->find('all', $tagParams));
        
    }
    
    function sTag($tag = null) {
        //disable need for a view	
        $tag = array();
        $tag['Tag']['tag'] = "gi22";
     	$this->autoRender=false;
        //$this->SearchIndex->create();
        //$this->Tag->Setup();
        $this->Tag->create();
        $this->Tag->save($tag, array('validate' => 'false'));
        
        return $this->Tag->id; 
    }

     

}

?>