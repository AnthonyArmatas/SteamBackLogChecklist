<?php 

$appid = 570;
$url = "https://store.steampowered.com/app/$appid";
$steamUrl = file_get_contents($url);
$regexNoEndSpace = '/<a href=\"https:\/\/store\.steampowered\.com\/tags\/en\/([a-zA-Z0-9\%\.\_\-]+)\/\?snr=1_5_9__409\"\sclass\=\"app_tag"\sstyle\=\"([\w\:\s;]+)?\">\s*\t*([a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;-]+)\s+<\/a>/';
//Looking For
//<a href="https://store.steampowered.com/app/873700/Dead_Pixels_Adventure/?snr=1_7_7_230_150_1"


preg_match_all($regexNoEndSpace, $steamUrl, $matches);
//print_r($matches[3][0]);



//$tempUrl = "https://store.steampowered.com/search/?sort_by=Name_ASC&tags=19";
//$steamUrl = file_get_contents($tempUrl);
//$tagsRegex = '/<a href=\"https:\/\/store\.steampowered\.com\/app\/(\d+)\/(\w+)\/([a-zA-Z0-9?=_]+)\"/';
//preg_match_all($tagsRegex, $steamUrl, $matches);
//Prints out the appid
//Prints out the appname
//print_r($matches[2]);
//echo '<br/>';

$dbServername = 'dynastinae.cxf3o3gwu9no.us-east-2.rds.amazonaws.com';
$dbName = "SBCDatabase";
$dbUsername = "atlas";
$dbPassword = "###";
$charset = 'utf8mb4';
$dsn = "mysql:host=$dbServername;dbname=$dbName;charset=$charset";

$db = new PDO($dsn,$dbUsername,$dbPassword);
//$jsonData = file_get_contents('http://api.steampowered.com/ISteamApps/GetAppList/v0002/?format=json');
//$data = json_decode($jsonData,true);
$retriveTagId = $db->prepare("SELECT * FROM testdb.tag as a WHERE a.name = ?");
$retriveAppId = $db->prepare("SELECT * FROM testdb.games as a WHERE a.appid = ?");
//$tagId = $db->prepare("SELECT appid FROM SBCDatabase.tag WHERE appid = ?");
//print_r($retriveTagId);

$retriveTagId->bindParam(1, $matches[3][0]);	
$retriveTagId->execute();
$tagValue = $retriveTagId->fetch(PDO::FETCH_ASSOC);
$retriveAppId->bindParam(1, $appid);	
$retriveAppId->execute();
$appValue = $retriveAppId->fetch(PDO::FETCH_ASSOC);

//print_r("The value " . $matches[3][0] . " is in the table as " . $sqlValue['tagid'] . " " . $sqlValue['name']);
print_r($appValue['appid']);
print_r($tagValue['tagid']);

$insertStmt = $db->prepare("insert into testdb.gametag values(?,?)");
$insertStmt->bindParam(1, $appValue['appid']);
$insertStmt->bindParam(2, $tagValue['tagid']);
$insertStmt->execute();


/*
for($i = 0; $i < count($matches[0]); $i++){
	$validGame->bindParam(1, $matches[3][$i]);	
	$validGame->execute();
	$sqlValue = $validGame->fetch(PDO::FETCH_ASSOC);
	if($sqlValue != False){
		print_r("The value " . $matches[3][$i] . " is in the table as " . $sqlValue['tagid'] . " " . $sqlValue['name']);
		echo '<br/>';
	}else{

		print_r("The value was " . $matches[3][$i] . $sqlValue['appid'] . " was not in the table");
		echo '<br/>';

	}
}
	echo nl2br("Done! \n"); */
