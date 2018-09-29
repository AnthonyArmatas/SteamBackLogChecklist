<?php

ini_set('max_execution_time', 300);



	function populateOwnedGames($connToDB,$steamgameinfo){
		for($curgame = 0; $curgame <  $_SESSION['steam_ownedgamenum']; $curgame++){
			echo "<li class='ownedgamelistitem' id='" . $steamgameinfo['appid'][$curgame] . "' style='display: block;'>";
			echo "<div class='head' style='display: block;float: left'>";
			echo "<img src=http://media.steampowered.com/steamcommunity/public/images/apps/" . $steamgameinfo['appid'][$curgame] . "/" . $steamgameinfo['img_logo_url'][$curgame] . ".jpg>";
			echo "</div>";
			echo "<div class='body'>"; 
			echo " <div class='completedCheck' style='float: right;'><p id='completedCheckPara'>Completed</p><p><input id='CompleteCheckBox' type='checkbox'value='None'></p></div>";
			echo "<p class='gameName' style='display: inline-block;'>" . $steamgameinfo['name'][$curgame] . "</p>";
			echo '<br/>';	  
			$tags = showGamesTags($connToDB,$steamgameinfo['appid'][$curgame]);
			echo "<p class='gameTags' style='display: inline;'>Tags: " . $tags . "</p>";
			echo '<br/>';	  
			$details = showGamesDetails($connToDB,$steamgameinfo['appid'][$curgame]);
			echo "<p class='gameDetails' style='display: inline;'>Game Details: " . $details . "</p>";
			echo '<br/>';	  			
			$vrDetails = showGamesVRDetails($connToDB,$steamgameinfo['appid'][$curgame]);
			echo "<p class='gameVRDetails' style='display: inline;'>VR Info: " . $vrDetails . "</p>";
			echo '<br/>';	  			
			$systems = showGamesSystems($connToDB,$steamgameinfo['appid'][$curgame]);
			echo "<p class='gameSystems' style='display: inline;'>Game OS: " . $systems . "</p>";
			echo '<br/>';	
			$lang = showGamesLanguages($connToDB,$steamgameinfo['appid'][$curgame]);
			echo "<p class='gameLanguages' style='display: inline;'>Supported Languages: " . $lang . "</p>";
			echo '<br/>';				  			

			echo "</div>";
			//echo '<br/>';	  						 	
			echo "</li>";

		}	
	}
	function showGamesTags($connToDB,$appId){
		$loadGameTags =	$connToDB->prepare("SELECT tag.name
												FROM tag AS tag
												INNER JOIN gametag AS gametag
												WHERE tag.tagid = gametag.tagid
												AND gametag.appid = ?;");
		$loadGameTags->bindParam(1, $appId);	
  		$loadGameTags->execute();
  		$ResultsFromDB = $loadGameTags->fetchAll(PDO::FETCH_COLUMN);
  		$i = 0;
		$len = count($ResultsFromDB);
		$tagString = '';
		foreach($ResultsFromDB as $tags){
			if ($i == ($len - 1)){
				$tagString .= $tags;
			}else{
				$tagString .= $tags . ", ";
			}
			$i++;
		}
		return $tagString;
	}
		function showGamesDetails($connToDB,$appId){
		$loadGameDetails =	$connToDB->prepare("SELECT detail.name
												FROM detail AS detail
												INNER JOIN gamedetail AS gamedetail
												WHERE detail.detailid = gamedetail.detailid
												AND gamedetail.appid = ?;");
		$loadGameDetails->bindParam(1, $appId);	
  		$loadGameDetails->execute();
  		$ResultsFromDB = $loadGameDetails->fetchAll(PDO::FETCH_COLUMN);
  		$i = 0;
		$len = count($ResultsFromDB);
		$detailsString = '';
		foreach($ResultsFromDB as $Details){
			if ($i == ($len - 1)){
				$detailsString .= $Details;
			}else{
				$detailsString .= $Details . ", ";
			}
			$i++;
		}
		return $detailsString;
	}
		function showGamesVRDetails($connToDB,$appId){
		$loadGameVR =	$connToDB->prepare("SELECT vrdetails.name
												FROM vrdetails AS vrdetails
												INNER JOIN gamevrdetail AS gamevrdetail
												WHERE vrdetails.vrid = gamevrdetail.vrid
												AND gamevrdetail.appid = ?;");
		$loadGameVR->bindParam(1, $appId);	
  		$loadGameVR->execute();
  		$ResultsFromDB = $loadGameVR->fetchAll(PDO::FETCH_COLUMN);
  		$i = 0;
		$len = count($ResultsFromDB);
		$vrDetailsString = '';
		foreach($ResultsFromDB as $vr){
			if ($i == ($len - 1)){
				$vrDetailsString .= $vr;
			}else{
				$vrDetailsString .= $vr . ", ";
			}
			$i++;
		}
		return $vrDetailsString;
	}
		function showGamesSystems($connToDB,$appId){
		$loadGameSystem =	$connToDB->prepare("SELECT system.name
												FROM system AS system
												INNER JOIN gamesystem AS gamesystem
												WHERE system.systemid = gamesystem.systemid
												AND gamesystem.appid = ?;");
		$loadGameSystem->bindParam(1, $appId);	
  		$loadGameSystem->execute();
  		$ResultsFromDB = $loadGameSystem->fetchAll(PDO::FETCH_COLUMN);
  		$i = 0;
		$len = count($ResultsFromDB);
		$systemString = '';
		foreach($ResultsFromDB as $system){
			if ($i == ($len - 1)){
				$systemString .= $system;
			}else{
				$systemString .= $system . ", ";
			}
			$i++;
		}
		return $systemString;
	}
			function showGamesLanguages($connToDB,$appId){
		$loadGameLang =	$connToDB->prepare("SELECT languages.language_used
												FROM languages AS languages
												INNER JOIN gamelang AS gamelang
												WHERE languages.langid = gamelang.langid
												AND gamelang.appid = ?;");
		$loadGameLang->bindParam(1, $appId);	
  		$loadGameLang->execute();
  		$ResultsFromDB = $loadGameLang->fetchAll(PDO::FETCH_COLUMN);
  		$i = 0;
		$len = count($ResultsFromDB);
		$langString = '';
		foreach($ResultsFromDB as $languages){
			if ($i == ($len - 1)){
				$langString .= $languages;
			}else{
				$langString .= $languages . ", ";
			}
			$i++;
		}
		return $langString;
	}


?>