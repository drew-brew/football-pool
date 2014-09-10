<?php
include_once 'php/db_connect.php';
include_once 'php/functions.php';
sec_session_start ();
?>
<!DOCTYPE html>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/resize_functions.js"></script>
<script type="text/JavaScript">
$(window).load(function(){
	$("body").fadeIn(0);
});
</script>
<meta charset="UTF-8">
<title>YMB | Standings</title>
</head>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php include 'pages/background.php';?>
<?php include 'pages/headerloggedin.php';?>
<div id="page">
<?php include 'pages/nav.php';?>
	<div id="pagecontent">
		<?php getStangings($mysqli);?>
	</div>
</div>
<?php include 'pages/footer.php';?>
<?php else :  printf("<script>location.href='login.php'</script>");?>
<?php endif; ?>
</body>
</html>