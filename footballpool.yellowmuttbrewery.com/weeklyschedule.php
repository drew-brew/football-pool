<?php
include_once 'php/db_connect.php';
include_once 'php/functions.php';
sec_session_start ();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>YMB | Weekly Schedule</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.11.1.js"></script>
<script type="text/JavaScript">
$(window).load(function(){
	$("body").fadeIn(0);
});
</script>
<script type="text/JavaScript">
$(document).ready(function(){
	$("button[id$=\'submitPicks\']").click(function () {
	    var table = $("#submitPicks tbody");
	    var counter = 0;
	    var numberRadiosChecked = $(':radio:checked').length;
	    table.find('tr').each(function () {
	        counter++;
	    });
	    if (numberRadiosChecked < counter) {
	        alert('You must make all your picks now, you can update them in your account page.');
	    	return false;
	    }
	});
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
			<h1 id="centeredText">Weekly NFL Schedule</h1>

			<form method="post">
				<select name="week">
					<option id="week1" value="1">Week 1</option>
					<option id="week2" value="2">Week 2</option>
					<option id="week3" value="3">Week 3</option>
					<option id="week4" value="4">Week 4</option>
					<option id="week5" value="5">Week 5</option>
					<option id="week6" value="6">Week 6</option>
					<option id="week7" value="7">Week 7</option>
					<option id="week8" value="8">Week 8</option>
					<option id="week9" value="9">Week 9</option>
					<option id="week10" value="10">Week 10</option>
					<option id="week11" value="11">Week 11</option>
					<option id="week12" value="12">Week 12</option>
					<option id="week13" value="13">Week 13</option>
					<option id="week14" value="14">Week 14</option>
					<option id="week15" value="15">Week 15</option>
					<option id="week16" value="16">Week 16</option>
					<option id="week17" value="17">Week 17</option>
				</select> <input type="submit" id="showWeek" name="showWeek"
					value="Go">
			</form>
	
	
		<?php
	if (isset ( $_POST ['showWeek'] )) {
		$userID = getUserID ( $mysqli, $_SESSION ['username'] );
		$week = $_POST ['week'];
		getWeekForPicks ( $mysqli, $week );
	} else {
		echo 'Please select a week!';
	}
	?>
	</div>
	</div>
<?php include 'pages/footer.php';?>
<?php else :printf ( "<script>location.href='login.php'</script>" );?>
<?php endif; ?>
</body>
</html>