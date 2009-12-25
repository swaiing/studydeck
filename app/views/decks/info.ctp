<!-- File: /app/views/decks/info.ctp -->

<?php
    //echo $javascript->link('jquery-1.2.6.min',false) . "\n";
    //echo $javascript->link('deck_study',false) . "\n";;
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $html->css('deck_info',null,null,false) . "\n";
?>

<div id="middle_wrapper_content">
<div id="middle_bar">

<div id="heading">
    <h1><?php echo $deckData['Deck']['deck_name']; ?></h1>
    <p><?php echo $deckData['Deck']['description']; ?></p>
</div>

<div id="middle">
    <div id="category_select">
        <p>1. Select category:</p>
        <input type="checkbox" id="all_chkbox" value="all" />
        <label for="all_chkbox">All</label>
        <input type="checkbox" id="easy_chkbox" value="easy" />
        <label for="easy_chkbox">Easy</label>
        <input type="checkbox" id="medium_chkbox" value="medium" />
        <label for="medium_chkbox">Medium</label>
        <input type="checkbox" id="hard_chkbox" value="hard" />
        <label for="hard_chkbox">Hard</label>
        <input type="checkbox" id="unrated_chkbox" value="unrated" />
        <label for="unrated_chkbox">Unrated</label>
    </div>
    <div id="mode_select">
        <p>2. Select mode:</p>
        <input type="button" value="Learn" />
        <input type="button" value="Quiz" />
    </div>
    <div class="clear_div">&nbsp;</div>
</div>

<div id="bottom">
    <h3>table goes here</h3>
</div>

<?php
    //print_r($deckData);
?>
</div>
</div>
