<?php

 //5 Min it dies, it should go all the way through
ini_set('max_execution_time', 300);
$dbServername = 'dynastinae.cxf3o3gwu9no.us-east-2.rds.amazonaws.com';
$dbName = "SBCDatabase";
$dbUsername = "atlas";
$dbPassword = "H0ld1t!n";
$charset = 'utf8mb4';
$dsn = "mysql:host=$dbServername;dbname=$dbName;charset=$charset";

$db = new PDO($dsn,$dbUsername,$dbPassword);
/*$jsonData = file_get_contents('http://api.open-notify.org/astros.json');
$data = json_decode($jsonData,true);
$stmt = $db->prepare("insert into testdb.spaceppl values(?,?,'')");
foreach($data['people'] as $row){
	$stmt->bindParam(1, $row['craft']);	
	$stmt->bindParam(2, $row['name']);
	$stmt->execute();
	echo nl2br($row['craft'] . $row['name'] . "\n");
}


//The commented out section is how you use PDO, Below I am using mysqli. Both are valid ways to access the database
//$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword,$dbName);
*/
