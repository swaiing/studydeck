<?php
    // Set default homepage title
    $this->pageTitle = "Studydeck | The Best Way to Study GRE Vocabulary Online";

    // Javascript/CSS includes
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('jquery.nivo.slider.pack');
    echo $html->css('nivo-slider',null,null,false);
    echo $html->css('http://fonts.googleapis.com/css?family=Droid+Sans',null,null,false);
?>
<div id="top_stuff">
    <div id="big_important">

        <div id="boxwrap_screen">
            <div id="slider">
                <img src="/img/slider_quiz.png"/>
                <img src="/img/slider_learn.png"/>
                <img src="/img/slider_categorize.png"/>
                <img src="/img/slider_create.png"/>

                <div class="nivo-directionNav" style="display:none;">
                    <a class="nivo-prevNav">Prev</a>
                    <a class="nivo-nextNav">Next</a>
                </div>
            </div>
        </div>

        <div id="boxwrap_text">
            <h1>Study GRE Word Lists Online</h1>
            <p>A studying system designed to help you learn the vocabulary necessary to get a top score on the GRE.  Flash cards with premium GRE word lists.  Dead-simple quiz and review system.</p>
            <div id="pricing_button">
                <a href="/gre/lists"><img src="/img/see_pricing.png" alt="See Pricing"/></a>
            </div>
        </div>

    </div>
</div>

<div id="panels">
    <div class="rect_box left_box">
        <h2><a href="/features/categorize">Categorize by Difficulty</a></h2>
        <p>Group cards into Easy, Medium and Hard, so you only study the words you don't know.</p>
    </div>
    <div class="rect_box">
        <h2><a href="/features/quiz">Quiz Yourself</a></h2>
        <p>Test your knowledge using our simple, effective interface.</p>
    </div>
    <div class="rect_box left_box">
        <h2><a href="/features/track">Track Your Progress</a></h2>
        <p>Studydeck records your quiz history so you can work towards your goals.
    </div>
    <div class="rect_box">
        <h2><a href="/features/create">Create Your Own</a></h2>
        <p>Free with the purchase of any premium deck, we allow you to create as many custom Studydecks as you want on any topic.</p>
    </div>
</div>

<script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider({
            effect: 'fade',
            pauseTime: 7000,
	        startSlide:0,
            directionNav:true,
            directionNavHide:true,
            controlNav:false,
            pauseOnHover:true
        });
    });
</script>
