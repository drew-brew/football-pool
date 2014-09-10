<?php 
$username = $_POST['username'];
$code = $_POST['code'];
$password = $_POST ['p'];
$userId = getUserID ( $mysqli, $username );
$actualUserIdCode = getRandomString($mysqli, $userId);

if ($code != $actualUserIdCode){
	echo "<script>alert('Username and/or code not valid. Please try again.');window.location.href = \"../r3s3tpassw0rd.html\";</script>";
}else{
	$newRandomSalt = hash ( 'sha512', uniqid ( mt_rand ( 1, mt_getrandmax () ), true ) );
	$newHashedPassword = hash ( 'sha512', $password . $newRandomSalt );
	resetUserPassword($mysqli, $userId, $newHashedPassword, $newRandomSalt);
	echo "<script>alert('Your password has been reset.');window.location.href = \"../login.php\";</script>";
}
$mysqli->close();
?>