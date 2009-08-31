<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title><?php echo $title_for_layout?></title>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
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
  <div id="top_logo"><h1><a href="/studydeck/">StudyDeck</a></h1></div>

  <div id="top_nav">
  <div class="left">
    <ul class="inline_list" id="main_nav">
      <li><a href="#">About</a></li>
      <li><a href="/studydeck/decks/create">Create Deck</a></li>
      <li><a href="/studydeck/decks/explore">Explore Decks</a></li>
    </ul>
  </div>

  <div class="right">
    <ul class="inline_list" id="register_nav">
      <li><a href="/studydeck/users/register">Register</a></li>
      <li><a href="/studydeck/users/login">Login</a></li>
      <li><a href="/studydeck/users/logout">Logout</a></li>
    </ul>
  </div>
  </div> <!-- end top_nav -->

</div> <!-- end top_bar -->

<!-- Render view here -->
<?php echo $content_for_layout ?>

<div id="footer">
  <div class="left">
    <span class="copyright">Copyright 2009 StudyDeck. All rights reserved.</span>
  </div>
  <div class="right">
    <ul class="inline_list" id="footer_nav">
      <li><a href="/studydeck/">Home</a> | </li>
      <li><a href="#">About</a> | </li>
      <li><a href="#">Terms of Use</a> | </li>
      <li><a href="#">Contact</a></li>
    </ul>
  </div>
</div> <!-- end footer -->

</body>
</html>
