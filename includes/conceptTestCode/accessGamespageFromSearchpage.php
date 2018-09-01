<?php
//This code accesses the a games page from the steam search page using the games app id.
//It also contains code which checks the games store page for its tags and details. 

$currentPage = 1;
$tagid = 19;
$tempUrl = "https://store.steampowered.com/search/?sort_by=Name_ASC&tags=$tagid&page=$currentPage";
$steamUrl = file_get_contents($tempUrl);
$tagsRegex = '/<a href=\"https:\/\/store\.steampowered\.com\/app\/(\d+)\/(\w+)\/([a-zA-Z0-9?=_]+)\"/';

preg_match_all($tagsRegex, $steamUrl, $idMatches);

//Prints out the appid
//print_r($idMatches[1]);
//Prints out the appname
////For PDO
//$gameLink = 'https://store.steampowered.com/app/?';
$gameLink = 'https://store.steampowered.com/app/' . $idMatches[1][0];
//print_r($gameLink);

$gamePageContents = file_get_contents($gameLink);
/////////////////////////////////////////////////////////////////////////////////////////////
////Game tag Regex///////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
$regexNoEndSpace = '/<a href=\"https:\/\/store\.steampowered\.com\/tags\/en\/([a-zA-Z0-9\%\.\_\-]+)\/\?snr=1_5_9__409\"\sclass\=\"app_tag"\sstyle\=\"([\w\:\s;]+)?\">\s*\t*([a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;-]+)\s+<\/a>/';
preg_match_all($regexNoEndSpace, $gamePageContents, $idMatches);
//print_r($idMatches);
//print_r($idMatches[3][0]);
/*
----------------------------------------------------------------------------------------------
---Group three idMatches[3] is the array with tag names. idMatches[3][0] gets the string------
----------------------------------------------------------------------------------------------

//This regex is shorter, but has long tailing spaces thanks to the \s+ inside of the ([]+)
$regexEndSpace = '/<a href=\"https:\/\/store\.steampowered\.com\/tags\/en\/([a-zA-Z0-9\%\.\_\-]+)\/\?snr=1_5_9__409\"\sclass\=\"app_tag"\sstyle\=\"([\w\:\s;]+)?\">\s*\t*([a-zA-Z0-9\s\'\.\/_&amp;-]+)<\/a>/';

//This regex does not have trailing spaces but is longer and will only take into account those enteries up to three words or less (I beleive thats the max that steam currently uses).
$regexNoEndSpace = '/<a href=\"https:\/\/store\.steampowered\.com\/tags\/en\/([a-zA-Z0-9\%\.\_\-]+)\/\?snr=1_5_9__409\"\sclass\=\"app_tag"\sstyle\=\"([\w\:\s;]+)?\">\s*\t*([a-zA-Z0-9\'\.\/_&amp;-]+\s?[a-zA-Z0-9\'\.\/_&amp;-]+\s?[a-zA-Z0-9\'\.\/_&amp;-]+)\s+<\/a>/';
*/
/////////////////////////////////////////////////////////////////////////////////////////////
////Game Detail Regex////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
$regexGameDetail = '/<a\sclass=\"name\"\shref=\"https:\/\/store\.steampowered\.com\/search\/\?category(\d+)\=(\d+)\&snr=1_5_9__423\">([a-zA-Z0-9\s\-]+)<\/a>/';

$gameDetailLink = 'https://store.steampowered.com/app/570';
//print_r($gameLink);
$gamePageDetailContents = file_get_contents($gameDetailLink);
preg_match_all($regexGameDetail, $gamePageDetailContents, $detailMatches);
//print_r($detailMatches);


/////////////////////////////////////////////////////////////////////////////////////////////
/////VR Support Regex////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////

$regexVRSupport = '/<a\sclass=\"name\"\shref=\"https:\/\/store\.steampowered\.com\/search\/\?vrsupport\=(\d+)\">([a-zA-Z0-9\s\-\/]+)<\/a>/';
preg_match_all($regexVRSupport, $gamePageDetailContents, $vRSupportMatches);
//print_r($vRSupportMatches);


/////////////////////////////////////////////////////////////////////////////////////////////
/////Languages Regex////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////

$regexLanguages = '/<td\sstyle\=\"[a-zA-Z0-9\s:\-;]+\"\s+class=\"ellipsis\">\s+([a-zA-Z0-9\-\(\)]+?[a-zA-Z0-9\-\(\)\s]+?)\s+<\/td>/';
preg_match_all($regexLanguages, $gamePageDetailContents, $LanguageMatches);
//print_r($LanguageMatches);



/////////////////////////////////////////////////////////////////////////////////////////////
/////Systems Regex///////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////

$regexSystems = '/<div\sclass\=\"[a-zA-Z0-9\s_\-]+\"\s+data-os\=\"[a-zA-Z0-9]+\">\s+([a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-\+]+\s?[a-zA-Z0-9\'\.\/_&amp;-]+)\s+<\/div>/';
preg_match_all($regexSystems, $gamePageDetailContents, $regexSystemsMatches);
print_r($regexSystemsMatches);

//print_r($gamePageDetailContents);