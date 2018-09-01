<?php

$dbServername = 'dynastinae.cxf3o3gwu9no.us-east-2.rds.amazonaws.com';
$dbName = "testdb";
$dbUsername = "atlas";
$dbPassword = "###";
$charset = 'utf8mb4';
$dsn = "mysql:host=$dbServername;dbname=$dbName;charset=$charset";

$db = new PDO($dsn,$dbUsername,$dbPassword);
$jsonData = file_get_contents('http://api.open-notify.org/astros.json');
$data = json_decode($jsonData,true);
$stmt = $db->prepare("insert into testdb.spaceppl values(?,?,'')");

echo nl2br("People Count\n");
echo count($data['people']);
echo nl2br("\n");



foreach($data['people'] as $row){
	$stmt->bindParam(1, $row['craft']);	
	$stmt->bindParam(2, $row['name']);
	$stmt->execute();
	echo nl2br($row['craft'] . $row['name'] . "\n");
}