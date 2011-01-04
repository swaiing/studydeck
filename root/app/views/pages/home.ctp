<?php
    // Set default homepage title
    $this->pageTitle = "Studydeck | The Best Way to Study GRE Vocabulary Online";

    // Javascript/CSS includes
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('jquery.nivo.slider.pack');
    echo $html->css('nivo-slider',null,null,false);
    echo $html->css('http://fonts.googleapis.com/css?family=Droid+Sans',null,null,false);
?>
<div id="blurb">

    <h2>The Best Way to Study GRE Vocabulary Online</h2>
    <div id="slider">
        <img src="img/slider1_overview.png"/>
        <img src="img/slider2_categorize.png"/>
        <img src="img/slider3_quiz.png"/>
        <img src="img/slider4_track.png"/>
        <img src="img/slider5_create.png"/>

        <div class="nivo-directionNav" style="display:none;">
            <a class="nivo-prevNav">Prev</a>
            <a class="nivo-nextNav">Next</a>
        </div>
    </div>
</div>

<div id="more">
    <div id="panels">
        <div id="get_started">
            <h3><?php echo $html->link('Get Started!', array('controller'=>'products', 'action'=>'view')); ?></h3>
        </div>
    </div>
</div>
<div id="more_bottom"></div>

<script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider({
            effect: 'random',
            pauseTime: 7000,
	        startSlide:0,
            directionNav:true,
            directionNavHide:true,
            controlNav:false,
            pauseOnHover:true
        });
    });
</script>
