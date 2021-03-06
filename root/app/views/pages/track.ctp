<?php
    // Set page title
    $this->pageTitle = "The Best Way to Study GRE Word Lists Online - Studydeck";

    // Javascript/CSS includes
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $html->css('http://fonts.googleapis.com/css?family=Droid+Sans',null,null,false);
    echo $html->css('features',null,null,false);
?>

<div id="top_stuff">
    <div id="feature_wrap">

        <div id="screenshot_wrap">
            <img src="/img/feature_track.png" alt="Track Your Progress" />
        </div>

        <div id="text_caption">
            <h1>Track Your Progress</h1>
            <p>Studydeck records your quiz results and categorizations so that you can track your progress.</p>
            <p class="pricing"><a href="/gre/lists">Buy Now</a></p>
        </div>

    </div>
</div>

<div id="panels_tabs">
    <ul>
        <li><a href="/features">Overview</a></li>
        <li><a href="/features/categorize">Categorize</a></li>
        <li><a href="/features/quiz">Quiz</a></li>
        <li class="active"><a href="/features/track">Track</a></li>
        <li><a href="/features/create">Create</a></li>
    </ul>
</div>
