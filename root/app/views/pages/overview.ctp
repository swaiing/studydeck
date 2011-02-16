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
            <div id="girl_studying">
                <img src="/img/girl_study_gre.jpg" alt="Study for the GRE" />
            </div>
        </div>

        <div id="text_caption">
            <h1>Overview</h1>
            <p>Studydeck is a system that uses online flash cards to help you learn the challenging vocabulary necessary for a top score on the GRE.  We offer premium word lists that are compiled from the leading test preparation sources. Our clean and simple quiz and review system is the best way to study vocabulary for the GRE.</p>
        </div>

    </div>
</div>

<div id="panels_tabs">
    <ul>
        <li class="active"><a href="/features">Overview</a></li>
        <li><a href="/features/categorize">Categorize</a></li>
        <li><a href="/features/quiz">Quiz</a></li>
        <li><a href="/features/track">Track</a></li>
        <li><a href="/features/create">Create</a></li>
    </ul>
</div>
