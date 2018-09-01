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
print_r($matches[2]);

