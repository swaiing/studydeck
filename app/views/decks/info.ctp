<!-- File: /app/views/decks/info.ctp -->
<?php
    // Javascript/CSS includes
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js',false);
    echo $javascript->link('jquery.corner',false);
    echo $javascript->link('deck_info',false);
    echo $html->css('deck_info',null,null,false);
    echo $html->css('jquery-ui-1.7.2.custom.gray',null,null,false);

    // Page global constants
    $EASY = 1;
    $MEDIUM = 2;
    $HARD = 3;
    $TOTAL = 99;
    $RATING_MAP = array(1 => 'Easy', 2 => 'Medium', 3 => 'Hard');
    $DEFAULT_RATING = 3;

    // Set for display
    $deckName = $deckData['Deck']['deck_name'];
    $deckDesc = $deckData['Deck']['description'];

    // Labels for checkboxes
    $easyLabel = "Easy ($cardsRatingsCount[$EASY])";
    $mediumLabel = "Medium ($cardsRatingsCount[$MEDIUM])";
    $hardLabel = "Hard ($cardsRatingsCount[$HARD])";

    // Autoselect if only cards of one rating exists
    // otherwise select 'Hard' cards
    $hasOneRating = false;
    $selected = array();
    if($cardsRatingsCount[$EASY] == $cardsRatingsCount[$TOTAL]) {
        $selected = array($EASY);
        $hasOneRating = true;
    }
    else if($cardsRatingsCount[$MEDIUM] == $cardsRatingsCount[$TOTAL]) {
        $selected = array($MEDIUM);
        $hasOneRating = true;
    }
    else if($cardsRatingsCount[$HARD] == $cardsRatingsCount[$TOTAL]) {
        $selected = array($HARD);
        $hasOneRating = true;
    }
    else {
        $selected = array($HARD);
    }
?>
<!-- Begin view content -->
<div id="middle_wrapper_content">
<div id="middle_bar">

<div class="crumb">
    <?php
        // If not in dashboard show explore link, otherwise 'dashboard' link
        if($notAssociated) {
            echo $html->link('Explore', array('controller'=>'decks', 'action'=>'explore'));
        }
        else {
            echo $html->link('Dashboard', array('controller'=>'users', 'action'=>'dashboard'));
        }
    ?>
    > 
    <span class="you_are_here"><?php echo $deckName; ?></span>
</div>

<div id="deck_utils">
    <?php
        // Add favorite link if not associated in my_decks
        if($notAssociated) {
            echo $html->link('Add to Favorites', array('controller'=>'decks', 'action'=>'favorite', $deckId));
        }
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

        <?php
            echo "<input type=\"checkbox\" id=\"select_all_checkbox\" ";
            if($hasOneRating) {
                echo "disabled=\"true\"";
            }
            echo ">";
            echo "<label for=\"select_all_checkbox\" ";
            if($hasOneRating) {
                echo "class=\"disabled\"";
            }
            echo ">";
            echo "All (";
            echo $cardsRatingsCount[$TOTAL];
            echo ")</label>";

            // Create checkbox form item
            $options = array('' => array(
                            $EASY => $easyLabel,
                            $MEDIUM => $mediumLabel,
                            $HARD => $hardLabel));
            echo $form->input('RatingsSelected', array('type' => 'select', 'label' => '',
                                                       'options' => $options, 'multiple' => 'checkbox',
                                                       'disabled' => array(1,2), 'selected' => $selected));

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
        <li><a href="#stats_tab">Stats</a></li>
        <li><a href="#cards_tab">Cards</a></li>
        <li><a href="#results_tab">Results</a></li>
    </ul>

    <div id="stats_tab">
        <ul>
            <li>Created: <?php echo $deckData['Deck']['created']; ?></li>
            <li>Studied: <?php echo $deckData['Deck']['view_count'] . " times"; ?></li>
        </ul>
    </div>

    <div id="cards_tab">
        <div id="edit_deck">
            <?php echo $html->link('Edit', array('controller'=>'decks', 'action'=>'edit', $deckId)); ?>
        </div>
        <table class="deck_table">
        <tbody>
            <tr>
                <th>#</th>
                <th>Term</th>
                <th>Definition</th>
                <th>Rating</th>
            </tr>
            <?php
                // Iterate cards for table
                foreach($cards as $card) {
                    $id = $card['Card']['id'];
                    $term = $card['Card']['question'];
                    $defn = $card['Card']['answer'];
                    $rating = $card['Rating']['rating'];
                    echo "<tr>";
                    echo "<td>$id</td>";
                    echo "<td>$term</td>";
                    echo "<td>$defn</td>";
                    $ratingStr = $rating;
                    if(!isset($ratingStr)) {
                        $ratingStr = $DEFAULT_RATING;
                    }
                    echo "<td>$RATING_MAP[$ratingStr]</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
        </table>
    </div>

    <div id="results_tab">
        <h2>Results</h2>
    </div>
</div>

</div>
</div>
<!-- End view content -->
