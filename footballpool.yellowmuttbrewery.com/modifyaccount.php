<?php
include_once 'php/db_connect.php';
include_once 'php/functions.php';
sec_session_start ();
$username = htmlentities ( $_SESSION ['username'] );
$userID = getUserID ( $mysqli, $username );
$userEmail = getUsersEmail ( $mysqli, $userID );
$oweMoneyFlag = getUserPaidFlag ( $mysqli, $userID );
if ($oweMoneyFlag == 'Y') {
	$oweMoneyFlag = "Yes";
} else {
	$oweMoneyFlag = "No";
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Football Pool by YMB | Modify Account</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript" src="js/sha512.js"></script>
<script type="text/JavaScript" src="js/forms.js"></script>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/JavaScript">
$(window).load(function(){
	$("body").fadeIn(0);
});
function editUserData(){
	$("#accountDetails").html("<form method=\"post\"><table id=\"modifyAccount\"><tr><td>User Name:</td><td><input type=\"text\" id=\"newUserName\" value=\"<?php echo htmlentities($_SESSION['username']); ?>\"/></td></tr><tr><td>Email:</td><td><input type=\"text\" id=\"newEmail\" value=\"<?php echo $userEmail; ?>\" size=\"36\"/></td></tr></table><button type=\"submit\" id=\"updateUserAccount\"name=\"updateUserAccount\" onclick=\"notWorking()\">Update</button></form>");
}
function notWorking(){
	alert("Functionality is in progress...");
}
</script>
<meta charset="UTF-8">
</head>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php include 'pages/background.php';?>
<?php include 'pages/headerloggedin.php';?>
<div id="page">
<?php include 'pages/nav.php';?>
<div id="pagecontent">
<?php include 'pages/accountlinks.php';?>
	<div id="accountDetails">
				<table id="modifyAccount">
					<tr>
						<td>User Name:</td>
						<td><?php echo htmlentities($_SESSION['username']); ?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?php echo $userEmail; ?></td>
					</tr>
					<tr>
						<td>Owe $25:</td>
						<td><?php echo $oweMoneyFlag?></td>
					</tr>
				</table>
				<p>Payments can be made through the app Venmo or in person. The app is 100% free and easy!</p>
				<button type="submit" id="updateUserAccount"
					name="updateUserAccount" onclick="editUserData()">Edit</button>
			</div>
		</div>
	</div>
<?php include 'pages/footer.php';?>
<?php else : ?>
<!-- Link to this page is hidden unless signed in -->
<?php endif; ?>
</body>
</html>