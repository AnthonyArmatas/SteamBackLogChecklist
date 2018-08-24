<?php
	//Not currently used.
	//To use add somthing like <a href="[filelocation of logout.php]>Logout</a>"
	session_start();
	unset($_SESSION['steamid']);
	unset($_SESSION['steam_uptodate']);

	header("Location: index.php")
	
?>