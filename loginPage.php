
<?php
	//Have to put the session start at the begining of every page that uses it
	ini_set('max_execution_time', 300);	
	session_start();


 ?>
<?php 
	//All of the extraneous files needed to make the page work
	require 'steamauth/steamauth.php';
	require 'steamauth/Steamconfig.php';
	require 'steamauth/userInfo.php';
	require 'steamauth/userOwnedGames.php';
	require 'includes/dbh.php';	
	require 'filterbuttons.php';
	require 'populateOwnedGames.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Steam Backlog Checklist</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--Bringing in the CSS to format the page-->
	<link  href='StyleSheets/scbStyle.css' rel='stylesheet' type='text/css' />
	<link  href='StyleSheets/w3.css' rel='stylesheet' type='text/css' />
	<!--this script allows me to use jQuery with a link instead of downloading it myself-->
	<script
		src="https://code.jquery.com/jquery-3.3.1.min.js"
		integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
		crossorigin="anonymous"> 	
	</script>


	<script>
		//Array which will hold all of the games set to complete
		var curCompletedGames = new Array();
		//Array which will hold the current randomly chosen game.
		//This could probably be done with an object or better data type but I knew I could develop it with an array in the moment
		//So I went for imidiate functionality. I will change this in the future
		var randomGameChoice = new Array();


		var curHiddenGames = new Array();
		var curShownGames = new Array();
		var curEnabledGames = {};
			curEnabledGames.gameTags = new Array();
			curEnabledGames.gameDetails = new Array();
			curEnabledGames.gameVRDetails = new Array();
			curEnabledGames.gameSystems = new Array();
			curEnabledGames.gameLanguages = new Array();
		var curEnabledTags = {};
			curEnabledTags.gameTags = new Array();
			curEnabledTags.gameDetails = new Array();
			curEnabledTags.gameVRDetails = new Array();
			curEnabledTags.gameSystems = new Array();
			curEnabledTags.gameLanguages = new Array();
		var curNegatedTags = {};
			curNegatedTags.gameTags = new Array();
			curNegatedTags.gameDetails = new Array();
			curNegatedTags.gameVRDetails = new Array();
			curNegatedTags.gameSystems = new Array();
			curNegatedTags.gameLanguages = new Array();	
		var gameFiltersTypes = ["gameTags", "gameDetails", "gameVRDetails", "gameSystems", "gameLanguages"];						
		var buttonFilterTypes = ["buttonTags", "buttonDetails", "buttonVRDetails", "buttonSystems", "buttonLanguages"];						
		//var curEnabledTags = new Array();
		//var curNegatedTags = new Array();

		$(document).ready(function(){
			curShownGames = getAllShownGames();

			$(".filterbutton").click(function(){
				// $(this) referese to the button clicked and .text() gets the text shown on the button. The Name of the filter
				var buttonText = $(this).text();
				var buttonType = $(this).attr("class").split(' ')[0];
				var buttonsGameClass = "";
				switch(buttonType){
					case buttonFilterTypes[0]:
						buttonsGameClass = gameFiltersTypes[0];
						break;
					case buttonFilterTypes[1]:
						buttonsGameClass = gameFiltersTypes[1];
						break;
					case buttonFilterTypes[2]:
						buttonsGameClass = gameFiltersTypes[2];
						break;
					case buttonFilterTypes[3]:
						buttonsGameClass = gameFiltersTypes[3];
						break;
					case buttonFilterTypes[4]:
						buttonsGameClass = gameFiltersTypes[4];
						break;				
				}

				//This looks at the list item which has the id of its games appid
				//var gameli = $( "li.ownedgamelistitem > div.body > p.gameTags:contains(" + buttonText + ")" ).parent().parent();
				//var gameliNOT = $( "li.ownedgamelistitem > div.body > p.gameTags:not(:contains(" + buttonText + "))" ).parent().parent();
				var gameli = $( "li.ownedgamelistitem > div.body > p." + buttonsGameClass + ":contains(" + buttonText + ")" ).parent().parent();
				var gameliNOT = $( "li.ownedgamelistitem > div.body > p." + buttonsGameClass + ":not(:contains(" + buttonText + "))" ).parent().parent();

				//Checks to see if the tag has been enabled yet
				if($(this).hasClass("buttDisabled")){
					//sets it to the enabled class
					$(this).removeClass("buttDisabled").addClass("buttEnabled");
					//Adds the filter to the enabled list
					curEnabledTags[buttonsGameClass].push(buttonText);

					if(getLength(curEnabledTags) == 1){
						if(getLength(curNegatedTags) === 0){
							curShownGames = [];
							curShownGames = getAllShownGames();
						}
						gameli.each(function (index, value) {
							gameappid = $(value).attr('id');
							//Adds every game with the tag to enabled.
							curEnabledGames[buttonsGameClass].push(gameappid);
						});
						//Hides each game without the filter
						gameliNOT.each(function (index, value) {
							//Checks to see if the game is already hidden by another filter
							if($(value).is(":hidden")){
								//Skips if already hidden
							}else{
								//If not already hidden adds its game id to the list of hidden games for later checks
								gameappid = $(value).attr('id');
								curHiddenGames.push($(value).attr('id'));
								curShownGames = removeFromArray(curShownGames,gameappid);
								//Hide used to be in here and done individually
								//But it was faster to hide all that did not fit the filter than call it for each individual one.
							}
							
						});
						//Hides the games list items
						gameliNOT.hide();
					}else{
						gameli.each(function (index, value) {
							gameappid = $(value).attr('id');
							if(curEnabledGames[buttonsGameClass].indexOf(gameappid) == -1){
								curEnabledGames[buttonsGameClass].push(gameappid);
							}
						});
						var tempShownGames = curShownGames;
						$.each(curShownGames,function(index, value){
							var curgameId = value;
							var liAppid = "#" + curgameId;
							var gametags = $(liAppid).children("div.body").children("p." + buttonsGameClass).text();						
							//If the clicked filter is not within the shown games tags it is hidden and removed from the shown games	
							if(gametags.indexOf(buttonText) == -1){
   								//if it does, it is remoed from because it still needs to be hidden
   								//alert(tempShownGames);
   								tempShownGames = removeFromArray(tempShownGames,curgameId);
								//alert(tempShownGames);	
								curHiddenGames.push(gameappid);
								$(liAppid).hide();
							}							
						});
						 curShownGames = tempShownGames;
					}

				}else if($(this).hasClass("buttEnabled")){
					//sets it to the enabled class
					$(this).removeClass("buttEnabled").addClass("buttNegated");
					//Adds the tag to the negated tag array for checks later
					curNegatedTags[buttonsGameClass].push(buttonText);
					//removes the now negated tag from the enabled tag array
					curEnabledTags[buttonsGameClass] = removeFromArray(curEnabledTags[buttonsGameClass],buttonText);
					//Removes the newly negated games from enabled games			
					gameli.each(function (index, value) {
						//This checks to make sure that if a game has a tag that is no longer enabled and still has other tags that are, it is not removed from curEnabledGames 
							gameappid = $(value).attr('id');
							var liAppid = "#" + gameappid;
							//gameTags, gameDetails, gameVRDetails, gameSystems, gameLanguages
							//Checks all of each game filter, for enabled filters			 
							for(var i = 0; i < gameFiltersTypes.length; i++){
								var isOtherwiseEnabled= false;
								var gametags = $(liAppid).children("div.body").children("p." + gameFiltersTypes[i] ).text();
								//Goes through each of the enabled filter types to see if the game has those filters
								$.each(curEnabledTags[gameFiltersTypes[i]],function(index, value){
										if(gametags.indexOf(value) != -1){
											//The game needs to only have one filter enabled
											isOtherwiseEnabled = true;										
										} 
										//If a filter is enabled we break out of the loop for the current filter. We then move on to the next filter type for the game to see if it has somthing enabled there.
										if(isOtherwiseEnabled == true){
											return
										}									
									});
								if(isOtherwiseEnabled == false){
									curEnabledTags[gameFiltersTypes[i]] = removeFromArray(curEnabledTags[gameFiltersTypes[i]],gameappid);
								}
							}
					});
					//Gets the total
					var totalEnabledGameFilterLength = getcurEnableGamesLength(totalEnabledGameFilterLength);
					if(totalEnabledGameFilterLength != 0){
						//Goes through the enabled games and shows those who meet the criteria and are not negated
						$.each(gameliNOT,function(index,value){
							gameappid = $(value).attr('id');
							var liAppid = "#" + gameappid;

							if(curShownGames.indexOf(gameappid) != -1){
								//If it is already shown, you can ignore it
							}else{
								var hasFilter= false;
								for(var i = 0; i < gameFiltersTypes.length; i++){
									var gametags = $(liAppid).children("div.body").children("p." + gameFiltersTypes[i] ).text();
									//We check to make sure the game meets all of the enabled filter criteria
									$.each(curEnabledTags[gameFiltersTypes[i]],function(index, value){
										//We want it to have each of the enabled filters to be able to be shown
										if(gametags.indexOf(value) == -1){
											hasFilter = true;
										}
										if(hasFilter == true){
											return
										}
									});
									//Checks the games tags to see if the game is already negated
									$.each(curNegatedTags[gameFiltersTypes[i]],function (index, value){							
										//we dont want it to have any of the negated filters
										if(gametags.indexOf(value) != -1){
											hasFilter = true;
										}
										if(hasFilter == true){
											return
										}										
									});	
									if(hasFilter == true){
										return
									}																
								}//End of for
								if(hasFilter == false){
									curShownGames.push(gameappid);
									$(liAppid).show();
									curHiddenGames = removeFromArray(curHiddenGames,gameappid);
								}
							}
						});
					} else{
						//If no games are enabled and you are negating a game (there may be other games negated)
						gameliNOT.each(function (index, value){
							gameappid = $(value).attr('id');;
							var liAppid = "#" + gameappid;

							if(curShownGames.indexOf(gameappid) != -1){
								//If it is already shown, you can ignore it
							}else{
								var hasFilter= false;
								for(var i = 0; i < gameFiltersTypes.length; i++){
									var gametags = $(liAppid).children("div.body").children("p." + gameFiltersTypes[i] ).text();
									//Checks the games tags to see if the game is already negated
									$.each(curNegatedTags[gameFiltersTypes[i]],function (index, value){							
										//we dont want it to have any of the negated filters
										if(gametags.indexOf(value) != -1){
											hasFilter = true;
										}
										if(hasFilter == true){
											return
										}
									});			
								}								
								if(hasFilter == false){
									curShownGames.push(gameappid);
									$(liAppid).show();
									curHiddenGames = removeFromArray(curHiddenGames,gameappid);
								}
							}
						});
					}


					//Walks through each of the games with the tag
					gameli.each(function (index, value) {
						//Checks to see if the game is already hidden by another filter
						if($(value).is(":hidden")){
							//Skips if already hidden
						}else{
							//If not already hidden adds its game id to the list of hidden games for later checks
							gameappid = $(value).attr('id');
							curShownGames = removeFromArray(curShownGames,gameappid);
							curHiddenGames.push(gameappid);
						}
					});
					//Hides the games list item					
					gameli.hide();
				}else{
					//Sets the class back to disable the button
					$(this).removeClass("buttNegated").addClass("buttDisabled");
					//removed the tag from the array
					curNegatedTags[buttonsGameClass] = removeFromArray(curNegatedTags[buttonsGameClass],buttonText);
					//Go through each of the games which has its negation removed and check if any meet the criteria to be shown again
					gameli.each(function(index,value){
							var gameappid = $(value).attr('id');
							liAppid = "#" + gameappid;
							var hasFilter= false;							
							for(var i = 0; i < gameFiltersTypes.length; i++){
								var gametags = $(value).children("div.body").children("p." + gameFiltersTypes[i] ).text();
								//We check to make sure the game meets all of the enabled filter criteria
								$.each(curEnabledTags[gameFiltersTypes[i]],function(index, value){
									//We want it to have each of the enabled filters
									if(gametags.indexOf(value) == -1){
										hasFilter = true;
									}
									if(hasFilter == true){
										return
									}									
								});
								//Checks the games tags to see if the game is already negated
								$.each(curNegatedTags[gameFiltersTypes[i]],function (index, value){
									//we dont want it to have any of the negated filters
									if(gametags.indexOf(value) != -1){
										hasFilter = true;
									}
									if(hasFilter == true){
										return
									}									
								});
								if(hasFilter == true){
									return
								}								
							}
							if(hasFilter == false){
								curShownGames.push(gameappid);
								$(liAppid).show();
								curHiddenGames = removeFromArray(curHiddenGames,gameappid);
							}							
					});				
				}
				updateCompletePercentage();
			});

			function getAllShownGames(){
				var shownGames = new Array();		
				var gameli = $("li.ownedgamelistitem");
				gameli.each(function(index,value){
					gameappid = $(value).attr('id');
					shownGames.push(gameappid);
				});
				return shownGames;

			}

			function removeFromArray(array, valToRemove){
				array = jQuery.grep(array, function(value) {
  										return value != valToRemove;
									});
				return array;
			}
			function getLength(array){
				var numofGameEntries = 0;
				$.each(array,function(index, value){
					numofGameEntries = numofGameEntries + value.length;
				});
				return numofGameEntries;
			}
			function getcurEnableGamesLength(totalEnabledGameFilterLength){
					for(var i = 0; i < gameFiltersTypes.length; i++){
						totalEnabledGameFilterLength = totalEnabledGameFilterLength + curEnabledGames[gameFiltersTypes[i]].length;
					}
					return totalEnabledGameFilterLength;			
			}


			//Functionality for when a game is clicked completed
			$("input[type='checkbox']").change(function(){
				if($(this).val() == 'None'){
					$(this).val('Checked');
				}else{
					$(this).val('None');
				}
				updateCompletePercentage();

			});
			function updateCompletePercentage(){
				var numOfCompleteGames = 0;
				$.each(curShownGames,function(index, value){
					var curgameId = value;
					var liAppid = "#" + curgameId;
					var completeStatus = $(liAppid).children("div.body").children("div.completedCheck").children("p").children("#CompleteCheckBox").val();
					if(completeStatus == 'Checked'){
						numOfCompleteGames = numOfCompleteGames + 1;
					}
				});
				var shownGamepercentage = (numOfCompleteGames/curShownGames.length) * 100;
				$("#completedPercentageBar").css("width", shownGamepercentage + "%");
				$("#completedPercentageNumber").text((shownGamepercentage.toFixed(2) + "%"));

				//Changed Color with percentage completed
				updatePercentageColor(shownGamepercentage);
			}


			function updatePercentageColor(shownGamepercentage){
				if(shownGamepercentage < 25){
					//Logo "Lighter" Blue
					$("#completedPercentageBar").css("background-color","#2286de");
				}else if(shownGamepercentage > 25 && shownGamepercentage < 50){
					//Logo "Darker" Blue					
					$("#completedPercentageBar").css("background-color","#007bac");

				}else if(shownGamepercentage > 50 && shownGamepercentage < 75){
					//Logo "Darker" Green					
					$("#completedPercentageBar").css("background-color","#068e4e");

				}else if(shownGamepercentage > 75 && shownGamepercentage < 100){
					//Logo "Darker" Green					
					$("#completedPercentageBar").css("background-color","#8da356");

				}else if(shownGamepercentage == 100){
					//Logo brown					
					$("#completedPercentageBar").css("background-color","#b6972f");
				}
			}

			/*
			//Works the same as input[type='checkbox']
			$("#CompleteCheckBox").click(function(){
				alert("Checked the click");
			});
			*/		
								
			//Select a random game
			$(".randombutton").click(function(){
				//Gets the random game from the games currently shown
				var chosenGameIndex = Math.floor((Math.random() * curShownGames.length));
				var curgameId = curShownGames[chosenGameIndex];

				if(randomGameChoice.length == 0){
					randomGameChoice.push(curgameId);
					var liAppid = "#" + curgameId;
					//Changes the border color of the chosen game to an eyecatching yellow
					$(liAppid).css("border-color","#f2ff00");
				}else{
					//Gets the last randomly chosen gam e
					var liAppid = "#" + randomGameChoice[0];
					//Sets its border back to the greysilver
					$(liAppid).css("border-color","#C0C0C0");
					//Removes the first random game
					randomGameChoice.pop();
					//And adds the newly chosen game
					randomGameChoice.push(curgameId);
					liAppid = "#" + curgameId;
					$(liAppid).css("border-color","#f2ff00");
					

				}
				var gameidPosition = document.getElementById(curgameId);
				//Will scrollIntoView will not work on Internet Explorer or Safari but it works Chrome,Firefox,and Opera
				gameidPosition.scrollIntoView({behavior: 'smooth'});
			});


		});

	</script>
</head>
<body>

<div id="homeHeader" class="w3-container header">
	<img class="DehumidifierLogo" src="includes/images/Dehumidifier-Logo.png" alt="Dehumidifier Logo Icon">

	<span id="logoutButtonHeader">
	<form action="logout.php" method="get"><button name='logout' type='submit'>Logout of <?php echo $steamprofile['personaname'] ?></button></form>
	</span>
	<span id="userimageAvatar">
		<?php echo ("<a href=" . $steamprofile['profileurl'] . " target='_blank' >");?>
		<img src=<?php echo $steamprofile['avatarmedium'] ?>></a>
	</span>	

</div>
  <div id="gameDisplayContainer" >
  	<!-- This div holds all of the games which are pulled from the users Steam Profile -->
  	<div id="ownedGamesDisplayContainer" class=""><!--w3-twothird-->
  			<ul id="unorderedGameList" class="games">
  				<?php
  					populateOwnedGames($db,$steamgameinfo);
  				?>  				
  			</ul>
  	</div>
  	<!-- This Div holds all the filters for the games. These are pulled from the DB and consist of tags, game details, ect, of all pulled back games -->
  	<div id="gameFilterDisplayContainer" class=""><!--w3-onethird-->
  			<div id='FilterContainer' style="display: block;">
  		  		<script type="text/javascript"></script>
  		  	
  		  		<?php
  		  			displayAllFilters($db);
  		  		?>
  			</div>
  			<div >
  		  		<button class="randombutton" id="curNegatedTagsButton" style="position: relative;left: 45%;">Random Game</button>  
  			</div>  	  		  	
  	</div>
  </div>
  <div id="completedPercentageContainer">
  	<div id="completedPercentageBar" class="w3-container">
  		<p id="completedPercentageNumber">0%</p>
  	</div>
  	
  </div>

	<?php if(isset($_SESSION['steamid'])) {
		//print_r($_SESSION);
		//echo $_SESSION['steam_personaname'];
	} else {
		//nl2br makes it so php can use line breaks like html
		echo nl2br ("\n Not Logged in");
	}
	?>





<form >

  <div id="footer" class="w3-container">
  	<a href="https://github.com/AnthonyArmatas" ><img src="includes/images/GitHub-Logo-120px.png" alt="GitHub Link Icon" style="width:45px;height:45px;"></a>
  	<!-- Keep The linkedin picture around 5-20px larger than the git hub. That is about their size difference-->
  	<a href="https://www.linkedin.com/in/anthonyarmatas/"><img src="includes/images/LinkedIn-Logo-120px.png" alt="Linkedin Link Icon" style="width:60px;height:60px;"></a>
  </div>
</body>
</html>