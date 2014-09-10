<?php
// if (isset ( $_POST ['username'], $_POST ['p'], $_POST ['email'] )) {
$textReminders = $_POST ["textReminders"];
$name = $_POST ['name'];
$username = $_POST ['username'];
$email = $_POST ['email'];
$password = $_POST ['p'];
$moneyFlag = 'Y';
$currentDate = getCurrentDate ();
$textReminders = $_POST ["textReminders"];
if (isset ( $textReminders )) {
	$cellCarrier = $_POST ["cellCarrier"];
	$textRemindersFlag = 'Y';
	$cellNumberFromUserInput = $_POST ["cellNumber"];
	$cellNumberStripped = stripPhoneNumber ( $cellNumberFromUserInput );
} else {
	$textRemindersFlag = 'N';
	$cellCarrier = "";
	$cellNumberStripped = "";
}


if (register ( $mysqli, $name, $username, $email, $cellNumberStripped, $cellCarrier, $password, $moneyFlag, $textRemindersFlag ) == true) {
	$userId = getUserID ( $mysqli, $username );
	addUserToStandings ( $mysqli, $userId );
	addUserToRetrievePasswordCodes ( $mysqli, $userId );
	$email_to = "andrew@yellowmuttbrewery.com";
	$email_subject = "New User";
	$email_message = "Name: $name\nUsername: $username\nEmail: $email\nDate: $currentDate";
	mail ( $email_to, $email_subject, $email_message );
	if (isset ( $textReminders )) {
		$text_to = $cellNumberStripped . $cellCarrier;
		$text_subject = "Welcome!";
		$text_message = "Welcome $name, to the 2014 NFL Football Pool brought to you by YMB";
		mail ( $text_to, $text_subject, $text_message );
	}
	$mysqli->close ();
	echo "<script>alert('Registration successful! Enjoy!');window.location.href = \"../login.php\";</script>";
} else {
	$mysqli->close ();
	echo "<script>alert('Registration failed. Please contact Admin.');window.location.href = \"../contact.php\";</script>";
}
?>