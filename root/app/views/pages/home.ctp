<?php
    // Set default homepage title
    $this->pageTitle = "Studydeck | Online flashcards made simple";

    // Javascript/CSS includes
    echo $javascript->link('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',false);
    echo $javascript->link('jquery.nivo.slider.pack');
    echo $html->css('nivo-slider',null,null,false);
?>
<div id="blurb">

    <h2>Master the GRE with Studydeck</h2>
    <div id="slider">
        <img src="img/slider1.jpg" title="#slide1caption" />
        <img src="img/slider2.jpg" title="#slide2caption" />
        <img src="img/slider3.jpg" title="#slide3caption" />
    </div>

    <div id="slide1caption" class="nivo-html-caption">The boys of 202 Elm</div>
    <div id="slide2caption" class="nivo-html-caption">Walter Thomas Frick</div>
    <div id="slide3caption" class="nivo-html-caption">What a lovely couple!</div>

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
            pauseTime: 5000
        });
    });
</script>
