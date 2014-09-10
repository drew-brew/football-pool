<?php
include_once 'php/db_connect.php';
include_once 'php/functions.php';
sec_session_start ();
// $_SESSION['EXPIRES'] = time() + 60;
// check_activity($_SESSION['EXPIRES']);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>YMB | My Account</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/JavaScript">
$(window).load(function(){
	$("body").fadeIn(0);
});
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
</div>
</div>
<?php include 'pages/footer.php';?>
<?php else : ?>
<!-- Link to this page is hidden unless signed in -->
<?php endif; ?>
</body>
</html>