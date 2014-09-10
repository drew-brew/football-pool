<?php
include 'functions.php';
sec_session_start ();
include 'db_connect.php';
$week = $_POST ['week'];
$numberOfGames = getNumberOfGamesInWeek ( $mysqli, $week );
$awayScoreCounter = 1;
$homeScoreCounter = 2;
$nameCounter = 1;
$gameIdCounter = 1;

while ( $numberOfGames > 0 ) {
	$gameID = $_POST ['gameID' . $gameIdCounter];
	$awayScore = $_POST [$awayScoreCounter];
	$homeScore = $_POST [$homeScoreCounter];
	submitActualWeekResult($mysqli, $awayScore, $homeScore, $gameID, $week);
	$awayScoreCounter = $awayScoreCounter + 2;
	$homeScoreCounter = $homeScoreCounter + 2;
	$numberOfGames --;
	$nameCounter ++;
	$gameIdCounter ++;
}
$mysqli->close ();
echo "<script>window.location.href = \"../adminpage.php\";</script>";
?>