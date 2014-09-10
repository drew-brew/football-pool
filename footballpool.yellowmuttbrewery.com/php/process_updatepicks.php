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

while ( $numberOfGames > 0 ) {
	$awayTeam = $_POST ['awayGame' . $nameCounter];
	$homeTeam = $_POST ['homeGame' . $nameCounter];
	$gameID = $_POST ['gameID' . $gameIdCounter];
	$currentUserPick = getUsersCurrentPick ( $mysqli, $gameID, $userID );
	if (isset($_POST ['gameid' . $gameID . 'pick'])){
		$updatedPick = $_POST ['gameid' . $gameID . 'pick'];
	}else{
		$updatedPick = $currentUserPick;
	}
	updatePick ( $mysqli, $updatedPick, $currentDate, $userID, $gameID );
	$awayScoreCounter = $awayScoreCounter + 2;
	$homeScoreCounter = $homeScoreCounter + 2;
	$numberOfGames --;
	$nameCounter ++;
	$gameIdCounter ++;
}
$mysqli->close ();
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
echo "<script>alert('Thank you for updating your picks.');window.location.href = \"../account.php\";</script>";
?>