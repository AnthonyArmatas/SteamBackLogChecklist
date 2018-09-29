<?php

	function displayTagsForGames($connToDB,$listOfOwnedGames){
  		$loadTagFilter =	$connToDB->prepare("SELECT tag.tagid, tag.name
												FROM tag AS tag
												INNER JOIN gametag AS gametag
												WHERE tag.tagid = gametag.tagid
												AND gametag.appid = ?;");

		$tagExsists = array();
		$numOfTags = 0;
		foreach($listOfOwnedGames as $curGame){
		  		$loadTagFilter->bindParam(1, $listOfOwnedGames[$curGame]);	
  				$loadTagFilter->execute();
  				$ResultsFromDB = $loadTagFilter->fetchAll();
  				for($curTag = 0; $curTag < count($ResultsFromDB); $curTag++){
  					if(in_array($ResultsFromDB[$curTag]['tagid'], $tagExsists)){
  						continue;
  					}else{
  						array_push($tagExsists,$ResultsFromDB[$curTag]['tagid']);
  						echo "<button id='" . $ResultsFromDB[$curTag]['tagid'] . "' style='display: inline-block;'>" . $ResultsFromDB[$curTag]['name'] . "</button>";
  						$numOfTags++;
  					}

  				}
		}
		echo "Num of tags " . $numOfTags;
	}

	function displayAllFilters($connToDB){
		$tableNames = array('tag','detail','vrdetails','system','languages');
		$sectionHeaders = array('Game Tags','Game Details','VR Info','Game OS','Supported Languages');
		$sectionClass = array('buttonTags','buttonDetails','buttonVRDetails','buttonSystems','buttonLanguages');
		$headerCounter = 0;
		foreach($tableNames as $tables){
			$loadFilters =	$connToDB->prepare("SELECT * FROM $tables;");		
			$loadFilters->execute();	
			//echo "ON TABLE " . 	$tables;
			$ResultsFromDB = $loadFilters->fetchAll();
			//print_r($ResultsFromDB);
			echo "<div id='" . $tables . "FilterContainer'>";
			echo "<h3 class='sectionHeader'>" . $sectionHeaders[$headerCounter] . "</h3>";
			for($curTag = 0; $curTag < count($ResultsFromDB); $curTag++){
				//Changing $sectionClass[$headerCounter] to $sectionClass[0] so all the buttons are of class buttonTags and will be effected by the main jquery
				echo "<button class='" . $sectionClass[$headerCounter] . " filterbutton buttDisabled' id='" . $ResultsFromDB[$curTag][0] . "' style='display: inline-block;'>" . $ResultsFromDB[$curTag][1] . "</button>";
			}
			echo "</div>";
			$headerCounter++;
			

		}

	}
	function settingEnabled($enabledFilters ){
		$steamgameinfo['curEnabledTags'] = $enabledFilters;
		?>
			<script> alert($steamgameinfo['curEnabledTags']); </script>
		<?php
	}
?>