<?php
include 'php/functions.php';
include 'php/db_connect.php';

if ($_SERVER ["REQUEST_METHOD"] == "POST") {
	$name = $_POST ["name"];
	$string_exp = "/^[A-Za-z .'-]+$/";
	if (empty ( $name )) {
		$name_error = "You must enter your name!";
	}
	if (! preg_match ( $string_exp, $name )) {
		$name_error = "You entered an invalid name!";
	}
	
	$username = $_POST ["username"];
	if (empty ( $username )) {
		$username_error = "Username is required!";
	} else if (usernameExists ( $_POST ["username"], $mysqli ) == true) {
		$username_error = "A member already uses this username!";
	}
	
	$email = $_POST ["email"];
	if (empty ( $email )) {
		$email_error = "A valid email address is required!";
	} else if (validEmail ( $_POST ["email"] ) == false) {
		$email_error = "The email address you entered has invalid syntax!";
	} else if (emailExists ( $_POST ["email"], $mysqli ) == true) {
		$email_error = "A member already uses this email address!";
	}
	$password = $_POST ["p"];
	if (empty ( $password )) {
		$password_error = "You must enter a password!";
	}
	
	if ($_POST ['length'] == "short") {
		$password_error = "Your password must have at least 8 characters!";
	}
	
	$accessCode = $_POST ["accesscode"];
	if (empty ( $accessCode )) {
		$code_error = "Please enter access code!";
	}
	
	if ($accessCode != "SIGNUPOVER") {
		$code_error = "You entered an invalid code!";
	}
	
	$textReminders = $_POST ["textReminders"];
	if (isset ( $textReminders )) {
		$cellNumber = $_POST ["cellNumber"];
		if (empty ( $cellNumber )) {
			$phonenumber_error = "You must enter a phone number.";
		} else if (validPhoneNumber ( $cellNumber ) == false) {
			$phonenumber_error = "You have entered an invalid phone number.";
		}
		$cellCarrier = $_POST ["cellCarrier"];
	}
	
	if (($username_error == "") && ($email_error == "") && ($password_error == "") && ($code_error == "") && ($phonenumber_error == "")) {
		
		include "php/process_signup.php";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>YMB | Sign Up</title>
<meta charset="UTF-8">
<script type="text/javascript" src="js/sha512.js"></script>
<script type="text/javascript" src="js/forms.js"></script>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript" src="js/jquery-1.11.1.js"></script>
<script type="text/JavaScript">
$(window).load(function(){
	$("body").fadeIn(0);
	   $(document).ready(function () {
		   $("#registerForm [name='name']").focus();
		   $('#yesTextReminders').prop('checked', true);
		    if ( $('#yesTextReminders').is(':checked') ) {
			       $('#showCellCarrior').show(),
			       $('#showCellNumber').show();
			   } 
	   });
});
</script>
<script type="text/javascript">
$(document).ready(function () {
	$('#yesTextReminders').click(function(){
	    if ( $(this).is(':checked') ) {
	       $('#showCellCarrior').show(),
	       $('#showCellNumber').show();
	   } else {
	       $('#showCellCarrior').hide(),
	       $('#showCellNumber').hide();
	   }
	});
});
</script>
</head>
<body id="signupBody">
<?php include 'pages/background.php';?>
<?php include 'pages/header.php';?>
<div id="page">
	<div id="pagecontentsignup">
		<h1 id="centeredText">Sign Up</h1>
        <?php
								if (! empty ( $error_msg )) {
									echo $error_msg;
								}
								?>
	<div id="signupForm">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
				method="post" name="registerForm" id="registerForm">
			
				<?php echo "<p style=\"color:red\">$name_error"?>
				<?php echo $username_error?>
				<?php echo $password_error?>
				<?php echo $email_error?>
				<?php echo $code_error?>
				<?php echo $phonenumber_error?>

			<table>
					<tr>
						<td><label>Name:</label></td>
						<td><input type="text" name="name" /></td>
						<td></td>
					</tr>
					<tr>
						<td><label>Username:</label></td>
						<td><input type="text" name="username" /></td>
						<td></td>
					</tr>
					<tr>
						<td><label>Password:</label></td>
						<td><input type="password" name="p" id="password" /></td>
						<td></td>
					</tr>
					<tr>
						<td><label>Email:</label></td>
						<td><input type="text" name="email" /></td>
						<td></td>
					</tr>
					<tr>
						<td><label>Code:</label></td>
						<td><input type="text" name="accesscode" /></td>
						<td></td>
					</tr>
					<tr>
						<td><label>Text Reminders:</label></td>
						<td>Yes<input type="checkbox" id="yesTextReminders"
							name="textReminders" /></td>
						<td></td>
					</tr>
					<tr id=showCellCarrior style="display: none">
						<td><label>Cell Carrior:</label></td>
						<td><select name="cellCarrier">
								<option value="@txt.att.net">AT&T</option>
								<option value="@vtext.com">Verizon</option>
								<option value="@messaging.sprintpcs.com">Sprint</option>
								<option value="@tmomail.net">T-Mobile</option>
								<option value="@message.alltel.com">Alltel</option>
								<option value="@messaging.nextel.com">Nextel</option>
								<option value="@myboostmobile.com">Boost</option>
								<option value="@mymetropcs.com">Metro PCS</option>
						</select></td>
						<td></td>
					</tr>
					<tr id="showCellNumber" style="display: none">
						<td><label>Cell Number:</label></td>
						<td><input type="text" name="cellNumber" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input id="button" type="submit" value="Sign Up Now"
							onClick="formhashregister(this.form, this.form.password);" /></td>
						<td></td>
					</tr>
					<tr>
						<td><label>Already a member?</label></td>
						<td><a href="login.php">Login</a></td>
						<td></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
	<?php include 'pages/footer.php';?>
</body>
</html>