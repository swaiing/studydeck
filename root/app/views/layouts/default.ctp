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
<div id="content">
<div id="header">

    <div id="site_id">
	<a href="/">
	    <img src="/img/sd_logo.png" alt="Studydeck"/>
	</a>
    </div>

    <div id="nav">

        <div id="row">
        <div id="nav_left">
            <ul>
            <?php
                // For a logged in user,
                // Dashboard | Store | Create
                if(isset($activeUser)) {
                    echo "<li class=\"button\">" . $html->link('Dashboard',array('controller'=>'users','action'=>'dashboard')) . "</li>";
                    // No more explore
                    //echo "<li class=\"button\">" . $html->link('Explore',array('controller'=>'decks','action'=>'explore')) . "</li>";
                    echo "<li class=\"button\">" . $html->link('Store',array('controller'=>'products','action'=>'view')) . "</li>";
                    echo "<li class=\"button\">" . $html->link('Create',array('controller'=>'decks','action'=>'create')) . "</li>";
                }
            ?>
            </ul>
        </div>

        <div id="nav_right">

            <?php
               // For a logged in user,
               // The current user logged in is displayed: Hello steve | 'Logout'
               if(isset($activeUser)) {
                    echo "<li>" . $html->link("Settings",array('controller'=>'users','action'=>'account')) . "</li>";
                    echo "<li>|</li>";
                    echo "<li>" . $html->link('Logout',array('controller'=>'users','action'=>'logout')) . "</li>";
                }
                else {        
                    // For an anonymous user,
                    // There are links for: 'Register' | 'Login'
                    //echo "<li>" . $html->link('Register',array('controller'=>'products','action'=>'view')) . "</li>";
                    //echo "<li>|</li>";
                    echo "<li>" . $html->link('Login',array('controller'=>'users','action'=>'login')) . "</li>";
                }
            ?>
            </ul>

            <?php
                // Removed search for redesign
                //echo $form->create("Deck", array('controller' => 'decks', 'action' => 'explore'));
                //echo $form->input("searchQuery", array('label' => false));
                //echo "<input title=\"Search\" type=\"submit\" class=\"submit\" value=\"\"/>";
                //echo $form->end();
            ?>
        </div>
        </div>

    </div> <!-- end nav -->

</div> <!-- end header -->

<!-- Render view here -->
<?php echo $content_for_layout ?>

<div id="footer_wrap">
<div id="footer">
  <div id="footer_copyright">
    <span class="copyright">Copyright 2010 StudyDeck. Build Version: BUILD_NUM. All rights reserved.</span>
  </div>
  <div id="footer_nav">
    <ul id="list_nav_footer">
      <li><a href="/">Home</a> | </li>
      <li><a href="#">About</a> | </li>
      <li><a href="#">Contact</a> | </li>
      <li><a href="#">Help</a> | </li>
      <li><a href="#">Terms of Use</a> | </li>
      <li><a href="#">Privacy Policy</a></li>
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
