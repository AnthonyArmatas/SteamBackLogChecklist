<?php
 //Three hours till it dies, it should go all the way through
ini_set('max_execution_time', 10800);
$dbServername = 'dynastinae.cxf3o3gwu9no.us-east-2.rds.amazonaws.com';
$dbName = "SBCDatabase";
$dbUsername = "atlas";
$dbPassword = "###";
$charset = 'utf8mb4';
$dsn = "mysql:host=$dbServername;dbname=$dbName;charset=$charset";

$db = new PDO($dsn,$dbUsername,$dbPassword);
$jsonData = file_get_contents('http://api.steampowered.com/ISteamApps/GetAppList/v0002/?format=json');
$data = json_decode($jsonData,true);
$stmt = $db->prepare("insert into SBCDatabase.game values(?,?)");



foreach($data['applist']['apps'] as $row){
	$stmt->bindParam(1, $row['appid']);	
	$stmt->bindParam(2, $row['name']);
	$stmt->execute();
}
	echo nl2br("Done! \n");
