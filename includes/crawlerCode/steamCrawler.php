<?php
	include 'getGameList.php';
	include 'addInfoToGames.php';
	ini_set('max_execution_time', 6030);

	//Set up the database in the steamCrawler.php file so I do not have to initialize it in every file
	$dbServername = 'dynastinae.cxf3o3gwu9no.us-east-2.rds.amazonaws.com';
	$dbName = "SBCDatabase";
	$dbUsername = "atlas";
	$dbPassword = "###";
	$charset = 'utf8mb4';
	$dsn = "mysql:host=$dbServername;dbname=$dbName;charset=$charset";

	$db = new PDO($dsn,$dbUsername,$dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//We retrieve the list of game to append to the address bar so we can walk through every game.
	//This is more efficient than my previous idea of going through each search tag page because it eliminates
	//the need to hit the same game multiple times.
	$arrayOfTags = getGameList($db);
	//print_r(count($arrayOfTags));
	//echo '<br/>';

	$gamesWorkedThrough = 0;
	for($curGame = 0; $curGame < 603 /*count($arrayOfTags)*/; $curGame++ ){
		print_r("games Worked Through: " . $gamesWorkedThrough);
		echo '<br/>';	
		$appid = $arrayOfTags[$curGame]['appid'];	
		$url = "https://store.steampowered.com/app/$appid";
		$gamePageUrl = file_get_contents($url);
		//Pulls all of the info from the page and adds it to the DB
		getTagsOnPage($db,$gamePageUrl,$appid);
		getLanguagesOnPage($db,$gamePageUrl,$appid);
		getSystemsOnPage($db,$gamePageUrl,$appid);
		getDetailsOnPage($db,$gamePageUrl,$appid);
		getVRDetailsOnPage($db,$gamePageUrl,$appid);
		$gamesWorkedThrough++;
	}

	echo 'Done <br/>';
	
