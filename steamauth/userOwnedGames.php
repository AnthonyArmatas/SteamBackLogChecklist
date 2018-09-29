<?php
    if(!isset($_SESSION)) 
    { 
        session_start();
      	ini_set('max_execution_time', 300);
   
    } 

	require 'SteamConfig.php';
	$url = file_get_contents("https://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=".$steamauth['apikey']."&steamid=".$_SESSION['steamid']."&format=json&include_appinfo=1&include_played_free_games=1"); 
	$content = json_decode($url, true);
	$_SESSION['steam_ownedgamenum'] = $content['response']['game_count'];
	$_SESSION['steam_test'] = $content['response']['games'];
	
	for($curOwnedGame = 0; $curOwnedGame < $_SESSION['steam_ownedgamenum']; $curOwnedGame++){
		$_SESSION['steam_appid'][$curOwnedGame] =  $content['response']['games'][$curOwnedGame]['appid'];
		$_SESSION['steam_name'][$curOwnedGame] =  $content['response']['games'][$curOwnedGame]['name'];
		$_SESSION['steam_playtime_forever'][$curOwnedGame] =  $content['response']['games'][$curOwnedGame]['playtime_forever'];
		$_SESSION['steam_img_icon_url'][$curOwnedGame] =  $content['response']['games'][$curOwnedGame]['img_icon_url'];
		$_SESSION['steam_img_logo_url'][$curOwnedGame] =  $content['response']['games'][$curOwnedGame]['img_logo_url'];
	}

for($curGame = 0; $curGame < $_SESSION['steam_ownedgamenum']; $curGame++){
	$steamgameinfo['appid'][$curGame] = $_SESSION['steam_appid'][$curGame];
	$steamgameinfo['name'][$curGame] = $_SESSION['steam_name'][$curGame];
	$steamgameinfo['playtimeinsec'][$curGame] = $_SESSION['steam_playtime_forever'][$curGame];
	$steamgameinfo['img_icon_url'][$curGame] = $_SESSION['steam_img_icon_url'][$curGame];
	$steamgameinfo['img_logo_url'][$curGame] = $_SESSION['steam_img_logo_url'][$curGame];
}
	$steamgameinfo['curShownGames'] = $steamgameinfo['appid'];
	


//https://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=###&steamid=76561198013422592&format=json&include_appinfo=1&include_played_free_games=1 -->
?>



