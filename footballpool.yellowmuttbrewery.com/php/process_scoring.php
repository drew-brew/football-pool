<?php
include 'functions.php';
include 'scoringFunctions.php';
include 'db_connect.php';
// week based on what Admin inputs on admin page.
$week = $_POST ['weekToScore'];
// get all user ids into an array
$userIdsResultSet = getAllUserIds ( $mysqli );
// get all game ids for a particular week into an array
$gameIdsResultSet = getAllGameIdsForWeek ( $mysqli, $week );

$userIdArray = createArrayFromResultSet ( $userIdsResultSet );
$gameIdArray = createArrayFromResultSet ( $gameIdsResultSet );

$sizeOfUserIdArray = count ( $userIdArray );
$sizeOfGameIdArray = count ( $gameIdArray );
//if (isWeekScored ( $mysqli, $week ) == true) {
	//$mysqli->close ();
	//echo "<script>alert('Week already scored.');window.location.href = \"../adminpage.php\";</script>";
//} else {
	for($i = 0; $i < $sizeOfUserIdArray; $i ++) {
		$userScore = 0;
		if ($userIdArray [$i] [id] != 111 && hasUserDonePicks ( $mysqli, $week, $userIdArray [$i] [id] )) { // 111 = admin
			for($j = 0; $j < $sizeOfGameIdArray; $j ++) {
				
				$awayTeam = getAwayTeamForGame ( $mysqli, $gameIdArray [$j] [gameid] );
				$homeTeam = getHomeTeamForGame ( $mysqli, $gameIdArray [$j] [gameid] );
				
				// get actual scores for game id in loop - home/away
				$actualAwayScore = getActualAwayScoreForGame ( $mysqli, $week, $gameIdArray [$j] [gameid] );
				$actualHomeScore = getActualHomeScoreForGame ( $mysqli, $week, $gameIdArray [$j] [gameid] );
				
				// get user pick for game id
				$userPick = getUsersCurrentPick ( $mysqli, $gameIdArray [$j] [gameid], $userIdArray [$i] [id] );
				
				// determine actual winner
				//$actualWinner = getActualWinner($mysqli, $weekId, $gameIdArray [$j] [gameid]);
				$actualWinner = determineWinner ( $actualAwayScore, $awayTeam, $actualHomeScore, $homeTeam );
				
				// comparison of results
				if ($actualWinner === $userPick) {
					$userScore = $userScore + 1.00;
				}
			}
			
			// check user score for bonus points
			if ($userScore >= 5 && $userScore <= 7){
				$userScore = $userScore + 0.25;
			}else if ($userScore >= 8 && $userScore <= 10){
				$userScore = $userScore + 0.50;
			}else if ($userScore >= 11){
				$userScore = $userScore + 0.75;
			}
			submitUserScoreForWeek ( $mysqli, $userScore, $userIdArray [$i] [id], $week );
			updateTotalScore ( $mysqli, $userIdArray [$i] [id], $week );
		}
	}
	//weekScored ( $mysqli, $week );
	$mysqli->close ();
//}

echo "<script>window.location.href = \"../adminpage.php\";</script>";
?>