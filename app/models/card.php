<?php
class Card extends AppModel {
    var $name = 'Card';
    var $belongsTo = array('Deck');
    var $hasMany = array(
        'Rating' => array(
            'fields' => array('rating')
        ),
        'Result' => array(
            'fields' => array('last_guess','total_correct','total_incorrect')
        )
    );
}
?>
