<?php
    // Set default homepage title
    $this->pageTitle = "Studydeck | Online flashcards made simple";
?>
<div id="blurb">
    <div id="blurb_content"></div>
</div>
<div id="more">
    <div id="panels">
        <div id="signup">
            <img src="/img/signup.png" alt="Sign Up" />
            <h3><?php echo $html->link('Sign Up',array('controller'=>'users','action'=>'register')) ?></h3>
            Sign up for a free account!
        </div>
        <div id="explore">
            <img src="/img/explore.png" alt="Explore" />
            <h3><?php echo $html->link('Explore Studydecks',array('controller'=>'decks','action'=>'explore')) ?></h3>
            Browse the library of available Studydecks 
        </div>
        <div id="iphone">
            <img src="/img/iphone_app.png" alt="iPhone App" />
            <h3>Mobile Studydeck</h3>
            Coming soon to the iPhone App Store!
        </div>
    </div>
</div>
<div id="more_bottom"></div>
