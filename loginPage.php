
<?php

	require 'steamauth/steamauth.php';
	//require 'steamauth/userInfo.php';

?>
<!DOCTYPE html>
<html>
<head>
<title>Steam Backlog Checklist</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link  href='StyleSheets/scbStyle.css' rel='stylesheet' type='text/css' />
<link  href='StyleSheets/w3.css' rel='stylesheet' type='text/css' />

</head>
<body>

<div id="homeHeader" class="w3-container header">
	<h2 style='display: inline;'>Backlog Checklist LOGIN PAGE</h2>
	<span id="logoutButtonHeader">
	<form action="logout.php" method="get"><button name='logout' type='submit'>Logout</button></form>
	</span>
		<?php
		/*if(isset($_SESSION['steamid'])) {
    		include ('steamauth/userInfo.php'); //To access the $steamprofile array
    		logoutbutton(); //Logout Button
		}  else {
	    //Protected content
	    echo "something went wrong with the logout button"; //Logout Button
		}  */ 
		?>
</div>

	

<?php
//if(isset($_SESSION['steamid'])) {
//    include ('steamauth/userInfo.php'); //To access the $steamprofile array
//    logoutbutton(); //Logout Button

//}  else {

    //Protected content

//    echo "something went wrong with the logout button"; //Logout Button
//}     
?>

	<?php if(isset($_SESSION['steamid'])) {
		echo $_SESSION['steam_personaname'];
	} else {
		//nl2br makes it so php can use line breaks like html
		echo nl2br ("\n Not Logged in");
	}
	?>
	<p>  </p>


	<?php ?>


	
	<?php 
		if(!empty($_GET['steamid'])){
	 	   echo 'Hello ' . htmlspecialchars($_GET["steamid"]) . '!';
		}

	?>




<form >


</body>
</html>