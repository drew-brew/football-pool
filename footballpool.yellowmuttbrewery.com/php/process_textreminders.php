<?php
include 'functions.php';
include 'db_connect.php';

$week = $_POST['weekForReminder'];
$dateOfFirstGameForWeek = getDateOfFirstGameForWeek($mysqli, $week);
$userInfoResultSet = getUserInfoForTextReminders($mysqli);
$userInfoArray = createArrayFromResultSet($userInfoResultSet);
$sizeOfUserInfoArray = count($userInfoArray);

for($i = 0; $i < $sizeOfUserInfoArray; $i++){
	$userName = $userInfoArray[$i][0];
	$cellNumber = $userInfoArray[$i][1];
	$cellCarrier = $userInfoArray[$i][2];
	
	$text_to = $cellNumber . $cellCarrier;
	$text_subject = "**REMINDER**";
	$text_message = "$userName, this is a reminder to make football pool picks for week $week - first game is: $dateOfFirstGameForWeek";
	mail ( $text_to, $text_subject, $text_message );
	$cellCarrierCounter++;
}
$mysqli->close ();
echo "<script>window.location.href = \"../adminpage.php\";</script>";
?>