<?php
ini_set('max_execution_time', 240);

function isLastPage($currentPage, $tagid){
	$finalPageRegex = '/<p>No results were returned for that query\.<\/p>/';
	$tempUrl = "https://store.steampowered.com/search/?sort_by=Name_ASC&tags=$tagid&page=$currentPage";
	$steamUrl = file_get_contents($tempUrl);

	preg_match_all($finalPageRegex, $steamUrl, $matches);
	return $matches[0];
}
$currentPage = 1;
$tagid = 3871;
$lastPage;

/*
if(empty(isLastPage($currentPage, $tagid))){
	print_r("false");
}else{
	print_r(isLastPage($currentPage, $tagid));
}
*/
While(empty(isLastPage($currentPage, $tagid))) {
	print_r("Page " . $currentPage . " is not the last page");
	echo '<br/>';
	$currentPage++;
}
	print_r("Page " . $currentPage . " is the last page!!!!!!!!!!!!!!!!!");