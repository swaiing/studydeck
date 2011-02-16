<?php header('Content-type:text/html; charset=UTF-8'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title><?php echo $title_for_layout?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="Studydeck is a studying system designed to help you learn the vocabulary necessary to get a top score on the GRE. Online flash cards with premium GRE word lists. Dead-simple quiz and review system." />
  <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/reset/reset-min.css">
  <?php
    // Include default styles
    echo $html->css('styles');
    echo $html->css('http://fonts.googleapis.com/css?family=Allerta',null,null,false);

    // Include scripts and CSS from inner view
    echo $scripts_for_layout;
  ?>
  <script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-20265898-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
  
</head>
<body>
<div id="content">
<div id="header">

    <div id="site_id">
        <a href="/"><img src="/img/sd_logo.png" alt="Studydeck"/></a>
    </div>

    <div id="nav">

        <div id="row">
        <div id="nav_left">
            <ul>
            <?php
                // For a logged in user,
                // Dashboard | Store | Create
                if(isset($activeUser)) {
                    echo "<li>" . $html->link('Dashboard',array('controller'=>'users','action'=>'dashboard')) . "</li>";
                    echo "<li>" . $html->link('Store',array('controller'=>'products','action'=>'view')) . "</li>";
                    echo "<li>" . $html->link('Create',array('controller'=>'decks','action'=>'create')) . "</li>";
                }
            ?>
            </ul>
        </div>

        <div id="nav_right">
            <ul>
            <?php
               // For a logged in user,
               // The current user logged in is displayed: Hello steve | 'Logout'
               if(isset($activeUser)) {
                    echo "<li>" . $html->link("Settings",array('controller'=>'users','action'=>'account')) . "</li>";
                    echo "<li>" . $html->link('Logout',array('controller'=>'users','action'=>'logout')) . "</li>";
                }
                else {        
                    // For an anonymous user,
                    // There are links for: 'About' | 'Features' | 'Login'
            ?>
                    <li><a href="/about">About</a></li>
                    <li><a href="/features">Features</a></li>
                    <li><a href="/users/login"><img id="login_button" src="/img/login_button.png" alt="Login"/></a></li>
            <?php
                }
            ?>
            </ul>

        </div>
        </div>

    </div> <!-- end nav -->

</div> <!-- end header -->

<!-- Render view here -->
<?php echo $content_for_layout ?>

<div id="footer_wrap">
<div id="footer">
  <div id="footer_copyright">
    <span class="copyright">Copyright 2011 Studydeck. All rights reserved.</span>
  </div>
  <div id="footer_nav">
    <ul id="list_nav_footer">
      <li><a href="/">Home</a> | </li>
      <li><a href="/about">About</a> | </li>
      <li><a href="/contact">Contact</a> | </li>
      <li><a href="/tos">Terms</a> | </li>
      <li><a href="/privacy">Privacy Policy</a></li>
    </ul>
  </div>
</div> <!-- end footer -->
</div> <!-- end footer_wrap -->
</div> <!-- end content -->

<script type="text/javascript">
    var uservoiceOptions = {
      /* required */
      key: 'studydeck',
      host: 'studydeck.uservoice.com', 
      forum: '42041',
      showTab: true,  
      /* optional */
      alignment: 'left',
      background_color:'#363636', 
      text_color: 'white',
      hover_color: '#89A7D7',
      lang: 'en'
    };

    function _loadUserVoice() {
      var s = document.createElement('script');
      s.setAttribute('type', 'text/javascript');
      s.setAttribute('src', ("https:" == document.location.protocol ? "https://" : "http://") + "cdn.uservoice.com/javascripts/widgets/tab.js");
      document.getElementsByTagName('head')[0].appendChild(s);
    }
    _loadSuper = window.onload;
    window.onload = (typeof window.onload != 'function') ? _loadUserVoice : function() { _loadSuper(); _loadUserVoice(); };
</script>
</body>
</html>
