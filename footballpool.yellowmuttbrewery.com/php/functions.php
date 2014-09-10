<?php
// #########################################
// This function creates a new session ID #
// #########################################
function sec_session_start() {
	$session_name = 'sec_session_id';
	$secure = false;
	$httponly = true;
	
	ini_set ( 'session.use_only_cookies', 1 );
	$cookieParams = session_get_cookie_params ();
	session_set_cookie_params ( 0 );
	session_name ( $session_name );
	session_start ();
	session_regenerate_id ();
}
// ##################################################
// These functions are related to a user logging in #
// ##################################################
function login($username, $password, $mysqli) {
	if ($stmt = $mysqli->prepare ( "SELECT id, password, salt FROM users WHERE user_name = ? LIMIT 1" )) {
		$stmt->bind_param ( 's', $username );
		$stmt->execute ();
		$stmt->store_result ();
		$stmt->bind_result ( $user_id, $db_password, $salt );
		$stmt->fetch ();
		$password = hash ( 'sha512', $password . $salt );
		
		if ($stmt->num_rows == 1) {
			if (checkbrute ( $user_id, $mysqli ) == true) {
				echo "<script>alert('You have entered the incorrect password too many times. Your account is locked for 10 minutes.');window.location.href = \"../login.php\";</script>";
				return false;
			} else {
				if ($db_password == $password) {
					$user_browser = $_SERVER ['HTTP_USER_AGENT'];
					$user_id = preg_replace ( "/[^0-9]+/", "", $user_id );
					$_SESSION ['user_id'] = $user_id;
					$username = preg_replace ( "/[^a-zA-Z0-9_\-]+/", "", $username );
					$_SESSION ['username'] = $username;
					$_SESSION ['login_string'] = hash ( 'sha512', $password . $user_browser );
					return true;
				} else {
					$now = time ();
					$mysqli->query ( "INSERT INTO login_attempts(user_id, time) VALUES ('$user_id', '$now')" );
					return false;
				}
			}
		} else {
			return false;
		}
	}
}
function login_check($mysqli) {
	if (isset ( $_SESSION ['user_id'], $_SESSION ['username'], $_SESSION ['login_string'] )) {
		$user_id = $_SESSION ['user_id'];
		$login_string = $_SESSION ['login_string'];
		$username = $_SESSION ['username'];
		$user_browser = $_SERVER ['HTTP_USER_AGENT'];
		
		if ($stmt = $mysqli->prepare ( "SELECT password
                                      FROM users
                                      WHERE id = ? LIMIT 1" )) {
			$stmt->bind_param ( 'i', $user_id );
			$stmt->execute ();
			$stmt->store_result ();
			
			if ($stmt->num_rows == 1) {
				$stmt->bind_result ( $password );
				$stmt->fetch ();
				$login_check = hash ( 'sha512', $password . $user_browser );
				
				if ($login_check == $login_string) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}
function checkbrute($user_id, $mysqli) {
	$now = time ();
	$valid_attempts = $now - (10 * 60); // in seconds
	if ($stmt = $mysqli->prepare ( "SELECT time
			FROM login_attempts
			WHERE user_id = ?
			AND time > '$valid_attempts'" )) {
		$stmt->bind_param ( 'i', $user_id );
		$stmt->execute ();
		$stmt->store_result ();
		
		if ($stmt->num_rows > 5) {
			return true;
		} else {
			return false;
		}
	}
}
// #########################################################
// This function registers a user and inserts info into DB #
// #########################################################
function register($mysqli, $name, $username, $email, $phoneNumber, $cellCarrier, $password, $moneyFlag, $textRemindersFlag) {
	$password = $_POST ['p'];
	$random_salt = hash ( 'sha512', uniqid ( mt_rand ( 1, mt_getrandmax () ), true ) );
	$password = hash ( 'sha512', $password . $random_salt );
	
	if ($insert_stmt = $mysqli->prepare ( "INSERT INTO users (name, user_name, password, email, phone_number, cell_carrier, salt, owe_money, text_reminders) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)" )) {
		$insert_stmt->bind_param ( 'sssssssss', $name, $username, $password, $email, $phoneNumber, $cellCarrier, $random_salt, $moneyFlag, $textRemindersFlag );
		$insert_stmt->execute ();
		return true;
	}
}
// ############################
// These are helper functions #
// ############################
function textAndEmailUserPassword($mysqli, $userId, $plainTextPassword) {
	// email
	$email_to = getUsersEmail ( $mysqli, $userId );
	$email_subject = "Your Password";
	$email_message = "Password: $plainTextPassword";
	mail ( $email_to, $email_subject, $email_message );
	// text message
	$userPhoneNumber = getUsersPhoneNumber ( $mysqli, $userId );
	$userCellCarrier = getUserCellCarrier ( $mysqli, $userId );
	$text_to = $userPhoneNumber . $userCellCarrier;
	$text_subject = "Your Password";
	$text_message = "Password: $plainTextPassword";
	mail ( $text_to, $text_subject, $text_message );
}
function generateRandomString() {
	$length = 6;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for($i = 0; $i < $length; $i ++) {
		$randomString .= $characters [rand ( 0, strlen ( $characters ) - 1 )];
	}
	return $randomString;
}
function writeRandomString($mysqli, $userId, $randomString) {
	$writeRandomStringStmt = $mysqli->prepare ( "UPDATE retrievepassword_codes set code = ? where userid = ?" );
	$writeRandomStringStmt->bind_param ( 'si', $randomString, $userId );
	$writeRandomStringStmt->execute ();
}
function validatePasswordRetrievalForEmail($providedEmail, $actualUserIdEmail) {
	if ($providedEmail == $actualUserIdEmail) {
		return true;
	} else {
		return false;
	}
}
function validatePasswordRetrievalForText($providedPhoneNumber, $actualUserIdPhoneNumber, $providedCellCarrier, $actualUserIdCellCarrier) {
	if ($providedPhoneNumber == $actualUserIdPhoneNumber && $providedCellCarrier == $actualUserIdCellCarrier) {
		return true;
	} else {
		return false;
	}
}
function emailExists($email, $mysqli) {
	$stmt = $mysqli->prepare ( "SELECT email FROM users WHERE email = ? LIMIT 1" );
	$stmt->bind_param ( 's', $email );
	$stmt->execute ();
	return $stmt->fetch ();
}
function usernameExists($username, $mysqli) {
	$stmt = $mysqli->prepare ( "SELECT user_name FROM users WHERE user_name = ? LIMIT 1" );
	$stmt->bind_param ( 's', $username );
	$stmt->execute ();
	return $stmt->fetch ();
}
function validEmail($address) {
	$syntax = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/";
	if (preg_match ( $syntax, $address )) {
		return true;
	} else {
		return false;
	}
}
function validPhoneNumber($phoneNumber) {
	$phoneNumberStripped = stripPhoneNumber ( $phoneNumber );
	if (strlen ( $phoneNumberStripped ) != 10) {
		return false;
	} else {
		return true;
	}
}
function stripPhoneNumber($phoneNumber) {
	$phoneNumberStripped = preg_replace ( "/[^0-9,.]/", "", $phoneNumber );
	if (strlen ( $phoneNumberStripped ) == 11 && $phoneNumberStripped [0] == 1) {
		$phoneNumberStripped = substr ( $phoneNumberStripped, 1, 10 );
	} else if (strlen ( $phoneNumberStripped ) == 10) {
		// not doing anything here yet
	}
	return $phoneNumberStripped;
}
function hasUserDonePicks($mysqli, $week, $userId) {
	$checkUserPicksStmt = $mysqli->prepare ( "select * from user_picks where weekid = ? && userid = ?" );
	$checkUserPicksStmt->bind_param ( 'ii', $week, $userId );
	$checkUserPicksStmt->execute ();
	$myPicks = $checkUserPicksStmt->get_result ();
	
	if (mysqli_fetch_array ( $myPicks ) > 0) {
		// user had made picks
		return true;
	} else {
		// user has NOT made picks
		return false;
	}
}
function updateAccountInfo($mysqli, $userId, $newUserName, $newEmail) {
}
function createArrayFromResultSet($resultSet) {
	$resultArray = [ ];
	while ( $row = mysqli_fetch_array ( $resultSet ) ) {
		array_push ( $resultArray, $row );
	}
	return $resultArray;
}

// ##################################################
// These functions insert/update data to a MySQL DB #
// ##################################################
function submitPick($mysqli, $userId, $gameID, $week, $pick, $awayTeam, $homeTeam, $cutoffTime, $currentDate) {
	$insert_stmt2 = $mysqli->prepare ( "INSERT INTO user_picks (userid, gameid, weekid, pick, away_team, home_team, cutoff, date_picked ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)" );
	$insert_stmt2->bind_param ( 'iiisssss', $userId, $gameID, $week, $pick, $awayTeam, $homeTeam, $cutoffTime, $currentDate );
	$insert_stmt2->execute ();
}
function updatePick($mysqli, $pick, $currentDate, $userId, $gameID) {
	$update_stmt2 = $mysqli->prepare ( "UPDATE user_picks SET pick = ?, date_updated = ? WHERE userid = ? AND gameid = ?" );
	$update_stmt2->bind_param ( 'ssii', $pick, $currentDate, $userId, $gameID );
	$update_stmt2->execute ();
}
function submitActualWeekResult($mysqli, $awayScore, $homeScore, $gameId, $week) {
	$submitActualResultStmt = $mysqli->prepare ( "UPDATE game_list set away_score = ?, home_score = ? where gameid = ? && weekid = ?" );
	$submitActualResultStmt->bind_param ( 'iiii', $awayScore, $homeScore, $gameId, $week );
	$submitActualResultStmt->execute ();
}
function addUserToStandings($mysqli, $userId) {
	$addUserToStandingsStmt = $mysqli->prepare ( "INSERT INTO standings (`userid`, `username`) SELECT id, name FROM users WHERE id = ?" );
	$addUserToStandingsStmt->bind_param ( 'i', $userId );
	$addUserToStandingsStmt->execute ();
}
function addUserToRetrievePasswordCodes($mysqli, $userId) {
	$addUserToStandingsStmt = $mysqli->prepare ( "INSERT INTO retrievepassword_codes (`userid`) values (?)" );
	$addUserToStandingsStmt->bind_param ( 'i', $userId );
	$addUserToStandingsStmt->execute ();
}
function resetUserPassword($mysqli, $userId, $newPassword, $newSalt) {
	$resetUserPasswordStmt = $mysqli->prepare ( "UPDATE users set password = ?, salt = ? where id = ?" );
	$resetUserPasswordStmt->bind_param ( 'ssi', $newPassword, $newSalt, $userId );
	$resetUserPasswordStmt->execute ();
}
// ###################################################
// These functions "get" something from the database #
// ###################################################
function getWeekForActualResults($mysqli, $week) {
	$counter = 1;
	$counterTwo = 1;
	$missedGameCutoffCounter = 0;
	$currentDateAndTimeForCutoff = getCurrentDateAndTimeForCutoff ();
	$getWeekForPicks = $mysqli->prepare ( "select * from game_list where weekid = ?" );
	$getWeekForPicks->bind_param ( 'i', $week );
	$getWeekForPicks->execute ();
	$myPicks = $getWeekForPicks->get_result ();
	$numberOfGames = getNumberOfGamesInWeek ( $mysqli, $week );
	// else { sameScore = false; var table = $("#submitActualResults tbody"); sameScorePicks = []; table.find(\'tr\').each(function () { var $input = $(this).find(\'td input\'), awayScore = $input.eq(1).val(), homeScore = $input.eq(4).val(); if (awayScore == homeScore) { sameScore = true; } }); } if (sameScore) { alert("You cannot have the same score for any game. Please check scores."); return false; } else { return true; }
	echo "<br>";
	echo '<script>$(document).ready(function () { var specialKeys = []; specialKeys.push(8); $(function () { $(\'.numeric\').bind("keypress", function (e) { var keyCode = e.which ? e.which : e.keyCode; var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1); return ret; }); $(\'.numeric\').bind("paste", function (e) { return false; }); $(\'.numeric\').bind("drop", function (e) { return false; }); });});</script>';
	echo '<script>$(document).ready(function () {$("button[id$=\'submitActualResults\']").click(function () { var emptyInputs = $(this).parent().find(\'td input[type="text"]\').filter(function () { return $(this).val() === ""; }); if (emptyInputs.length) { alert("Please fill out all scores!"); return false; } }); });</script>';
	echo "<form  method=\"post\" action=\"php/process_actualresults.php\" id=\"submitActualResultsForm\">";
	echo "<h4>Week $week</h4>";
	echo "<input type=\"hidden\" name=\"week\" value=\"$week\"/><button type=\"submit\" id=\"submitActualResults\">Submit Actual Results</button>";
	echo "<table id=\"submitActualResults\">";
	echo "<thead>";
	echo "<tr>";
	echo "<td><h4>Away Score</h4></td>";
	echo "<td></td>";
	echo "<td width=\"200\"><h4>Away Team</h4></td>";
	echo "<td></td>";
	echo "<td><h4>VS</h4></td>";
	echo "<td></td>";
	echo "<td><h4>Home Team</h4></td>";
	echo "<td></td>";
	echo "<td><h4>Home Score</h4></td>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	while ( $row = mysqli_fetch_array ( $myPicks ) ) {
		echo "<tr>";
		echo "<td><input type=\"hidden\" name=\"gameID$counterTwo\" value=\"$row[gameid]\"/><input class=\"numeric\" id=\"firstinput\" type=\"text\" name=\"$counter\"size=\"2\" maxlength=\"2\" value='$row[away_score]'/></td>";
		$counter ++;
		echo "<td></td>";
		echo "<td><input  name=\"awayGame$counterTwo\" type=\"hidden\" value=\"" . $row [away_team] . "\" />" . $row [away_team] . "</td>";
		echo "<td></td>";
		echo "<td>@</td>";
		echo "<td></td>";
		echo "<td><input name=\"homeGame$counterTwo\" type=\"hidden\" value=\"" . $row [home_team] . "\" /> " . $row [home_team] . " </td>";
		echo "<td></td>";
		echo "<td><input class=\"numeric\" type=\"text\" name=\"$counter\"size=\"2\" maxlength=\"2\" value='$row[home_score]'/></td>";
		echo "</tr>";
		$counter ++;
		$counterTwo ++;
	}
	echo "</tbody>";
	echo "</table>";
	echo "</form>";
	return true;
}
function getWeekForPicks($mysqli, $week) {
	$counter = 1;
	$counterTwo = 1;
	$missedGameCutoffCounter = 0;
	$currentDateAndTimeForCutoff = getCurrentDateAndTimeForCutoff ();
	$getWeekForPicks = $mysqli->prepare ( "select * from game_list where weekid = ? AND cutoff > ?" );
	$getWeekForPicks->bind_param ( 'is', $week, $currentDateAndTimeForCutoff );
	$getWeekForPicks->execute ();
	$myPicks = $getWeekForPicks->get_result ();
	$numberOfGames = getNumberOfGamesInWeek ( $mysqli, $week );
	echo "<br>";
	echo "<form  method=\"post\" action=\"php/process_picks.php\" id=\"submitPicksForm\">";
	echo "<h2 id=\"centeredText\">Week $week Games</h2>";
	getByeWeekTeams ( $mysqli, $week );
	echo "<input type=\"hidden\" name=\"week\" value=\"$week\"/><button type=\"submit\" id=\"submitPicks\">Submit Picks</button>";
	echo "<table id=\"submitPicks\">";
	echo "<thead>";
	echo "<tr>";
	echo "<td><h4>Pick Away</h4></td>";
	echo "<td></td>";
	echo "<td width=\"200\"><h4>Away Team</h4></td>";
	echo "<td></td>";
	echo "<td><h4>VS</h4></td>";
	echo "<td></td>";
	echo "<td><h4>Home Team</h4></td>";
	echo "<td></td>";
	echo "<td><h4>Pick Home</h4></td>";
	echo "<td></td>";
	echo "<td><h4>Date</h4></td>";
	echo "<td></td>";
	echo "<td><h4>Time</h4></td>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	if ($myPicks->num_rows < $numberOfGames) {
		$gamesMissed = $numberOfGames - $myPicks->num_rows;
		echo "<p>You missed the cutoff for $gamesMissed games.</p>";
	}
	while ( $row = mysqli_fetch_array ( $myPicks ) ) {
		echo "<tr>";
		echo "<td><input type=\"hidden\" name=\"gameID$counterTwo\" value=\"$row[gameid]\"/><input type=\"radio\" class='awaygame$counter' name=\"gameid$row[gameid]pick\" value=\"" . $row [away_team] . "\" /></td>";
		echo "<td></td>";
		echo "<td><input  name=\"awayGame$counterTwo\" type=\"hidden\" value=\"" . $row [away_team] . "\" />" . $row [away_team] . "</td>";
		echo "<td></td>";
		echo "<td>@</td>";
		echo "<td></td>";
		echo "<td><input name=\"homeGame$counterTwo\" type=\"hidden\" value=\"" . $row [home_team] . "\" />" . $row [home_team] . "</td>";
		echo "<td></td>";
		echo "<td><input class='homegame$counter' type=\"radio\" name=\"gameid$row[gameid]pick\" value=\"" . $row [home_team] . "\"/></td>";
		echo "<td></td>";
		echo "<td>" . $row [date] . "</td>";
		echo "<td></td>";
		echo "<td>" . $row [time] . "</td>";
		echo "</tr>";
		$counter ++;
		$counterTwo ++;
	}
	echo "</tbody>";
	echo "</table>";
	echo "</form>";
	return true;
}
function getUserPicksToView($mysqli, $week, $userId) {
	$myPicksStmt = $mysqli->prepare ( "select * from user_picks where weekid = ? && userid = ? ORDER BY gameid ASC" );
	$myPicksStmt->bind_param ( 'ii', $week, $userId );
	$myPicksStmt->execute ();
	$myPicks = $myPicksStmt->get_result ();
	
	if (hasUserDonePicks ( $mysqli, $week, $userId ) == false) {
		echo "<br>";
		echo sprintf ( "You have not made picks for Week %s yet. Please do so.", $week );
		return false;
	} else {
		echo "<br>";
		echo sprintf ( "My Week %s Picks", $week );
		echo "<table id=\"viewPicks\">";
		echo "<thead>";
		echo "<tr>";
		echo "<td><h4>Pick</h4></td>";
		// echo "<td></td>";
		echo "<td width=\"200\"><h4>Away Team</h4></td>";
		// echo "<td></td>";
		echo "<td><h4>VS</h4></td>";
		// echo "<td></td>";
		echo "<td><h4>Home Team</h4></td>";
		// echo "<td></td>";
		echo "<td><h4>Pick &#8212; </h4></td>";
		// echo "<td></td>";
		echo "<td><h4>Result</h4></td>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		while ( $row = mysqli_fetch_array ( $myPicks ) ) {
			echo "<tr>";
			if ($row [pick] == $row [away_team]) {
				echo "<td><b>&#x2713;</b></td>";
			} else {
				echo "<td></td>";
			}
			// echo "<td></td>";
			echo "<td>$row[away_team]</td>";
			// echo "<td></td>";
			echo "<td>@</td>";
			// echo "<td></td>";
			echo "<td>$row[home_team]</td>";
			// echo "<td></td>";
			if ($row [pick] == $row [home_team]) {
				echo "<td><b>&#x2713;</b></td>";
			} else {
				echo "<td></td>";
			}
			// echo "<td></td";
			// get actual result
			$actualWinnerForGame = getActualWinner ( $mysqli, $week, $row [gameid] );
			if ($row [pick] === $actualWinnerForGame) {
				echo "<td><span style=\"color: green\"><b>WIN</b></span></td>";
			} else if ($actualWinnerForGame === "notScored") {
				echo "<td><b> - </b></td>";
			} else {
				echo "<td><span style=\"color: red\"><b>LOSS</b></span></td>";
			}
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
		return true;
	}
}
function getOtherUsersPicksToView($mysqli, $week, $userId) {
	$myPicksStmt = $mysqli->prepare ( "select * from user_picks where weekid = ? && userid = ? ORDER BY gameid ASC" );
	$myPicksStmt->bind_param ( 'ii', $week, $userId );
	$myPicksStmt->execute ();
	$myPicks = $myPicksStmt->get_result ();
	$usernameOfPicksBeingViewed = getUsername ( $mysqli, $userId );
	if (hasUserDonePicks ( $mysqli, $week, $userId ) == false) {
		echo "<br>";
		echo sprintf ( "You have not made picks for Week %s yet. Please do so.", $week );
		return false;
	} else {
		echo "<br>";
		echo sprintf ( "$usernameOfPicksBeingViewed's Week %s Picks", $week );
		echo "<table id=\"viewPicks\">";
		echo "<thead>";
		echo "<tr>";
		echo "<td><h4>Pick</h4></td>";
		// echo "<td></td>";
		echo "<td width=\"200\"><h4>Away Team</h4></td>";
		// echo "<td></td>";
		echo "<td><h4>VS</h4></td>";
		// echo "<td></td>";
		echo "<td><h4>Home Team</h4></td>";
		// echo "<td></td>";
		echo "<td><h4>Pick &#8212; </h4></td>";
		// echo "<td></td>";
		echo "<td><h4>Result</h4></td>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		while ( $row = mysqli_fetch_array ( $myPicks ) ) {
			echo "<tr>";
			if ($row [pick] == $row [away_team]) {
				echo "<td><b>&#x2713;</b></td>";
			} else {
				echo "<td></td>";
			}
			// echo "<td></td>";
			echo "<td>$row[away_team]</td>";
			// echo "<td></td>";
			echo "<td>@</td>";
			// echo "<td></td>";
			echo "<td>$row[home_team]</td>";
			// echo "<td></td>";
			if ($row [pick] == $row [home_team]) {
				echo "<td><b>&#x2713;</b></td>";
			} else {
				echo "<td></td>";
			}
			// echo "<td></td";
			// get actual result
			$actualWinnerForGame = getActualWinner ( $mysqli, $week, $row [gameid] );
			if ($row [pick] === $actualWinnerForGame) {
				echo "<td><span style=\"color: green\"><b>WIN</b></span></td>";
			} else if ($actualWinnerForGame === "notScored") {
				echo "<td><b> - </b></td>";
			} else {
				echo "<td><span style=\"color: red\"><b>LOSS</b></span></td>";
			}
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
		return true;
	}
}
function getUserPicksToUpdate($mysqli, $week, $userId) {
	$counter = 1;
	$counterTwo = 1;
	$currentDateAndTimeForCutoff = getCurrentDateAndTimeForCutoff ();
	$myPicksStmt = $mysqli->prepare ( "select * from user_picks where weekid = ? && userid = ? && cutoff > ? ORDER BY gameid ASC" );
	$myPicksStmt->bind_param ( 'iis', $week, $userId, $currentDateAndTimeForCutoff );
	$myPicksStmt->execute ();
	$myPicks = $myPicksStmt->get_result ();
	$numberOfGames = getNumberOfGamesInWeek ( $mysqli, $week );
	if (hasUserDonePicks ( $mysqli, $week, $userId ) == false) {
		echo "<br>";
		echo sprintf ( "You have not made picks for Week %s yet. Please do so.", $week );
		return false;
	} else {
		echo "<br>";
		echo "<form  method=\"post\" action=\"php/process_updatepicks.php\" id=\"updatePicksForm\">";
		echo "<h4>My Week $week Picks</h4>";
		echo "<input type=\"hidden\" name=\"week\" value=\"$week\"/><button type=\"submit\" value=\"updatePicks\">Update My Picks</button>";
		echo "<table>";
		echo "<thead>";
		echo "<tr>";
		echo "<td><h4>Pick Away</h4></td>";
		echo "<td></td>";
		echo "<td width=\"200\"><h4>Away Team</h4></td>";
		echo "<td></td>";
		echo "<td><h4>VS</h4></td>";
		echo "<td></td>";
		echo "<td><h4>Home Team</h4></td>";
		echo "<td></td>";
		echo "<td><h4>Pick Home</h4></td>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		if ($myPicks->num_rows < $numberOfGames) {
			$gamesMissed = $numberOfGames - $myPicks->num_rows;
			echo "<p>The update cutoff for $gamesMissed games has past.</p>";
		}
		while ( $row = mysqli_fetch_array ( $myPicks ) ) {
			echo "<tr>";
			echo "<input type=\"hidden\" name=\"gameID$counterTwo\" value=\"$row[gameid]\"/>";
			if ($row [pick] === $row [away_team]) {
				echo "<td>&#x2713;</td>";
			} else {
				echo "<td><input type=\"checkbox\" name=\"gameid$row[gameid]pick\" value=\"" . $row [away_team] . "\" /></td>";
			}
			$counter ++;
			echo "<td></td>";
			echo "<td><input  name=\"awayGame$counterTwo\" type=\"hidden\" value=\"" . $row [away_team] . "\" />" . $row [away_team] . "</td>";
			echo "<td></td>";
			echo "<td>@</td>";
			echo "<td></td>";
			echo "<td><input name=\"homeGame$counterTwo\" type=\"hidden\" value=\"" . $row [home_team] . "\" /> " . $row [home_team] . " </td>";
			echo "<td></td>";
			if ($row [pick] === $row [home_team]) {
				echo "<td>&#x2713;</td>";
			} else {
				echo "<td><input type=\"checkbox\" name=\"gameid$row[gameid]pick\" value=\"" . $row [home_team] . "\"/></td>";
			}
			echo "</tr>";
			$counter ++;
			$counterTwo ++;
		}
		echo "</tbody>";
		echo "</table>";
		echo "</form>";
		return true;
	}
}
function getStangings($mysqli) {
	$standingsStmt = $mysqli->prepare ( "select * from standings order by total_points DESC" );
	$standingsStmt->execute ();
	$standings = $standingsStmt->get_result ();
	$placeInStandingsCounter = 1;
	echo "<h1 id=\"centeredText\">Standings</h1>";
	echo "<table id=\"standingsTable\">";
	echo "<thead>";
	echo "<tr>";
	echo "<td><h4>Place</h4></td>";
	echo "<td></td>";
	echo "<td><h4>Username</h4></td>";
	echo "<td></td>";
	echo "<td><h4>Total Points</h4></td>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	while ( $row = mysqli_fetch_array ( $standings ) ) {
		$username = getUsername ( $mysqli, $row [userid] );
		if ($username != 'admin') {
			echo "<tr>";
			echo "<td>$placeInStandingsCounter.</td>";
			echo "<td></td>";
			echo "<td>$row[username]</td>";
			echo "<td></td>";
			echo "<td>$row[total_points]</td>";
			echo "</tr>";
			$placeInStandingsCounter ++;
		}
	}
	echo "</tbody>";
	echo "</table>";
	return true;
}
function getUsersCurrentPick($mysqli, $gameId, $userId) {
	$currentPickStmt = $mysqli->prepare ( "select pick from user_picks where gameid = ? AND userid = ?" );
	$currentPickStmt->bind_param ( 'ii', $gameId, $userId );
	$currentPickStmt->execute ();
	$currentPickStmt->bind_result ( $currentPick );
	$currentPickStmt->fetch ();
	return $currentPick;
}
function getUserInfoForTextReminders($mysqli) {
	$userNumbersForTextRemindersStmt = $mysqli->prepare ( "select name, phone_number, cell_carrier from users where text_reminders = 'Y'" );
	$userNumbersForTextRemindersStmt->execute ();
	$userInfo = $userNumbersForTextRemindersStmt->get_result ();
	return $userInfo;
}
function getDateOfFirstGameForWeek($mysqli, $week) {
	$dateOfFirstGameStmt = $mysqli->prepare ( "select date from game_list where gameid = ? LIMIT 1" );
	$dateOfFirstGameStmt->bind_param ( 'i', $week );
	$dateOfFirstGameStmt->execute ();
	$dateOfFirstGameStmt->bind_result ( $dateOfFirstGame );
	$dateOfFirstGameStmt->fetch ();
	return $dateOfFirstGame;
}
function getAllUsersForWhoIsPlaying($mysqli) {
	$allUsersStmt = $mysqli->prepare ( "select * from users" );
	$allUsersStmt->execute ();
	$allUsers = $allUsersStmt->get_result ();
	$counter = 1;
	echo "<table id=\"userTable\">";
	echo "<thead>";
	echo "<tr>";
	echo "<td></td>";
	echo "<td><h1>Name</h1></td>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	while ( $row = mysqli_fetch_array ( $allUsers ) ) {
		echo "<tr>";
		if ($row [user_name] != 'admin') {
			echo "<td>$counter.</td>";
			echo "<td>$row[user_name] - $row[name]</td>";
			$counter ++;
		}
		echo "</tr>";
	}
	echo "</tbdoy>";
	echo "</table>";
}
function getCutoffTimeForGame($mysqli, $gameID) {
	$cutoffTimeStmt = $mysqli->prepare ( "select cutoff from game_list where gameid = ?" );
	$cutoffTimeStmt->bind_param ( 'i', $gameID );
	$cutoffTimeStmt->execute ();
	$cutoffTimeStmt->bind_result ( $cutoffTime );
	$cutoffTimeStmt->fetch ();
	return $cutoffTime;
}
function getUsersEmail($mysqli, $userId) {
	$userEmailStmt = $mysqli->prepare ( "select email from users where id = ?" );
	$userEmailStmt->bind_param ( 'i', $userId );
	$userEmailStmt->execute ();
	$userEmailStmt->bind_result ( $userEmail );
	$userEmailStmt->fetch ();
	return $userEmail;
}
function getUsersPhoneNumber($mysqli, $userId) {
	$userEmailStmt = $mysqli->prepare ( "select phone_number from users where id = ?" );
	$userEmailStmt->bind_param ( 'i', $userId );
	$userEmailStmt->execute ();
	$userEmailStmt->bind_result ( $phoneNumber );
	$userEmailStmt->fetch ();
	return $phoneNumber;
}
function getUserCellCarrier($mysqli, $userId) {
	$userCellCarrierStmt = $mysqli->prepare ( "select cell_carrier from users where id = ?" );
	$userCellCarrierStmt->bind_param ( 'i', $userId );
	$userCellCarrierStmt->execute ();
	$userCellCarrierStmt->bind_result ( $cellCarrier );
	$userCellCarrierStmt->fetch ();
	return $cellCarrier;
}
function getUserID($mysqli, $username) {
	$getUserIDStmt = $mysqli->prepare ( "SELECT id FROM users WHERE user_name = ?" );
	$getUserIDStmt->bind_param ( 's', $username );
	$getUserIDStmt->execute ();
	$getUserIDStmt->bind_result ( $userId );
	$getUserIDStmt->fetch ();
	return $userId;
}
function getUsername($mysqli, $userId) {
	$getUsernameStmt = $mysqli->prepare ( "select user_name from users where id = ?" );
	$getUsernameStmt->bind_param ( 'i', $userId );
	$getUsernameStmt->execute ();
	$getUsernameStmt->bind_result ( $username );
	$getUsernameStmt->fetch ();
	return $username;
}
function getNumberOfGamesInWeek($mysqli, $week) {
	$getNumOfGamesStmt = $mysqli->prepare ( "select games from games_per_week where week = ?" );
	$getNumOfGamesStmt->bind_param ( 'i', $week );
	$getNumOfGamesStmt->execute ();
	$getNumOfGamesStmt->bind_result ( $numOfGames );
	$getNumOfGamesStmt->fetch ();
	return $numOfGames;
}
function getDifferential($awayScore, $homeScore) {
	if ($awayScore > $homeScore) {
		$result = $awayScore - $homeScore;
	} else {
		$result = $homeScore - $awayScore;
	}
	return $result;
}
function getCurrentDate() {
	$currentDate = date ( 'Y-m-d' );
	return $currentDate;
}
function getCurrentDateAndTimeForCutoff() {
	date_default_timezone_set ( 'America/New_York' );
	$currentDateAndTimeForCutoff = date ( 'm-d-H:i' );
	return $currentDateAndTimeForCutoff;
}
function getUserPaidFlag($mysqli, $userId) {
	$getUserPaidFlagStmt = $mysqli->prepare ( "select owe_money from users where id = ?" );
	$getUserPaidFlagStmt->bind_param ( 'i', $userId );
	$getUserPaidFlagStmt->execute ();
	$getUserPaidFlagStmt->bind_result ( $userPaidFlag );
	$getUserPaidFlagStmt->fetch ();
	return $userPaidFlag;
}
function getRandomString($mysqli, $userId) {
	$getRandomStringStmt = $mysqli->prepare ( "select code from retrievepassword_codes where userid = ?" );
	$getRandomStringStmt->bind_param ( 'i', $userId );
	$getRandomStringStmt->execute ();
	$getRandomStringStmt->bind_result ( $randomString );
	$getRandomStringStmt->fetch ();
	return $randomString;
}
function getUsersSalt($mysqli, $userId) {
	$getUsersSaltStmt = $mysqli->prepare ( "select salt from users where id = ?" );
	$getUsersSaltStmt->bind_param ( 'i', $userId );
	$getUsersSaltStmt->execute ();
	$getUsersSaltStmt->bind_result ( $usersSalt );
	$getUsersSaltStmt->fetch ();
	return $usersSalt;
}
function getUsersWhoHaveDonePicksForWeek($mysqli, $weekId) {
}
function getUserSelectOption($mysqli) {
	$allUsersStmt = $mysqli->prepare ( "select * from users" );
	$allUsersStmt->execute ();
	$allUsers = $allUsersStmt->get_result ();
	$currentUser = $username = htmlentities ( $_SESSION ['username'] );
	echo "<select name=\"usersSelectOption\">";
	while ( $row = mysqli_fetch_array ( $allUsers ) ) {
		if ($row [user_name] != 'admin' && $currentUser != $row [user_name]) {
			echo "<option value=\"$row[id]\">$row[user_name]</option>";
		}
	}
	echo "</select>";
}
function getByeWeekTeams($mysqli, $weekId) {
	$byeWeekTeamsStmt = $mysqli->prepare ( "select * from bye_week where weekid = ?" );
	$byeWeekTeamsStmt->bind_param ( 'i', $weekId );
	$byeWeekTeamsStmt->execute ();
	$byeWeekGames = $byeWeekTeamsStmt->get_result ();
	
	echo "Teams on bye week:<br>";
	echo "<ul>";
	while ( $row = mysqli_fetch_array ( $byeWeekGames ) ) {
		if ($row [team] != "None") {
			echo "<li>$row[team]</li>";
		} else {
			echo "<li>No teams on bye week.</li>";
		}
	}
	echo "</ul>";
	echo "<br>";
}
?>