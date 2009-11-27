<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title><?php echo $title_for_layout?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
        // There are links for: 'Dashboard' | 'Explore Decks' | 'Create Deck' | 'Help'
        if(isset($activeUser)) {
            echo "<li>" . $html->link('Dashboard',array('controller'=>'users','action'=>'dashboard')) . $LINE_SEP . "</li>";
        }

        // For an anonymous user,
        // There are links for: 'Explore Decks' | 'Create Deck' | 'Help'
        echo "<li>" . $html->link('Explore Decks',array('controller'=>'decks','action'=>'explore')) . $LINE_SEP . "</li>";
        echo "<li>" . $html->link('Create Deck',array('controller'=>'decks','action'=>'create')) . $LINE_SEP . "</li>";
    ?>
        <li><a href="#">Help</a></li>
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
      <li><a href="/studydeck/">Home</a> | </li>
      <li><a href="#">About</a> | </li>
      <li><a href="#">Terms of Use</a> | </li>
      <li><a href="#">Contact</a></li>
    </ul>
  </div>
</div> <!-- end footer -->

</body>
</html>
