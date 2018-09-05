<?php
	include 'addDetailsToDataBase.php';

	function getTagsOnPage($db,$gamePageUrl,$appid){
		//Retrives the info for a tag with the passed in name from the database
		$retriveTagId = $db->prepare("SELECT * FROM SBCDatabase.tag as a WHERE a.name = ?");
		$insertTagsToGames = $db->prepare("insert into SBCDatabase.gametag values(?,?)");

		//The regular expression to pull the tags from the page. It collects all of the tags but since they are so diverse it ends with a space or two and needs to be trimmed
		$regexGameTags = '/<a href=\"https:\/\/store\.steampowered\.com\/tags\/en\/[a-zA-Z0-9\%\.\_\-]+\/\?snr=1_5_9__409\"\sclass\=\"app_tag"\sstyle\=\"[\w\:\s;]+.?\">\s*([a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-]*)\s+<\/a>/';
		//All of the tags are in the variable $tagMatches from the regualr expression. We are using tagMatches[3] because that is the Capturing group of the page text which appears to the user.
		preg_match_all($regexGameTags, $gamePageUrl, $tagMatches);
		//echo '<br/>';			
		//print_r($tagMatches[1]);
		//echo '<br/>';			

		
		for($curTag = 0; $curTag < count($tagMatches[1]); $curTag++){
			try{
				//Sets the query with the $curTag and trims the white space
				$trimmedtagMatche = trim($tagMatches[1][$curTag]);
				//Binds the trimmed value to the query
				$retriveTagId->bindParam(1, $trimmedtagMatche);	
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
    	//Uncomment if somthing seems off
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
		$retriveLangId = $db->prepare("SELECT * FROM SBCDatabase.languages as a WHERE a.language_used = ?");
		$insertLangsToGames = $db->prepare("insert into SBCDatabase.gamelang values(?,?)");
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
		$retriveSysId = $db->prepare("SELECT * FROM SBCDatabase.system as a WHERE a.name = ?");
		$insertSysToGames = $db->prepare("insert into SBCDatabase.gamesystem values(?,?)");
		//The regular expression to pull the languages from the page.
		//$regexSystems = '/<div\sclass\=\"[a-zA-Z0-9\s_\-]+\"\s+data-os\=\"[a-zA-Z0-9]+\">\s+([a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-\+]+\s?[a-zA-Z0-9\'\.\/_&amp;-]+)\s+<\/div>/';
		//The above regex was used to pull the name from the sysreq_tab on the steam page. This did not work for games with only one OS and no tabs
		//Below pulls from the data-os section and get the system abbreviation
		//$regexSystems = '/<div\sclass\=\"[a-zA-Z0-9\s_\-]+\"\s+data-os\=\"([a-zA-Z0-9]+)\">/';
		//Above is the regex I was using to get the data-os data originally
		//If I understand micro optimization, if I change it to below, it will run faster
		//because of how specific it is. It will not stop look at every div but every data and so on.
		$regexSystems = '/data-os=\"([win|mac|linux]+)\"/';
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
		$retriveDetId = $db->prepare("SELECT * FROM SBCDatabase.detail as a WHERE a.name = ?");
		$insertDetToGames = $db->prepare("insert into SBCDatabase.gamedetail values(?,?)");
		//The regular expression to pull the languages from the page.
		$regexGameDetail = '/<a\sclass=\"name\"\shref=\"https:\/\/store\.steampowered\.com\/search\/\?category\d+\=\d+\&snr=1_5_9__423\">([a-zA-Z0-9\s\-]+)<\/a>/';
		preg_match_all($regexGameDetail, $gamePageUrl, $detailMatches);
		for($curDet = 0; $curDet < count($detailMatches[1]); $curDet++){
			try{
				$passedDetail = $detailMatches[1][$curDet];
				$passedTBLName = 'SBCDatabase.detail';
				doesEntryExist($db,$passedTBLName,$passedDetail);
				//Sets the query with the $curSys
				$retriveDetId->bindParam(1, $detailMatches[1][$curDet]);
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
		$retriveVRDetId = $db->prepare("SELECT * FROM SBCDatabase.vrdetails as a WHERE a.name = ?");
		$insertVRDetToGames = $db->prepare("insert into SBCDatabase.gamevrdetail values(?,?)");
		//The regular expression to pull the languages from the page.
		$regexVRSupport = '/<a\sclass=\"name\"\shref=\"https:\/\/store\.steampowered\.com\/search\/\?vrsupport\=\d+\">([a-zA-Z0-9\s\-\/]+)<\/a>/';
		preg_match_all($regexVRSupport, $gamePageUrl, $vrDetailMatches);
		for($curVRDet = 0; $curVRDet < count($vrDetailMatches[1]); $curVRDet++){
			try{
				$passedDetail = $vrDetailMatches[1][$curVRDet];
				$passedTBLName = 'SBCDatabase.vrdetails';
				doesEntryExist($db,$passedTBLName,$passedDetail);
				//Sets the query with the $curSys
				$retriveVRDetId->bindParam(1, $vrDetailMatches[1][$curVRDet]);				
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

