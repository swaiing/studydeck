<?php
    // Set page title
    $this->pageTitle = "Studydeck | The Best Way to Study GRE Vocabulary Online";

    // Javascript/CSS includes
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $html->css('http://fonts.googleapis.com/css?family=Droid+Sans',null,null,false);
    echo $html->css('features',null,null,false);
?>

<div id="top_stuff">
    <div id="feature_wrap">

        <h1>The Basics</h1>
        <div id="screenshot_wrap">
            <img src="/img/feature_quiz.png" alt="Quiz Mode" />
        </div>

        <div id="text_caption">
            <p>A studying system designed to help you learn the vocabulary necessary to get a top score on the GRE.  Flash cards with premium GRE word lists.  Dead-simple quiz and review system.</p>
        </div>

    </div>
</div>

<div id="panels_tabs">
    <ul>
        <li class="active"><a href="/features">Basics</a></li>
        <li><a href="/features/categorize">Categorize</a></li>
        <li><a href="/feautures/quiz">Quiz</a></li>
        <li><a href="/features/track">Track</a></li>
        <li><a href="features/create">Create</a></li>
    </ul>
</div>
