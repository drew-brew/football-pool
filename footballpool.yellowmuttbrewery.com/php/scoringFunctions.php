<?php
function getUsersPickForGame($mysqli, $week, $gameId, $userId) {
	$userPickForGameStmt = $mysqli->prepare ( "select away_score, home_score, pick from weekly_user_picks where weekid = ? && gameid = ? && userid = ?" );
	$userPickForGameStmt->bind_param ( 'iii', $week, $gameId, $userId );
	$userPickForGameStmt->execute ();
	$userGamePick = $userPickForGameStmt->get_result ();
}
function determineWinner($away_score, $away_team, $home_score, $home_team) {
	$winner = "notScored";
	if ($away_score > $home_score) {
		$winner = $away_team;
	}
	if ($home_score > $away_score) {
		$winner = $home_team;
	}
	return $winner;
}
function getActualAwayScoreForGame($mysqli, $week, $gameId) {
	$actualGameAwayScoreResultStmt = $mysqli->prepare ( "select away_score from game_list where weekid = ? && gameid = ?" );
	$actualGameAwayScoreResultStmt->bind_param ( 'ii', $week, $gameId );
	$actualGameAwayScoreResultStmt->execute ();
	$actualGameAwayScoreResult = $actualGameAwayScoreResultStmt->get_result ();
	$result = mysqli_fetch_array ( $actualGameAwayScoreResult );
	return $result [away_score];
}
function getActualHomeScoreForGame($mysqli, $week, $gameId) {
	$actualGameHomeScoreResultStmt = $mysqli->prepare ( "select home_score from game_list where weekid = ? && gameid = ?" );
	$actualGameHomeScoreResultStmt->bind_param ( 'ii', $week, $gameId );
	$actualGameHomeScoreResultStmt->execute ();
	$actualGameHomeScoreResult = $actualGameHomeScoreResultStmt->get_result ();
	$result = mysqli_fetch_array ( $actualGameHomeScoreResult );
	return $result [home_score];
}
function getAllUserIds($mysqli) {
	$getAllUserIdsStmt = $mysqli->prepare ( "select id from users" );
	$getAllUserIdsStmt->execute ();
	$allUserIds = $getAllUserIdsStmt->get_result ();
	return $allUserIds;
}
function isWeekScored($mysqli, $week) {
	$isWeekScoredStmt = $mysqli->prepare ( "select scored from weeks_scored where week = ?" );
	$isWeekScoredStmt->bind_param ( 'i', $week );
	$isWeekScoredStmt->execute ();
	$isWeekScoredStmt->bind_result ( $weekScoredFlag );
	$isWeekScoredStmt->fetch ();
	if ($weekScoredFlag == 'Y') {
		return true;
	} else {
		return false;
	}
}
function getAllGameIdsForWeek($mysqli, $week) {
	$getAllGameIdsStmt = $mysqli->prepare ( "select gameid from game_list where weekid = ?" );
	$getAllGameIdsStmt->bind_param ( 'i', $week );
	$getAllGameIdsStmt->execute ();
	$allGameIds = $getAllGameIdsStmt->get_result ();
	return $allGameIds;
}
function getAwayTeamForGame($mysqli, $gameId) {
	$getAwayTeamForGameStmt = $mysqli->prepare ( "select away_team from game_list where gameid = ?" );
	$getAwayTeamForGameStmt->bind_param ( 'i', $gameId );
	$getAwayTeamForGameStmt->execute ();
	$awayTeamResult = $getAwayTeamForGameStmt->get_result ();
	$result = mysqli_fetch_array ( $awayTeamResult );
	return $result [away_team];
}
function getHomeTeamForGame($mysqli, $gameId) {
	$getHomeTeamForGameStmt = $mysqli->prepare ( "select home_team from game_list where gameid = ?" );
	$getHomeTeamForGameStmt->bind_param ( 'i', $gameId );
	$getHomeTeamForGameStmt->execute ();
	$homeTeamResult = $getHomeTeamForGameStmt->get_result ();
	$result = mysqli_fetch_array ( $homeTeamResult );
	return $result [home_team];
}
function submitUserScoreForWeek($mysqli, $totalPoints, $userId, $week) {
	$submitUserWeekScoreStmt = $mysqli->prepare ( "UPDATE standings set week_$week = ? where userid = ? " );
	$submitUserWeekScoreStmt->bind_param ( 'di', $totalPoints, $userId );
	$submitUserWeekScoreStmt->execute ();
}
function updateTotalScore($mysqli, $userId, $week) {
	$updateUserTotalScoreStmt = $mysqli->prepare ( "update standings set total_points = (select (SUM(week_1) + SUM(week_2) + SUM(week_3) + SUM(week_4) + SUM(week_5) + SUM(week_6) + SUM(week_7) + SUM(week_8) + SUM(week_9) + SUM(week_10) + SUM(week_11) + SUM(week_12) + SUM(week_13) + SUM(week_14) + SUM(week_15) + SUM(week_16) + SUM(week_17))) where userid = ?" );
	$updateUserTotalScoreStmt->bind_param ( 'd', $userId );
	$updateUserTotalScoreStmt->execute ();
}
function weekScored($mysqli, $week) {
	$weekScoredStmt = $mysqli->prepare ( "UPDATE weeks_scored set scored = 'Y' where week = ? " );
	$weekScoredStmt->bind_param ( 'i', $week );
	$weekScoredStmt->execute ();
}
function getActualWinner($mysqli, $weekId, $gameId) {
	$winner = "notScored";
	$awayTeam = getAwayTeamForGame ( $mysqli, $gameId );
	$homeTeam = getHomeTeamForGame ( $mysqli, $gameId );
	$awayScore = getActualAwayScoreForGame ( $mysqli, $weekId, $gameId );
	$homeScore = getActualHomeScoreForGame ( $mysqli, $weekId, $gameId );
	$winner = determineWinner ( $awayScore, $awayTeam, $homeScore, $homeTeam );
	return $winner;
}
?>