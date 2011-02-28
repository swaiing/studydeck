<?php
    // Set default homepage title
    $this->pageTitle = "The Best Way to Study GRE Word Lists Online - Studydeck";

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
                <a href="/features/quiz"><img src="/img/slider_quiz.png"/></a>
                <a href="/features/track"><img src="/img/slider_learn.png"/></a>
                <a href="/features/categorize"><img src="/img/slider_categorize.png"/></a>
                <a href="/features/create"><img src="/img/slider_create.png"/></a>

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
        <a href="/features/categorize">
            <img class="panel_icon" src="/img/categorize_icon.png" alt="Categorize" />
            <h2>Categorize by Difficulty</h2>
        </a>
        <p>Group cards into Easy, Medium and Hard, so you only study the words you don't know.</p>
    </div>
    <div class="rect_box">
        <a href="/features/quiz">
            <img class="panel_icon" src="/img/quiz_icon.png" alt="Quiz" />
            <h2>Quiz Yourself</h2>
        </a>
        <p>Test your knowledge using our simple and effective interface.</p>
    </div>
    <div class="rect_box left_box">
        <a href="/features/track">
            <img class="panel_icon" src="/img/track_icon.png" alt="Track" />
            <h2>Track Your Progress</h2>
        </a>
        <p>Studydeck records your quiz history so you can work towards your goals.
    </div>
    <div class="rect_box">
        <a href="/features/create">
            <img class="panel_icon" src="/img/create_icon.png" alt="Create" />
            <h2>Create Your Own</h2>
        </a>
        <p>Free with the purchase of any premium deck, create as many custom Studydecks as you want on any topic.</p>
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
