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
<!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->


</head>
<body>
<div id="wrapper">
<!-- class adds a 16px left and right padding to any HTML element-->
	<div id="loginHeader" class="w3-container header">
		<h2>Steam Backlog Checklist</h2>
	</div>



  <div id="loginPage" >
  	<h3>
  		Welcome to your Steam BackLog Checklist
  	</h3>
  	<p class="loginText">On this site you will be able to</p>
  	<ul class="loginText">
  		<li>View all of the games on your steam library</li>
  		<li>Filter through your games by steam tags (ex: local coop, online multiplayer, ect)</li>
  		<li>Create and save an editable checklist of your games so you can finally complete your backlog</li>
  	</ul>

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
  	<p>https://github.com/AnthonyArmatas</p>
  </div>
</div>
</body>
</html>