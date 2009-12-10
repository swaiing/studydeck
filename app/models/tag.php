<?php
class Tag extends AppModel {
    var $name = 'Tag';
    var $hasMany = 'DeckTag';
    //var $actsAs = array ('Searchable');
    var $validate = array(
        'tag' => array(
            'rule' => array('maxLength', 127),
            'message' => 'Tags must be no larger than 127 characters long.'
        )
    );

      
}
?>