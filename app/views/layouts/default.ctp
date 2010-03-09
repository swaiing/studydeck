<?php header('Content-type:text/html; charset=UTF-8'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title><?php echo $title_for_layout?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/reset/reset-min.css">
  <?php
    // Include default styles
    echo $html->css('styles');
    // Include scripts and CSS from inner view
    echo $scripts_for_layout;
  ?>
</head>
<body>
<div id="top_bar">
<div id="top_logo">
    <h1>
        <?php echo $html->link('StudyDeck',"/"); ?>
    </h1>
</div>

<div id="top_nav">
<div id="nav_left_links">
    <ul id="list_nav_main">
    <?php
        // Line separator character
        $LINE_SEP = " | ";

        // For a logged in user,
        // There are links for: 'Dashboard' | 'Explore' | 'Create'
        if(isset($activeUser)) {
            echo "<li>" . $html->link('Dashboard',array('controller'=>'users','action'=>'dashboard')) . $LINE_SEP . "</li>";
        }

        // For an anonymous user,
        // There are links for: 'Explore' | 'Create'
        echo "<li>" . $html->link('Explore',array('controller'=>'decks','action'=>'explore')) . $LINE_SEP . "</li>";
        echo "<li>" . $html->link('Create',array('controller'=>'decks','action'=>'create')) . "</li>";
    ?>
    </ul>
</div>

<div id="nav_right_login">
    <ul id="list_nav_login">
    <?php

       // For a logged in user,
       // The current user logged in is displayed: Hello steve | 'Logout'
       if(isset($activeUser)) {
            echo "<li>" . $activeUser . $LINE_SEP . "</li>";
            echo "<li>" . $html->link('Logout',array('controller'=>'users','action'=>'logout')) . "</li>";
        }
        else {        
            // For an anonymous user,
            // There are links for: 'Register' | 'Login'
            echo "<li>" . $html->link('Register',array('controller'=>'users','action'=>'register')) . $LINE_SEP . "</li>";
            echo "<li>" . $html->link('Login',array('controller'=>'users','action'=>'login')) . "</li>";
        }
    ?>
    </ul>
</div>
</div> <!-- end top_nav -->

</div> <!-- end top_bar -->

<!-- Render view here -->
<?php echo $content_for_layout ?>

<div id="footer">
  <div id="footer_copyright">
    <span class="copyright">Copyright 2009 StudyDeck. Build Version: BUILD_NUM. All rights reserved.</span>
  </div>
  <div id="footer_nav">
    <ul id="list_nav_footer">
      <li><a href="/">Home</a> | </li>
      <li><a href="#">Help</a> | </li>
      <li><a href="#">About</a> | </li>
      <li><a href="#">Contact</a></li>
      <li><a href="#">Terms of Use</a> | </li>
      <li><a href="#">Privacy Policy</a> | </li>
    </ul>
  </div>
</div> <!-- end footer -->

<script type="text/javascript">
    var uservoiceOptions = {
      /* required */
      key: 'studydeck',
      host: 'studydeck.uservoice.com', 
      forum: '42041',
      showTab: true,  
      /* optional */
      alignment: 'left',
      background_color:'#004D99', 
      text_color: 'white',
      hover_color: '#06C',
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
