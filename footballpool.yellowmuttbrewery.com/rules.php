<?php
include_once 'php/db_connect.php';
include_once 'php/functions.php';
sec_session_start ();
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="js/resize_functions.js"></script>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
$(window).load(function(){
	$("body").fadeIn(0);
});
</script>
<meta charset="UTF-8">
<title>YMB | Rules</title>
</head>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php include 'pages/background.php';?>
<?php include 'pages/headerloggedin.php';?>
<div id="page">
<?php include 'pages/nav.php';?>
	<div id="pagecontent">
			<h1>Rules</h1>
			<ul>
				<li>You have until kickoff of each game to make and update your
					picks.</li>
				<li>Technically after 4 quarters + 1 OT quarter, ties are possible.
					However, ties will not be played. If a game should end in a tie,
					that game will not be scored.</li>
			</ul>
			<h1>Scoring</h1>
			<ul>
				<li>You will recieve 1 point per win.</li>
				<h3>Bonus Points</h3>
				<li>To avoid a tie at the end of 17 weeks you will recieve extra
					points based on how many games you win.</li>
				<li>You will recieve:</li>
				<li>4 or less wins, better luck next week sucker.</li>
				<li>Between 5-7 wins on a given week = 0.25 bonus points</li>
				<li>Between 8-10 wins on a given week = 0.50 bonus points</li>
				<li>11 or more wins on a given week = 0.75 bonus points</li>
			</ul>
		</div>
	</div>
<?php include 'pages/footer.php';?>
<?php else : ?>
<?php include 'pages/background.php';?>
<?php include 'pages/header.php';?>
<div id="page">
<?php include 'pages/nav.php';?>
	<div id="pagecontent">
			<h1>Rules</h1>
			<ul>
				<li>You have until kickoff of each game to make and update your
					picks.</li>
				<li>Technically after 4 quarters + 1 OT quarter, ties are possible.
					However, ties will not be played. If a game should end in a tie,
					that game will not be scored.</li>
			</ul>
			<h1>Scoring</h1>
			<ul>
				<li>You will recieve 1 point per win.</li>
				<h3>Bonus Points</h3>
				<li>To avoid a tie at the end of 17 weeks you will recieve extra
					points based on how many games you win.</li>
				<li>4 or less wins, better luck next week sucker.</li>
				<li>Between 5-7 wins on a given week = 0.25 bonus points</li>
				<li>Between 8-10 wins on a given week = 0.50 bonus points</li>
				<li>11 or more wins on a given week = 0.75 bonus points</li>
			</ul>
		</div>
	</div>
<?php include 'pages/footer.php';?>
<?php endif; ?>					
</body>
</html>