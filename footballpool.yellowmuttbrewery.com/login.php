<?php
include_once 'php/db_connect.php';
include_once 'php/functions.php';
sec_session_start ();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>YMB | Login</title>
<meta charset="UTF-8">
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript" src="js/sha512.js"></script>
<script type="text/JavaScript" src="js/forms.js"></script>
<script type="text/JavaScript" src="js/jquery-1.11.1.js"></script>
<script type="text/JavaScript">
$(window).load(function(){
	$("body").fadeIn(0);
	   $(document).ready(function () {
		   $("#loginForm [name='username']").focus();
	   });
});
function notWorking(){
	alert("Functionality is in progress...");
	return false;
}
</script>
</head>
<body id="loginBody">
<?php if (login_check($mysqli) == true) : ?>
		<script>alert('You are already logged in!');window.location.href = "home.php";</script>
<?php else : ?>
<?php include 'pages/background.php';?>
<?php include 'pages/header.php';?>
<div id="page">
	<div id="pagecontentlogin">

		<div id="loginForm">
			<h1 id="centeredText">Login</h1>
			<form action="php/process_login.php" method="post" name="loginForm">
				<table>
					<tr>
						<td><label>Username:</label></td>
						<td><input type="text" id="username" name="username" /></td>
					</tr>
					<tr>
						<td><label>Password:</label></td>
						<td><input type="password" id="password" name="p" /></td>
					</tr>
					<tr>
						<td></td>
						<td>
						<input id="login" value="Login" type="submit" onclick="formhashlogin(this.form, this.form.password);" /> 
						<!--<input type="button" id="forgot_password" value="Forgot Password?" title="Click to retrieve password." onClick="notWorking()" /></td>-->
						<input type="button" id="forgot_password" value="Forgot Password?" title="Click to retrieve password." onClick="document.location.href='resetpassword.html'" /></td>
					</tr>
				</table>

			</form>
		</div>
	</div>
</div>
<?php include 'pages/footer.php';?>
<?php endif; ?>
</body>
</html>