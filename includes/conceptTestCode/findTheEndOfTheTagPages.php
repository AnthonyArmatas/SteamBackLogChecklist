<?php

$currentPage = 126;
$tagid = 3871;
$lastPage;
//A link to the steam store page where no results are found. the id 3871 only goes to 125
//The link is using the variables to make sure that it will work
$tempUrl = "https://store.steampowered.com/search/?sort_by=Name_ASC&tags=$tagid&page=$currentPage";
$steamUrl = file_get_contents($tempUrl);
$errorRegex = '/<p>No results were returned for that query\.<\/p>/';
$steamUrls = file_get_contents($tempUrls);
$tempval = "Hello World";
$tempsearch = '/([a-zA-Z0-9\s])+/';

preg_match_all($errorRegex, $steamUrl, $matches);

print_r($matches);
print_r($matches[0]);

