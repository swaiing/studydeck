<h1>Add Deck and cards</h1>

<?php
echo $javascript->link('createdeck');
echo $form->create('Card', array('action' => 'addDeckPlusCards'));
echo $form->input('Deck.deck_name',array('label'=>'Title'));
echo $form->input('DeckTag.tag_id',array('label' => 'Category'));
echo $form->input('Deck.privacy',array('label'=>'Type'));
echo $form->input('Deck.description');
echo $form->input('Deck.user_id');
echo $form->input('Card.0.question');
echo $form->input('Card.0.answer');
echo $form->input('Card.1.question');
echo $form->input('Card.1.answer');
echo $form->input('Card.2.question');
echo $form->input('Card.2.answer');
echo $form->input('Card.3.question');
echo $form->input('Card.3.answer');
echo $form->input('Card.4.question');
echo $form->input('Card.4.answer');
echo $form->button('Add a Card',array('onClick'=>'addCardRows(1)'));
echo $form->button('Add 5 Cards',array('onClick'=>'addCardRows(5)'));
echo $form->button('Add 10 Cards',array('onClick'=>'addCardRows(10)'));
echo $form->end('Save Deck');
?>

