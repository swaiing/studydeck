<!-- File: /app/views/elements/deck_viewer.ctp -->

<!-- Container for learn mode controls -->
<div id="top_controls"></div>

<!-- Card wrapper -->
<div id="card_wrap" class="dialog">
    <div class="content">
    <div class="t"></div>

        <div id="row_body_mask"></div>
        
        <div id="row_body">
            <div id="row_question">
                <span id="card_question"></span>
            </div>

            <div id="row_answer">
                <span id="card_answer"></span>
            </div>
        </div>

        <!-- rating selector/incorrect/correct -->
        <div id="row_bottom"></div>

    </div> <!-- end content -->
    <div class="b"><div></div></div> <!-- for box -->
</div> <!-- end #card_wrap -->

<div id="bottom_controls">
    <div id="prev" class="left">
        <a href="#">[previous]</a>
    </div>
    <div id="next" class="right">
        <a href="#">[next]</a>
    </div>
    <div class="center">
        <div id="deck_progress"></div>
    </div>
</div>

</div> <!-- end #middle_wrapper_content -->
