<?php
//include_once 'php/db_connect.php';
include_once 'php/functions.php';
//sec_session_start ();
?>
<!DOCTYPE html>
<html>
<head>
<title>YMB | Predictions</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<meta charset="UTF-8">
</head>
<body>
	<?php if (login_check($mysqli) == true) : ?>
	<?php include 'pages/background.php';?>
	<?php include 'pages/headerloggedin.php';?>
	<div id="page">
		<?php include 'pages/nav.php';?>
		<div id="pagecontent">
			<span style="color: white;"><h1>Weekly Prediction Sites</h1></span><br>
			<p>These are a few of my frequented sites on weekly game predictions.</p>
			<ul>
				<li><a target="_blank" href="http://bleacherreport.com/articles/2190783-nfl-week-2-picks-early-predictions-for-every-game">Bleacher Report: Week 2 Early Predictions</a></li>
				<li><a target="_blank" href="http://bleacherreport.com/articles/2190461-nfl-week-2-predictions-projections-for-the-early-lines-spreads-and-odds">Bleacher Report: Week 2 Early Odds, Picks, and Predictions</a></li>
				<li><a target="_blank" href="http://www.foxsports.com/nfl/predictions?season=2014&seasonType=1&week=2&view=0">Fox Sports: Week 2 Picks</a></li>
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
			<span style="color: white;"><h1>Weekly Prediction Sites</h1></span><br>
			<p>These are a few of my frequented sites on weekly game predictions.</p>
			<ul>
				<li><a target="_blank" href="http://bleacherreport.com/articles/2190783-nfl-week-2-picks-early-predictions-for-every-game">Bleacher Report: Week 2 Early Predictions</a></li>
				<li><a target="_blank" href="http://bleacherreport.com/articles/2190461-nfl-week-2-predictions-projections-for-the-early-lines-spreads-and-odds">Bleacher Report: Week 2 Early Odds, Picks, and Predictions</a></li>
				<li><a target="_blank" href="http://www.foxsports.com/nfl/predictions?season=2014&seasonType=1&week=2&view=0">Fox Sports: Week 2 Picks</a></li>
			</ul>
		</div>
	</div>
	<?php include 'pages/footer.php';?>
	<?php endif; ?>
</body>
</html>