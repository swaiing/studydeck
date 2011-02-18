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
            <img src="/img/feature_create.png" alt="Create Your Own Studydeck" />
        </div>

        <div id="text_caption">
            <h1>Create Your Own Studydeck</h1>
            <p>With the purchase of any premium deck you can create your own custom decks.  Create as many as you want on any subject.</p>
        </div>

    </div>
</div>

<div id="panels_tabs">
    <ul>
        <li><a href="/features">Overview</a></li>
        <li><a href="/features/categorize">Categorize</a></li>
        <li><a href="/features/quiz">Quiz</a></li>
        <li><a href="/features/track">Track</a></li>
        <li class="active"><a href="/features/create">Create</a></li>
    </ul>
</div>
