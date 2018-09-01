<?php
//Action tag 19
$tagid = 19;
$startPage = 1;
$gameListRegex = '/<a href=\"https:\/\/store\.steampowered\.com\/app\/(\d+)\/(\w+)\/([a-zA-Z0-9?=_]+)\"/';

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
$dbServername = 'dynastinae.cxf3o3gwu9no.us-east-2.rds.amazonaws.com';
$dbName = "SBCDatabase";
$dbUsername = "atlas";
$dbPassword = "###";
$charset = 'utf8mb4';
$dsn = "mysql:host=$dbServername;dbname=$dbName;charset=$charset";

$db = new PDO($dsn,$dbUsername,$dbPassword);
$retriveTagId = $db->prepare("SELECT * FROM testdb.tag as a WHERE a.name = ?");
$retriveAppId = $db->prepare("SELECT * FROM testdb.games as a WHERE a.appid = ?");
$retriveDetailId = $db->prepare("SELECT * FROM testdb.detail as a WHERE a.detailid = ?");
/////////////////////////////////////////////////////////////////////////////////////////////////////////////


for($curPage = $startPage; $curPage <= 1; $curPage++){

	$url = "https://store.steampowered.com/search/?sort_by=Name_ASC&tags=$tagid&page=$curPage";
	$steamUrl = file_get_contents($url);
	preg_match_all($gameListRegex, $steamUrl, $matches);
	//$gamePageUrl = "https://store.steampowered.com/app/$appid";
	for($curAppId = 0; $curAppId < count($matches[1]); $curAppId++){
			//print_r($matches[1][$curAppId]);
			echo '<br/>';		
			//Pulls the current steam page to walk through
			$appIdNum = $matches[1][$curAppId];
			$gamepageLink = 'https://store.steampowered.com/app/' . $appIdNum;
			$gamePageContents = file_get_contents($gamepageLink);
			$regexNoEndSpace = '/<a href=\"https:\/\/store\.steampowered\.com\/tags\/en\/([a-zA-Z0-9\%\.\_\-]+)\/\?snr=1_5_9__409\"\sclass\=\"app_tag"\sstyle\=\"([\w\:\s;]+)?\">\s*\t*([a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;-]+)\s+<\/a>/';
			//Retrives the tag matches into $tagMatches
			preg_match_all($regexNoEndSpace, $gamePageContents, $tagMatches);
			//print_r($tagMatches[3]);
			//print_r(count($tagMatches[3]));
			
			for($curTag = 0; $curTag < count($tagMatches[3]); $curTag++ ){
				//Retrives the tagId of the current tag from testdb.tag
				$retriveTagId->bindParam(1, $tagMatches[3][$curTag]);	
				$retriveTagId->execute();
				$tagValue = $retriveTagId->fetch(PDO::FETCH_ASSOC);
				print_r("Retrived the tag ID " . $tagValue['tagid'] . " for " . $tagValue['name']);
				echo '<br/>';
				print_r("The appid is " . $appIdNum);
				echo '<br/>';
				
				
				$insertStmt = $db->prepare("insert into testdb.gametag values(?,?)");
				$insertStmt->bindParam(1, $appIdNum);
				$insertStmt->bindParam(2, $tagValue['tagid']);
				$insertStmt->execute();
			}

			$regexGameDetail = '/<a\sclass=\"name\"\shref=\"https:\/\/store\.steampowered\.com\/search\/\?category(\d+)\=(\d+)\&snr=1_5_9__423\">([a-zA-Z0-9\s\-]+)<\/a>/';
			//Retrives the Detail matches into $detailMatches
			preg_match_all($regexGameDetail, $gamePageContents, $detailMatches);
			for($curDetail = 0; $curDetail < count($detailMatches[3]); $curDetail++){
				$retriveDetailId->bindParam(1, $detailMatches[3][$curDetail]);	
				$retriveDetailId->execute();
				$detailValue = $retriveDetailId->fetch(PDO::FETCH_ASSOC);
				print_r("Retrived the detail ID " . $detailValue['detailid'] . " for " . $detailValue['name']);
				echo '<br/>';
				print_r("The appid is " . $appIdNum);
				echo '<br/>';
				
				
				$insertStmt = $db->prepare("insert into testdb.gamedetail values(?,?)");
				$insertStmt->bindParam(1, $appIdNum);
				$insertStmt->bindParam(2, $detailValue['detailid']);
				$insertStmt->execute();
			}

			//print_r($tagMatches);

			//print_r($tagMatches[3]);
			echo '<br/>';					
	}

	//Prints out the appid
	//print_r($matches[1]);
	//print_r(count($matches[1]));
	//Prints out the appname
	//print_r($matches[2]);


}

