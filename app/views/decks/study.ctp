<!-- File: /app/views/decks/study.ctp -->

<?php
    echo $javascript->link('jquery-1.2.6.min',false) . "\n";
    echo $javascript->link('deck_study',false) . "\n";;
    echo $html->css('deck_study',null,null,false) . "\n";
?>

<!-- Pass card data -->
<script type="text/javascript">
<?php
    echo "var deckData = " . $javascript->object($deckData) . ";\n";
    echo "var cardData = " . $javascript->object($cards) . ";\n";
    echo "var cardRatingsData = " . $javascript->object($cardsRatings) . ";\n";
    echo "var cardResultsData = " . $javascript->object($cardsResults) . ";\n";
?>
</script>

<!-- <pre><?php //print_r($debug); ?></pre> -->

<div id="middle_wrapper_content">

<h1 class="title"></h1>

<div id="left_margin_wrap">
    <div class="margin_box">
        <span class="title">Card Quiz History</span>
        <table class="quiz_history">
            <tr>
                <td># Times Correct</td>
                <td id="card_total_correct"></td>
            </tr>
            <tr>
                <td># Times Incorrect</td>
                <td id="card_total_incorrect"></td>
            </tr>
            <tr>
                <td>Last Answer</td>
                <td id="card_last_answer"></td>
            </tr>
        </table>
    </div>
    <div class="margin_box">
        <span class="title">Options</span>
        <label for="show_answer_checkbox">Show Answer?</label>
        <input type="checkbox" id="show_answer_checkbox" name="show_answer" value="show_answer" />
    </div>
</div>

<div id="right_margin_wrap">
</div>

<div id="card_wrap">

    <div id="row_top">
        <div id="prev_button" class="left_button">
            previous
            <!--<?php echo $html->image('arrow_left.png',array('alt'=>'Previous')); ?>-->
        </div>
        <div id="next_button" class="right_button">
            next
            <!--<?php echo $html->image('arrow_right.png',array('alt'=>'Next')); ?>-->
        </div>
        <div class="middle_button" id="card_rating"></div>
    </div> <!-- end row_top -->

    <div id="row_question">
        <span id="card_question"></span>
    </div>

    <div id="row_answer">
        <span id="card_answer"></span>
    </div>

    <div id="row_bottom">
        <div id="incorrect_button" class="left_button">incorrect</div>
        <div id="correct_button" class="right_button">correct</div>
        <div class="middle_button" id="deck_progress">5/7</div>
    </div> <!-- end row_bottom -->

</div> <!-- end #card_wrap -->

</div> <!-- end #middle_wrapper_content -->
