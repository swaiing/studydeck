<!-- File: /app/views/decks/study.ctp -->

<?php
    echo $javascript->link('jquery-1.2.6.min',false) . "\n";
    echo $javascript->link('study_deck',false) . "\n";;
    echo $html->css('view_deck',null,null,false) . "\n";
?>

<!-- Pass card data -->
<script type="text/javascript">
<?php echo "var deckData = " . $javascript->object($deck); ?>
</script>

<div id="middle_wrapper_content">

  <div id="view_deck_wrap">

        <h1 class="title"><?php echo $deckInfo['Deck']['deck_name'] ?></h1>

        <!--<div id="prev_card">Previous</div>-->

        <div id="card_wrap">

          <div id="additional">

                <div id="prev_card"><a href="#" class="prev">Previous</a></div>
                <div id="center_items">

                <div class="widget_item">
                  <span>Show first:</span>
                  <select name="show_first">
                    <option value="">Term</option>
                    <option value="">Definition</option>
                  </select>
                </div>

                <div class="widget_item">
                  <span>Rating:</span>
                  <select name="rating">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="Hard">Hard</option>
                  </select>
                  <!--<div id="rating">
                    <img src="img/star_on.png"/>
                    <img src="img/star_on.png"/>
                    <img src="img/star_on.png"/>
                    <img src="img/star_on.png"/>
                  </div>-->
                </div>

                </div> <!-- end center_items -->

                <div id="next_card"><a href="#" class="next">Next</a></div>

                <div class="clear_div"></div>
          </div> <!-- end additional -->

          <div class="card">
                <p class="term"></p>
                <p class="defn"></p>

                <div id="eval">
                   <ul>
                     <li><a href="#" class="correct">correct</a></li>
                     <li><a href="#" class="incorrect">incorrect</a></li>
                   </ul>
                </div>
          </div>

        </div> <!-- end card_wrap -->

        <!--<div id="next_card">Next</div>-->

        <div class="clear_div">&nbsp;</div>
  </div> <!-- end view_deck_wrap -->

</div>

