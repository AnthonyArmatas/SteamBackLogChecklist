<!--
An easy to find link so I can view it locally on my browser
 http://localhost:1234/steambacklogchecklist/index.php

	FOR CSS FILES
	If your site is not live yet, and you just want to update the stylesheet at your pleased intervals, then use this:
	Ctrl + F5
	This will force your browser to reload and refresh all the resources related to the website's page.

	On Mac OS Chrome use: Command + Shift + R



  -->

<?php
    if(!isset($_SESSION)) 
    { 
		ini_set('max_execution_time', 300);
        session_start(); 
    } 
	require 'steamauth/steamauth.php';
	//require 'steamauth/userInfo.php';
?>

<!DOCTYPE html>
<html>
<head>
<link  href='StyleSheets/scbStyle.css' rel='stylesheet' type='text/css' />
<link  href='StyleSheets/w3.css' rel='stylesheet' type='text/css' />

<title>Steam Backlog Checklist</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
<div id="wrapper">
<!-- class adds a 16px left and right padding to any HTML element-->
	<div id="loginHeader" class="w3-container header">
		<img class="DehumidifierLogo" src="includes/images/Dehumidifier-Logo.png" alt="Dehumidifier Logo Icon">
	</div>



  <div id="loginContiner">
  	<div id="upperSectionLogin">
	  	<h2>
	  		Welcome to <span id='DE' style="color:#2286de">DE</span><span id='HUMIDI' style="color:#068e4e">HUMIDI</span><span id='FIER' style="color:#8da356">FIER</span>
	  	</h2>
	  	<h3>
	  		Your Steam BackLog Checklist
	  	</h3>
	  	<p class="loginText">On this site you will be able to</p>
	  	<ul class="loginText" style="display:block;">
	  		<li>View all of the games on your steam library</li>
	  		<li>Filter through your games by steam tags, game details, and other game data from steam store</li>
	  		<li>Create and an editable checklist of games in your steam library to help finally complete your backlog</li>
	  		<li>Select a random game from your filtered library</li>
	  	</ul>
	  	<span class="loginText">DEHUMIDIFIER is a small project created to utilize languages and technologies I am new to, while sharpening my front and backend skills. The core of the site is here, but additional features and optimization are still being worked on.  While the optimization is still being worked on the initial load time of the page, after logging in with your steam id, may take around 5 minutes, for larger steam libraries. </span>
	  	<div id="steamButton">
		<?php
		// Logs into steamwithout using the call //echo loginbutton("square"); // may just go back to using the function
		if(!isset($_SESSION['steamid'])) { //checks to see if steamid is has been set

	    	//loginbutton(); //login button
	    	$button['rectangle'] = "01";
			$button['square'] = "02";
			$button = "<a href='?login'><img src='https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_".$button["square"].".png'></a>";
		
		echo $button;

		}  else {
		    include ('steamauth/userInfo.php'); //To access the $steamprofile array
		    //Protected content
		    logoutbutton(); //Logout Button
		}     
		?>
		</div>
	</div>
	<div id="lowerSectionLogin">
  		<span>Copyright ©2018 Anthony Armatas. This is NOT an official Steam tool.
			Questions? comments? problems you'd like to report? Contact me on my Linkedin.
			Steam and all other trademarks and game logos are property of their respective owners.
		</span>
	</div>	
  </div>

<div>
	<?php if(isset($_SESSION['steamid'])) {
		//echo $_SESSION['steam_personaname'];
		//If logged in it will redirect the user to the login page
		header('Location: loginpage.php');
	} else {
		//nl2br makes it so php can use line breaks like html
		//echo nl2br ("\n Not Logged in");
	}
	?>

</div>

	
  <div id="footer" class="w3-container">
  	<a href="https://github.com/AnthonyArmatas" ><img src="includes/images/GitHub-Logo-120px.png" alt="GitHub Link Icon" style="width:45px;height:45px;"></a>
  	<!-- Keep The linkedin picture around 5-20px larger than the git hub. That is about their size difference-->
  	<a href="https://www.linkedin.com/in/anthonyarmatas/"><img src="includes/images/LinkedIn-Logo-120px.png" alt="Linkedin Link Icon" style="width:60px;height:60px;"></a>
  </div>
</div>
</body>
</html>