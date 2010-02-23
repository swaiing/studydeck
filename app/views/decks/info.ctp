<!-- File: /app/views/decks/info.ctp -->
<?php
    // Javascript/CSS includes
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js',false);
    echo $javascript->link('jquery.corner',false);
    echo $javascript->link('deck_class',false);
    echo $javascript->link('deck_rts_wdg',false);
    echo $javascript->link('deck_info',false);
    echo $html->css('deck_info',null,null,false);
    echo $html->css('deck_rts_wdg',null,null,false);
    echo $html->css('jquery-ui-1.7.2.custom.gray',null,null,false);

    // Page global constants
    $EASY = 1;
    $MEDIUM = 2;
    $HARD = 3;
    $TOTAL = 99;
    $RATING_MAP = array(1 => 'easy', 2 => 'medium', 3 => 'hard');
    $DEFAULT_RATING = 3;

    // Set for display
    $deckName = $deckData['Deck']['deck_name'];
    $deckDesc = $deckData['Deck']['description'];

    // Labels for checkboxes
    $easyLabel = "Easy";
    $mediumLabel = "Medium";
    $hardLabel = "Hard";

?>

<script type="text/javascript">
<?php
    echo "var deckData = " . $javascript->object($deckData) . ";\n";
    echo "var cardData = " . $javascript->object($cards) . ";\n";
    echo "var cardResultsData = " . $javascript->object($cardsResults) . ";\n";
?>
</script>

<!-- Begin view content -->
<div id="middle_wrapper_content">
<div id="middle_bar">

<div class="crumb">
    <?php
        // If not in dashboard show explore link, otherwise 'dashboard' link
        if($assocNope) {
            echo $html->link('Explore', array('controller'=>'decks', 'action'=>'explore'));
        }
        else {
            echo $html->link('Dashboard', array('controller'=>'users', 'action'=>'dashboard'));
        }
    ?>
    &gt;
    <span class="you_are_here"><?php echo $deckName; ?></span>
</div>

<div id="deck_utils">
    <?php
        // Add favorite link if there's no association
        if($assocNope) {
            echo $html->link('Add to Favorites', array('controller'=>'decks', 'action'=>'favorite', $deckId));
        }
        // Add 'Edit' link if you created it
        else if($assocCreated) {
            echo $html->link('Edit Deck', array('controller'=>'decks', 'action'=>'edit', $deckId));
        }
        // TODO: Other cases available
        //else if($assocViewed) {}
        //else if($assocSaved) {}
    ?>
</div>

<div id="heading">
    <h1><?php echo $deckName; ?></h1>
    <p><?php echo $deckDesc; ?></p>
</div>

<div id="middle">
    <?php echo $form->create('Deck', array('action' => 'infoSubmit')); ?>
    <div id="category_select">
        <p>1. Select difficulty:</p>

        <input type="checkbox" id="select_all_checkbox" \>
        <label for="select_all_checkbox">All</label>

        <?php
            // Create checkbox form item
            $options = array('' => array(
                            $EASY => $easyLabel,
                            $MEDIUM => $mediumLabel,
                            $HARD => $hardLabel));
            echo $form->input('RatingsSelected', array('type' => 'select', 'label' => '',
                                                       'options' => $options, 'multiple' => 'checkbox',
                                                       'disabled' => array(1,2)));
                                                       //'disabled' => array(1,2), 'selected' => $selected));

            // Hidden form field to pass deckId
            echo $form->hidden('deckId', array('value' => $deckId));
        ?>
    </div>

    <div id="mode_select">
        <p>2. Select mode:</p>
        <?php
            // Hidden form field to record bit storing whether the Quiz button was clicked
            // Javascript on-click handler bound to 'Quiz' button
            echo $form->hidden('isQuizMode', array('value' => '0'));
            echo $form->submit('Learn', array('div' => false, 'id' => 'learn_button'));
            echo $form->submit('Quiz', array('div' => false, 'id' => 'quiz_button'));
        ?>
    </div>

    <?php echo $form->end(); ?>

    <div class="clear_div">&nbsp;</div>
</div>

<div id="bottom">
    <ul>
        <li><a href="#cards_tab">Cards</a></li>
        <li><a href="#stats_tab">Stats</a></li>
        <?php
            // Display results tab only if quizzed
            if(isset($quiz)) {
                echo "<li><a href=\"#results_tab\">Quiz Review</a></li>";
            }
        ?>
    </ul>

    <div id="stats_tab">
        <ul>
            <li>Created: <?php echo $deckData['Deck']['created']; ?></li>
            <li>Quizzed: <?php echo $userQuizCount . " times"; ?></li>
            <li>Tags: </li>
        </ul>
    </div>

    <div id="cards_tab">

        <!-- Static table header -->
        <table class="deck_table">
            <col class="col_num"/>
            <col class="col_term_defn"/>
            <col class="col_rating"/>
            <thead>
                <tr>
                    <th class="ord"></th>
                    <th>Question and Answer</th>
                    <th class="edit_rating">
                        <button type="button" class="btn"><span><span><b>&nbsp;</b><u>Edit Difficulties</u></span></span></button>
                    </th>
                </tr>
            </thead>
        </table>

        <!-- Card list table in scrollable div -->
        <div class="table_scroll">
        <table class="deck_table">
            <col class="col_num"/>
            <col class="col_term_defn"/>
            <col class="col_rating"/>
            <tbody>
            <?php
                // Iterate cards for table
                foreach($cards as $card) {
                    // Store values
                    $id = $card['Card']['id'];
                    $order = $card['Card']['card_order'];
                    $term = $card['Card']['question'];
                    $defn = $card['Card']['answer'];
                    $rating = $card['Rating']['rating'];

                    // Output row
                    echo "<tr class=\"card_row id_" . $id . "\">";
                    echo "<td class=\"ord\">$order</td>";
                    echo "<td>";
                    echo "<div class=\"term\">$term</div>";
                    echo "<div class=\"defn\">$defn</div>";
                    echo "</td>";
                    $ratingStr = $rating;
                    if(!isset($ratingStr)) {
                        $ratingStr = $DEFAULT_RATING;
                    }
                    echo "<td class=\"rts_col\">";
                    echo "<span class=\"rating\">$RATING_MAP[$ratingStr]</span>";
                    echo "</td>";
                    echo "</tr>";
                }
            ?>
            </tbody>
        </table>
        </div><!-- end table_scroll -->
    </div>

    <!-- Results tab, if applicable -->
    <?php if(isset($quiz)) { echo $this->element('results_tab'); } ?>

</div> <!-- end bottom div -->

</div>
</div>
<!-- End view content -->
