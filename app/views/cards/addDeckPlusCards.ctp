<h1>Add Deck and cards</h1>
<?php
echo $form->create('Card');
echo $form->input('Deck.deck_name');
echo $form->input('Deck.privacy');
echo $form->input('Deck.user_id');
echo $form->input('question');
echo $form->input('answer');
echo $form->end('Save Post');
?>