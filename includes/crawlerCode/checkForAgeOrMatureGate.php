<?php

		function checkForAgeOrMatureGate($url){
		$context = stream_context_create(
    	array(
        	"http" => array(
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
        		)
    		)
		);	

		//Setting this to a variable in case it has no check.
		//We can just return the file_get_contents of the url and we wont have to call it from the steamCrawler.php
		$passedUrl = file_get_contents($url,false,$context);
		//print_r($http_response_header[6]);	
		$regexisAgeCheck = '/Location: https:\/\/store\.steampowered\.com\/agecheck\/app\/[0-9]+\/?/';
		$regexisMatureCheck = '/Location: https:\/\/store\.steampowered\.com\/app\/[0-9]+\/agecheck/';

		preg_match_all($regexisAgeCheck, $http_response_header[6], $regAgeMatches);
		preg_match_all($regexisMatureCheck, $http_response_header[6], $regMatMatches);		
		if(!empty($regAgeMatches[0])){
			//print_r('Age Match Not Empty');
			//echo '<br/>';
			//This is a remennt from an attempt to use curl to change the submit an age through the page 
			/*$postData = array(
				'ageDay' => '31',
				'ageMonth' => 'July',
				'ageYear' => '1993'
			);*/
			//Instead of submission I create a cookie which allows the crawler to walk enter the pages without hitting the redirect
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5); 
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//Keeping this as a memory of what could be done. Maybe I can fix it some time later
			curl_setopt($ch,CURLOPT_POSTFIELDS,''/*$postData*/);
			curl_setopt( $ch, CURLOPT_COOKIE, "birthtime=28801; path=/; domain=store.steampowered.com" );
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$results = curl_exec($ch);
			curl_close($ch);
			//The results are are like file_get_contents but from beyond the redirect
			return $results;
		}else if(!empty($regMatMatches[0])){
			//print_r('Mature Match Not Empty');
			//echo '<br/>';
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13");
			curl_setopt($ch,CURLOPT_POSTFIELDS,'');
			$strCookie = 'mature_content=' . 1 . '; path=/';
			curl_setopt( $ch, CURLOPT_COOKIE, $strCookie );
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$results = curl_exec($ch);
			curl_close($ch);
			//The results are are like file_get_contents but from beyond the redirect
			return $results;
		} else{
			return $passedUrl;
		}		

	}