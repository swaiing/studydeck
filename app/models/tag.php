<?php
class Tag extends AppModel {
    var $name = 'Tag';
    var $hasMany = 'DeckTag';
    //var $actsAs = array ('Searchable');
    //var $uses = array('Search_Index');
    var $validate = array(
        'tag' => array(
            'rule' => array('maxLength', 127),
            'message' => 'Tags must be no larger than 127 characters long.'
        )
    );
    /*
    function afterSave($created) {
        //$this->log("[" . get_class($this) . "-> afterSave] ". $created, LOG_DEBUG);
        if($created){
            $this->log("[" . get_class($this) . "-> afterSave] ".$this->data['Tag']['tag'], LOG_DEBUG);
        }


    
    
    }
    
    function indexData() {
        $index = $this->data['Tag']['tag'];
        return $index;
    
    }
    */
      
}
?>