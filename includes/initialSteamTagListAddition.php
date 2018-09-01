<?php

$url = file_get_contents("https://cors.io/?https://store.steampowered.com/tag/browse/#global_492");
$tagsRegex = '/tagid=\"(\d+)\">([a-zA-Z0-9\s\'\.\/_&amp;-]+)<\/div>/';
//$testTag = '<div class="tag_browse_tag" data-tagid="19">Action</div>';
//echo $url;
//Just like strings get written between quotes, regular expression patterns get written between slashes (/). This means that slashes inside the expression have to be escaped.
//Just need to export these tags to my db
preg_match_all($tagsRegex, $url, $matches);

//print_r($matches[0]);
//print_r($matches[1]);
//print_r($matches[2]);
//print_r($matches);

 //Three hours till it dies, it should go all the way through
ini_set('max_execution_time', 10800);
$dbServername = 'dynastinae.cxf3o3gwu9no.us-east-2.rds.amazonaws.com';
$dbName = "SBCDatabase";
$dbUsername = "atlas";
$dbPassword = "###";
$charset = 'utf8mb4';
$dsn = "mysql:host=$dbServername;dbname=$dbName;charset=$charset";

$db = new PDO($dsn,$dbUsername,$dbPassword);
//$jsonData = file_get_contents('http://api.steampowered.com/ISteamApps/GetAppList/v0002/?format=json');
//$data = json_decode($jsonData,true);
$stmt = $db->prepare("insert into SBCDatabase.tag values(?,?)");

//print_r(count($matches[0]));

for($i = 0; $i < count($matches[0]); $i++){
	$stmt->bindParam(1, $matches[1][$i]);	
	$stmt->bindParam(2, $matches[2][$i]);
	$stmt->execute();
}
	echo nl2br("Done! \n");
