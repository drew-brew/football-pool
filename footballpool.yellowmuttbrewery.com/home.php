<?php
include_once 'php/db_connect.php';
include_once 'php/functions.php';
sec_session_start ();
?>
<!DOCTYPE html>
<html>
<head>
<title>YMB | Home</title>
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
			<span style="color: white;"><h1>Welcome</h1></span>
			<p>Welcome back to the 2014 NFL Football Pool, brought to you by
				Yellow Mutt Brewery. Those of you that did not play last year, I ran
				this pool through Google Docs which required a lot of time on my end
				posting everyone's picks, posting the actual game results of a given
				week, scoring each players picks, and updating the standings page.
				All that is in the past! I decided to make a website to do
				everything for not only myself but for the players.</p>
			<p>The biggest issue last year was players forgetting to make
				their picks. The idea of hosting the pool online, open to anyone at
				anytime, was made to make it easier for everyone to get their picks
				in on time. Also, when you sign up you can choose to receive text
				message reminders for each week to make your picks.</p>
			<p>Another feature this online football pool brings to the table
				is the ability to see the schedule for every week. This allows you
				to do everything on one site rather then having to go out to NFL.com
				or somewhere else and look at the schedule and then come back to
				make your picks.</p>
			<p>
				Create an account, explore the site, and enjoy! Let me know if you
				have any comments and/or questions using the <span><a
					href="contact.php">Contact</a></span> page
			</p>
			<span style="color: white;"><h1>Payout</h1></span>
			<p>1st Place: $250 and 12 pack of YMB Brew</p>
			<p>2nd Place: $125 and 12 pack of YMB Brew</p>
			<p>3rd Place: $75 and 12 pack of YMB Brew</p>
		</div>
	</div>
	<?php include 'pages/footer.php';?>
	<?php else : ?>
	<?php include 'pages/background.php';?>
	<?php include 'pages/header.php';?>
	<div id="page">
		<?php include 'pages/nav.php';?>
		<div id="pagecontent">
			<span style="color: white;"><h1>Welcome</h1></span>
			<p>Welcome back to the 2014 NFL Football Pool, brought to you by
				Yellow Mutt Brewery. Those of you that did not play last year, I ran
				this pool through Google Docs which required a lot of time on my end
				posting everyone's picks, posting the actual game results of a given
				week, scoring each players picks, and updating the standings page.
				All that is in the past! I decided to make a website to do
				everything for not only myself but for the players.</p>
			<p>The biggest issue last year was players forgetting to make
				their picks. The idea of hosting the pool online, open to anyone at
				anytime, was made to make it easier for everyone to get their picks
				in on time. Also, when you sign up you can choose to receive text
				message reminders for each week to make your picks.</p>
			<p>Another feature this online football pool brings to the table
				is the ability to see the schedule for every week. This allows you
				to do everything on one site rather then having to go out to NFL.com
				or somewhere else and look at the schedule and then come back to
				make your picks. Another nice feature about having all 17 weeks out
				there to view is you can make your picks for all 17 weeks initially
				and update them in your account page at any point in time, before
				the cutoff period of course which is kickoff for each game.</p>
			<p>
				Create an account, explore the site, and enjoy! Let me know if you
				have any comments and/or questions using the <span><a
					href="contact.php">Contact</a></span> page
			</p>
			<span style="color: white;"><h1>Payout</h1></span>
			<p>1st Place: $250 and 12 pack of YMB Brew</p>
			<p>2nd Place: $125 and 12 pack of YMB Brew</p>
			<p>3rd Place: $75 and 12 pack of YMB Brew</p>
		</div>
	</div>
	<?php include 'pages/footer.php';?>
	<?php endif; ?>
</body>
</html>