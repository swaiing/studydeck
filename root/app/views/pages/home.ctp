<?php
    // Set default homepage title
    $this->pageTitle = "Studydeck | Online flashcards made simple";
?>
<div id="blurb">
    <div id="blurb_content"></div>
</div>
<div id="more">
    <div id="panels">
        <div id="get_started">
            <h3><?php echo $html->link('Get Started!', array('controller'=>'products', 'action'=>'view')); ?></h3>
        </div>
    </div>
</div>
<div id="more_bottom"></div>
