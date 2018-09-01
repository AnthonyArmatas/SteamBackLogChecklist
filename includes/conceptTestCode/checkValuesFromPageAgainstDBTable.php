<?php 

$url = "https://store.steampowered.com/search/?sort_by=Name_ASC&tags=";
$nextLink = "https://store.steampowered.com/app/";

//Action tag 19
$tempUrl = "https://store.steampowered.com/search/?sort_by=Name_ASC&tags=19";

$steamUrl = file_get_contents($tempUrl);
$tagsRegex = '/<a href=\"https:\/\/store\.steampowered\.com\/app\/(\d+)\/(\w+)\/([a-zA-Z0-9?=_]+)\"/';
//Looking For
//<a href="https://store.steampowered.com/app/873700/Dead_Pixels_Adventure/?snr=1_7_7_230_150_1"


preg_match_all($tagsRegex, $steamUrl, $matches);



//$tempUrl = "https://store.steampowered.com/search/?sort_by=Name_ASC&tags=19";
//$steamUrl = file_get_contents($tempUrl);
//$tagsRegex = '/<a href=\"https:\/\/store\.steampowered\.com\/app\/(\d+)\/(\w+)\/([a-zA-Z0-9?=_]+)\"/';
//preg_match_all($tagsRegex, $steamUrl, $matches);
//Prints out the appid
print_r($matches[1]);
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
$stmt = $db->prepare("SELECT appid FROM SBCDatabase.game WHERE appid = ?");


for($i = 0; $i < count($matches[0]); $i++){
	$stmt->bindParam(1, $matches[1][$i]);	
	$stmt->execute();
	$sqlValue = $stmt->fetch(PDO::FETCH_ASSOC);
	if($sqlValue != False){
		print_r("The value " . $matches[1][$i] . " is in the table as " . $sqlValue['appid']);
		echo '<br/>';
	}else{

		print_r("The value was " . $matches[1][$i] . $sqlValue['appid'] . " was not in the table");
		echo '<br/>';

	}
}
	echo nl2br("Done! \n");
