<?php
include 'functions.php';
include 'db_connect.php';
sec_session_start ();
$randomString = generateRandomString ();
$username = $_POST ['username'];
if (isset ( $_POST ['email'] )) {
	$providedEmail = $_POST ['email'];
	$userId = getUserID ( $mysqli, $username );
	$actualUserIdEmail = getUsersEmail ( $mysqli, $userId );
	$link = "http://footballpool.yellowmuttbrewery.com/resetpassword.php";
	if (validatePasswordRetrievalForEmail ( $providedEmail, $actualUserIdEmail )) {
		writeRandomString ( $mysqli, $userId, $randomString );
		// send email
		
		$email_to = $providedEmail;
		$email_subject = "Reset Password Code";
		$email_message = "Use code: $randomString @ $link";
		mail ($email_to, $email_subject, $email_message);
		echo "<script>alert('A code has been sent to you. Follow Steps.');window.location.href = \"../home.php\";</script>";
	} else {
		echo "<script>alert('Information provided does not match a valid account.');window.location.href = \"../resetpassword.html\";</script>";
	}
} else if (isset ( $_POST ['phonenumber'] )) {
	$link = "http://footballpool.yellowmuttbrewery.com/resetpassword.php";
	$providedPhoneNumber = $_POST ['phonenumber'];
	$providedCellCarrier = $_POST ['cellcarrier'];
	$userId = getUserID ( $mysqli, $username );
	$actualUserIdCellCarrier = getUserCellCarrier ( $mysqli, $userId );
	$actualUserIdPhoneNumber = getUsersPhoneNumber ( $mysqli, $userId );
	if (validatePasswordRetrievalForText ( $providedPhoneNumber, $actualUserIdPhoneNumber, $providedCellCarrier, $actualUserIdCellCarrier )) {
		writeRandomString ( $mysqli, $userId, $randomString );
		// send text
		$text_to = $providedPhoneNumber . $providedCellCarrier;
		$text_subject = "";
		$text_message = "Use code: $randomString @ $link";
		mail ( $text_to, $text_subject, $text_message );
		echo "<script>alert('A code has been sent to you. Follow Steps.');window.location.href = \"../home.php\";</script>";
	} else {
		echo "<script>alert('Information provided does not match a valid account.');window.location.href = \"../resetpassword.html\";</script>";
	}
}
$mysqli->close ();
?>