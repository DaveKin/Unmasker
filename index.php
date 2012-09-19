<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<?php 
if(isset($_REQUEST['user'])){
  $username = $_REQUEST['user'];
}else{
  $username = "";
}
?>

<html>
  <head>
<meta name="google-site-verification" content="e8daeuE8PgSVdnT3R526V2nwoBF6oNVgY_ff7j3gZ8w" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1250">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  <title>Unmasker</title>
  <link rel="stylesheet" href="css/screen.css" type="text/css"/>
  </head>
  <body>
<div id="existence">
  <div id="heaven">
    <div id="angel"><p>Hello dear</p></div>
  </div>
  <div id="sky">
  </div>
  <div id="earth">
  </div>
  <div id="loam">
  </div>
  <div id="hell">
    <div id="devil"><p>Back so soon?</p></div>
  </div>
</div>

<div id="header">
<h1>unmasker</h1>
<ul id="toplinks">
  <li><a class="twitterlink" href="http://twitter.com/unmasker" title="Vent your indignation" target="twitter">Unmasker on Twitter</a></li>
  <li><a class="aboutlink" href="#" title="What is this?">About Unmasker</a></li>
</ul>
</div>

<div id="content">
  <div id="warning" class="contentblock">
    <p>Unmasker attempts to expose those people who write generic, disingenuous crap in their Twitter bio.</p>
    <p>If you are offended by offensive language then this website might offend you.</p>
    <p><a href="http://www.cornify.com/" id="leave">F*ck Off</a> <a href="#" id="closewarning">Bring it on</a></p>
  </div>

  <div id="inputform" class="contentblock">
    <p id="theform"><label for="tuser">Twitter username to unmask</label> 
    <input type="text" id="tuser" name="tuser" value="<?php echo $username ?>"/> 
    <a id="go" href="#">Go</a></p>
    <p id="loading"><img src="img/yellowloader.gif"> hang on a moment...</p>
  </div>

  <div id="fail" class="contentblock">
    <p>Fail</p>
    <a href="#" id="reset">try again?</a>
  </div>

  <div id="person" class="contentblock">
    <img><span></span>
    <p></p>
    <div><a href="#" id="tweetthis" target="twitter">Tweet this</a> <a href="#" id="again">Do another</a>
    <div></div>
    </div>
  </div>
</div>

  <div id="about" class="contentblock">
    <p>Unmasker attempts to expose those people who write generic, disingenuous crap in their Twitter bio.</p>
    <p>Original, honest bios go to heaven, buzzword laden rubbish is sent to hell.</p>
    <p><a href="http://webdeveloper2.com/2009/07/unmasker-chronicles-dawn-of-the-demon/">More details in this blog post</a></p>
    <p><a href="#" id="closeabout">OK, whatever</a></p>
  </div>

  <div class="sideblock" id="recent">
    <div class="close"></div>
    <h2>Most Recent</h2>
    <div class="wrap"></div>
  </div>

  <div class="sideblock" id="popular">
    <div class="close"></div>
    <h2>Most Popular</h2>
    <div class="wrap"></div>
  </div>

  <div class="sideblock white" id="angels">
    <div class="close"></div>
    <h2>Most Angelic</h2>
    <div class="wrap"></div>
  </div>

  <div class="sideblock petrol" id="demons">
    <div class="close"></div>
    <h2>Most Demonic</h2>
    <div class="wrap"></div>
  </div>
<ul id="links">
  <li><a href="ajaxhandler.php?get=recent&format=html" rel="#recent" title="most recent" class="recent">Recent</a></li>
  <li><a href="ajaxhandler.php?get=popular&format=html" rel="#popular" title="most popular" class="popular">Popular</a></li>
  <li><a href="ajaxhandler.php?get=angels&format=html" rel="#angels" title="top angels" class="angels">Angels</a></li>
  <li><a href="ajaxhandler.php?get=demons&format=html" rel="#demons" title="top demons" class="demons">Demons</a></li>
</ul>
<div id="footer">
<p>Malevolence &amp; Gubbins: <a href="http://twitter.com/techn0tic">Dave Kinsella</a><br/>
Doodles &amp; Swearing: <a href="http://twitter.com/bkcl">Greg Smith</a></p>
</div>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script> 
  <script src="js/unmasker.min.js" type="text/javascript"></script>
  <script type="text/javascript">
  var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
  document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
  </script>
  <script type="text/javascript">
  try {
  var pageTracker = _gat._getTracker("UA-1135557-4");
  pageTracker._trackPageview();
  } catch(err) {}</script>
  </body>
</html>