<?php
//Some games on Steam have age filters (330840) and maturity filters (414700) [570 is a game with no filter].
//these have to be bypassed differently. 
$gameLink = 'https://store.steampowered.com/app/330840'; // 330840 | 414700 
file_get_contents($gameLink);
/////////////////////////////////////////////////////////////////////////////////////////////
////Game tag Regex///////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
//$regexNoEndSpace = '/<a href=\"https:\/\/store\.steampowered\.com\/tags\/en\/([a-zA-Z0-9\%\.\_\-]+)\/\?snr=1_5_9__409\"\sclass\=\"app_tag"\sstyle\=\"([\w\:\s;]+)?\">\s*\t*([a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;\-]+\s?[a-zA-Z0-9\'\.\/_&amp;-]+)\s+<\/a>/';
//preg_match_all($regexNoEndSpace, $gamePageContents, $idMatches);
//print_r($http_response_header);
//print_r($http_response_header[6]);
//echo '<br/>';
//http_response_header has a telltale sign of having a filter check. Its array entry for 6 will have a response will the word 'Location:' followed by the address
//It is auto directing to. Age Filters have the age check come before the appid Location and Mature filters have the check come after.
//For Example:
//Age Filter: 
//Location: https://store.steampowered.com/agecheck/app/330840/
//The Regex: To find it:
//Location: (https:\/\/store\.steampowered\.com\/agecheck\/app\/[0-9]+\/?)
$regexisAgeCheck = '/Location: https:\/\/store\.steampowered\.com\/agecheck\/app\/[0-9]+\/?/';
preg_match_all($regexisAgeCheck, $http_response_header[6], $tagMatches);
//print_r($tagMatches);
echo '<br/>';


//Mature Filter:
//Location: https://store.steampowered.com/app/414700/agecheck
//The Regex: To find it:
//Location: (https:\/\/store\.steampowered\.com\/app\/[0-9]+\/agecheck)
$regexisMatureCheck = '/Location: (https:\/\/store\.steampowered\.com\/app\/[0-9]+\/agecheck)/';
//preg_match_all($regexisMatureCheck, $http_response_header[6], $tagMatches);
//print_r($tagMatches);
//echo '<br/>';
//echo '<br/>';

if(empty($tagMatches[0])){
	print_r("nothing was returned");
	echo '<br/>';
}else{
//$url = 'http://store.steampowered.com/app/330840/';
$postData = array(
'ageDay' => '31',
'ageMonth' => 'July',
'ageYear' => '1993'
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $gameLink);
curl_setopt($ch, CURLOPT_TIMEOUT, 5); 
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
//$strCookie = 'snr=1_agecheck_agecheck__age-gate&ageDay=' . 1 . '&ageMonth=' . 'January'. '&ageYear=' . 1979 . '; path=/';
//$strCookie = 'lastagecheckage=' . 1 .'-January-' . 1979 . '; path=/';
$strCookie = 'lastagecheckage=1-January-1979; path=/';
curl_setopt( $ch, CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com" );
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);


//Curl info
//	print_r(curl_getinfo($ch));
//	echo '<br/>';
//	print_r($data);
//	echo '<br/>';
//	$resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//	print_r($resultStatus);
//	echo '<br/>';
//Error Check
//	echo curl_errno($ch) . '-' . curl_error($ch);
//close the connection
curl_close($ch);
echo $data; 

////////////////////////////////////////////////////////////////////////////////////////////////
//THIS IS HOW YOU GET PAST MATURE FILTER///////////////////////////////////////

$url = "http://store.steampowered.com/app/414700/";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13");
curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
$strCookie = 'mature_content=' . 1 . '; path=/';
curl_setopt( $ch, CURLOPT_COOKIE, $strCookie );

curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);

curl_close($ch);
echo $data; 

////////////////////////////////////////////////////////////////////////////////////////////////
//Test To See if we can pull back the OS once past the maturity filter
//$regexSystems = '/<div\sclass\=\"[a-zA-Z0-9\s_\-]+\"\s+data-os\=\"([a-zA-Z0-9]+)\">/';
//preg_match_all($regexSystems, $data, $systemMatches);
//print_r($systemMatches[1][0]);


}