<?php
	include 'addDetailsToDataBase.php';

	function getTagsOnPage($db,$gamePageUrl,$appid){
		//Retrives the info for a tag with the passed in name from the database
		$retriveTagId = $db->prepare("SELECT * FROM testdb.tag as a WHERE a.name = ?");
		$insertTagsToGames = $db->prepare("insert into testdb.gametag values(?,?)");

		//The regular expression to pull the tags from the page.
		$regexGameTags = '/<a href=\"https:\/\/store\.steampowered\.com\/tags\/en\/([a-zA-Z0-9\%\.\_\-]+)\/\?snr=1_5_9__409\"\sclass\=\"app_tag"\sstyle\=\"([\w\:\s;]+)?\">\s*\t*([a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;-]+)\s+<\/a>/';
		//All of the tags are in the variable $tagMatches from the regualr expression. We are using tagMatches[3] because that is the Capturing group of the page text which appears to the user.
		preg_match_all($regexGameTags, $gamePageUrl, $tagMatches);
		for($curTag = 0; $curTag < count($tagMatches[3]); $curTag++){
			try{
				//Sets the query with the $curTag
				$retriveTagId->bindParam(1, $tagMatches[3][$curTag]);	
				//Runs the query			
				$retriveTagId->execute();
				//Retrives the values from the query						
				$tagValue = $retriveTagId->fetch(PDO::FETCH_ASSOC);
				connectTagsToGame($appid,$tagValue['tagid'],$insertTagsToGames);
			}
			catch(PDOException $e){
				handle_sql_errors($retriveTagId, $e->getMessage());
			}					
		}

	}
	function handle_sql_errors($query, $error_message){
		//echo '<pre>';
    	//echo $query;
    	//echo '</pre>';
    	//echo $error_message;
    	//die;
	}
	function connectTagsToGame($appid,$tagid,$insertTagsToGames){
		$insertTagsToGames->bindParam(1, $appid);
		$insertTagsToGames->bindParam(2, $tagid);
		$insertTagsToGames->execute();
	}	

	function getLanguagesOnPage($db,$gamePageUrl,$appid){
		//Retrives the info for a language with the passed in name from the database
		$retriveLangId = $db->prepare("SELECT * FROM testdb.languages as a WHERE a.language_used = ?");
		$insertLangsToGames = $db->prepare("insert into testdb.gamelang values(?,?)");
		//The regular expression to pull the languages from the page.
		$regexLanguages = '/<td\sstyle\=\"[a-zA-Z0-9\s:\-;]+\"\s+class=\"ellipsis\">\s+([a-zA-Z0-9\-\(\)]+?[a-zA-Z0-9\-\(\)\s]+?)\s+<\/td>/';
		preg_match_all($regexLanguages, $gamePageUrl, $languageMatches);
		for($curLang = 0; $curLang < count($languageMatches[1]); $curLang++){
			try{
				//Sets the query with the $curLang
				$retriveLangId->bindParam(1, $languageMatches[1][$curLang]);	
				//Runs the query			
				$retriveLangId->execute();
				//Retrives the values from the query						
				$langValue = $retriveLangId->fetch(PDO::FETCH_ASSOC);
				connectLangsToGame($appid,$langValue['langid'],$insertLangsToGames);
				
			}
			catch(PDOException $e){
				handle_sql_errors($retriveLangId, $e->getMessage());
			}					
		}

	}
	function connectLangsToGame($appid,$langid,$insertLangsToGames){
		$insertLangsToGames->bindParam(1, $appid);
		$insertLangsToGames->bindParam(2, $langid);
		$insertLangsToGames->execute();
	}		


	function getSystemsOnPage($db,$gamePageUrl,$appid){
		//Retrives the info for a System with the passed in name from the database
		$retriveSysId = $db->prepare("SELECT * FROM testdb.system as a WHERE a.name = ?");
		$insertSysToGames = $db->prepare("insert into testdb.gamesystem values(?,?)");
		//The regular expression to pull the languages from the page.
		$regexSystems = '/<div\sclass\=\"[a-zA-Z0-9\s_\-]+\"\s+data-os\=\"[a-zA-Z0-9]+\">\s+([a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-\+]+\s?[a-zA-Z0-9\'\.\/_&amp;-]+)\s+<\/div>/';
		preg_match_all($regexSystems, $gamePageUrl, $systemMatches);
		for($curSys = 0; $curSys < count($systemMatches[1]); $curSys++){
			try{
				//Sets the query with the $curSys
				$retriveSysId->bindParam(1, $systemMatches[1][$curSys]);	
				//Runs the query			
				$retriveSysId->execute();
				//Retrives the values from the query						
				$sysValue = $retriveSysId->fetch(PDO::FETCH_ASSOC);
				connectLangsToGame($appid,$sysValue['systemid'],$insertSysToGames);
				
			}
			catch(PDOException $e){
				handle_sql_errors($retriveSysId, $e->getMessage());
			}					
		}

	}
	function connectSysToGame($appid,$sysValue,$insertSysToGames){
		$insertSysToGames->bindParam(1, $appid);
		$insertSysToGames->bindParam(2, $sysValue);
		$insertSysToGames->execute();
	}		


	function getDetailsOnPage($db,$gamePageUrl,$appid){
		//Retrives the info for a Detail with the passed in name from the database
		$retriveDetId = $db->prepare("SELECT * FROM testdb.detail as a WHERE a.name = ?");
		$insertDetToGames = $db->prepare("insert into testdb.gamedetail values(?,?)");
		//The regular expression to pull the languages from the page.
		$regexGameDetail = '/<a\sclass=\"name\"\shref=\"https:\/\/store\.steampowered\.com\/search\/\?category(\d+)\=(\d+)\&snr=1_5_9__423\">([a-zA-Z0-9\s\-]+)<\/a>/';
		preg_match_all($regexGameDetail, $gamePageUrl, $detailMatches);

		for($curDet = 0; $curDet < count($detailMatches[3]); $curDet++){
			try{
				$passedDetail = $detailMatches[3][$curDet];
				$passedTBLName = 'testdb.detail';
				doesEntryExist($db,$passedTBLName,$passedDetail);
				//Sets the query with the $curSys
				$retriveDetId->bindParam(1, $detailMatches[3][$curDet]);	
				//Runs the query			
				$retriveDetId->execute();
				//Retrives the values from the query						
				$detValue = $retriveDetId->fetch(PDO::FETCH_ASSOC);
				connectDetToGame($appid,$detValue['detailid'],$insertDetToGames);
				
			}
			catch(PDOException $e){
				handle_sql_errors($retriveDetId, $e->getMessage());
			}					
		}
	}

	function connectDetToGame($appid,$detValue,$insertDetToGames){
		$insertDetToGames->bindParam(1, $appid);
		$insertDetToGames->bindParam(2, $detValue);
		$insertDetToGames->execute();
	}		

	function getVRDetailsOnPage($db,$gamePageUrl,$appid){
		//Retrives the info for a VRDetail with the passed in name from the database
		$retriveVRDetId = $db->prepare("SELECT * FROM testdb.vrdetails as a WHERE a.name = ?");
		$insertVRDetToGames = $db->prepare("insert into testdb.gamevrdetail values(?,?)");
		//The regular expression to pull the languages from the page.
		$regexVRSupport = '/<a\sclass=\"name\"\shref=\"https:\/\/store\.steampowered\.com\/search\/\?vrsupport\=(\d+)\">([a-zA-Z0-9\s\-\/]+)<\/a>/';
		preg_match_all($regexVRSupport, $gamePageUrl, $vrDetailMatches);

		for($curVRDet = 0; $curVRDet < count($vrDetailMatches[2]); $curVRDet++){
			try{
				$passedDetail = $vrDetailMatches[2][$curVRDet];
				$passedTBLName = 'testdb.vrdetails';
				doesEntryExist($db,$passedTBLName,$passedDetail);
				//Sets the query with the $curSys
				$retriveVRDetId->bindParam(1, $vrDetailMatches[2][$curVRDet]);	
				//Runs the query			
				$retriveVRDetId->execute();
				//Retrives the values from the query						
				$vrDetValue = $retriveVRDetId->fetch(PDO::FETCH_ASSOC);
				connectDetToGame($appid,$vrDetValue['vrid'],$insertVRDetToGames);
				
			}
			catch(PDOException $e){
				handle_sql_errors($retriveVRDetId, $e->getMessage());
			}					
		}
	}

	function connectVRDetToGame($appid,$vrDetValue,$insertVRDetToGames){
		$insertVRDetToGames->bindParam(1, $appid);
		$insertVRDetToGames->bindParam(2, $vrDetValue);
		$insertVRDetToGames->execute();
	}		

