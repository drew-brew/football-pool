<?php 
include 'php/functions.php';
include 'php/db_connect.php';

if ($_SERVER ["REQUEST_METHOD"] == "POST") {

	$username = $_POST ["username"];
	if (empty ( $username )) {
		$username_error = "Username is required!";
	} 
	
	$password = $_POST ["p"];
	if (empty ( $password )) {
		$password_error = "You must enter a password!";
	}

	$userId = getUserID ( $mysqli, $username );
	$passedUserResetCode = $_POST["code"];
	$actualUSerResetCode = getRandomString($mysqli, $userId);
	
	if (empty ($passedUserResetCode)){
		$code_error = "Please enter your reset code that was sent to you.";
	}
	
	if ($passedUserResetCode != $actualUSerResetCode){
		$code_error = "Your username and/or code is not valid";
	}
	
	if ($_POST ['length'] == "short") {
		$password_error = "Your password must have at least 8 characters!";
	}

	if (($username_error == "") && ($password_error == "") && ($code_error == "")) {

		include "php/process_resetpassword.php";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>YMB | Reset Password</title>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/JavaScript" src="js/sha512.js"></script>
<script type="text/JavaScript" src="js/forms.js"></script>
<meta charset="UTF-8">
</head>
<body>
<?php
if (! empty ( $error_msg )) {
	echo $error_msg;
}
?>

	<h1>Reset Password</h1>
<?php echo "<p style=\"color:red\">$code_error<br>"?>
<?php echo "$username_error<br>"?>
<?php echo "$password_error<br>"?>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				
		<b><i>1. Enter username you used to sign up</i></b><br> 
		<label>Username:</label><input type="text" id="username" name="username" /><br> <br>
		<b><i>2. Enter your code from email/text</i></b><br> 
		<label>Code:</label><input type="text" id="code" name="code" /><br> <br>
		<b><i>3. Enter your new password</i></b><br> 
		<label>New Password:</label><input type="password" id="password" name="p" /><br> <br>
		<input type="submit" id="resetPassword" value="Reset Password" onClick="formhashregister(this.form, this.form.password);"/>
	</form>
</body>
</html>