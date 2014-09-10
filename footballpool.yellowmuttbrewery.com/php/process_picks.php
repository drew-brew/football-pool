<?php
include 'functions.php';
sec_session_start ();
include 'db_connect.php';
$currentDate = getCurrentDate ();
$username = htmlentities ( $_SESSION ['username'] );
$userID = getUserID ( $mysqli, $username );
$week = $_POST ['week'];
$numberOfGames = getNumberOfGamesInWeek ( $mysqli, $week );
$awayScoreCounter = 1;
$homeScoreCounter = 2;
$nameCounter = 1;
$gameIdCounter = 1;

if (hasUserDonePicks ( $mysqli, $week, $userID )) {
	$mysqli->close ();
	echo "<script>alert('You have already done your picks for this week, please go to your account and edit picks there.');window.location.href = \"../account.php\";</script>";
} else {
	while ( $numberOfGames > 0 ) {
		$awayTeam = $_POST ['awayGame' . $nameCounter];
		$homeTeam = $_POST ['homeGame' . $nameCounter];
		$gameID = $_POST ['gameID' . $gameIdCounter];
		$pick = $_POST ['gameid' . $gameID . 'pick'];
		$cutoffTime = getCutoffTimeForGame ( $mysqli, $gameID );
		submitPick ( $mysqli, $userID, $gameID, $week, $pick, $awayTeam, $homeTeam, $cutoffTime, $currentDate );
		$awayScoreCounter = $awayScoreCounter + 2;
		$homeScoreCounter = $homeScoreCounter + 2;
		$numberOfGames --;
		$nameCounter ++;
		$gameIdCounter ++;
	}
	$mysqli->close ();
}

?>
<!DOCTYPE HTML>
<html>
<head>
<title>Football Pool by YMB | Processing</title>
<meta charset="UTF-8">
</head>
<h1>Processing...</h1>
</body>
</html>
<?php
echo "<script>alert('Thank you for submitting your picks.');window.location.href = \"../home.php\";</script>";
?>