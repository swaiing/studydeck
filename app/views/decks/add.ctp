<h1>Add Deck</h1>
<?php
echo $form->create('Deck');
echo $form->input('deck_name');
echo $form->input('privacy');
echo $form->input('user_id');
echo $form->end('Save Post');
?>