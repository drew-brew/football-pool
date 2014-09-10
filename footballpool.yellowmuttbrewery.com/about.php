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
<script type="text/JavaScript">
$(window).load(function(){
	$("body").fadeIn(0);
});
</script>
<meta charset="UTF-8">
<title>YMB | About</title>
</head>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php include 'pages/background.php';?>
<?php include 'pages/headerloggedin.php';?>
<div id="page">
<?php include 'pages/nav.php';?>
	<div id="pagecontent">
			<h1>About</h1>
			<p>Yellow Mutt Brewery is the brand name of a future Craft Brewery.
				It started as a hobby and took off, featuring all styles of beer.</p>
			<p>
				<span style="color: white"><b>footballpool.yellowmuttbrewery.com</b></span>
				is a fully funtional NFL Football pool allowing the players to take
				full control of their weekly picks while automatting the scoring
				functionality.
			</p>
			<p>I wanted to create something to show future employers of my self
				development thus the website was born. I use JQuery for various
				client side validation and HTML replacement. PHP is used server side
				to interact with my MySQL Database where all the user information,
				user picks, and scoring data lives.</p>
		</div>
	</div>
<?php include 'pages/footer.php';?>
<?php else : ?>
<?php include 'pages/background.php';?>
<?php include 'pages/header.php';?>
<div id="page">
<?php include 'pages/nav.php';?>
	<div id="pagecontent">
			<h1>About</h1>
			<p>Yellow Mutt Brewery is the brand name of a future Craft Brewery.
				It started as a hobby and took off, featuring all styles of beer.</p>
			<p>
				<span style="color: white"><b>footballpool.yellowmuttbrewery.com</b></span>
				is a fully funtional NFL Football pool allowing the players to take
				full control of their weekly picks while automatting the scoring
				functionality.
			</p>
			<p>I wanted to create something to show future employers of my self
				development thus the website was born. I use JQuery for various
				client side validation and HTML replacement. PHP is used server side
				to interact with my MySQL Database where all the user information,
				user picks, and scoring data lives.</p>
		</div>
	</div>
<?php include 'pages/footer.php';?>
<?php endif; ?>
</body>
</html>