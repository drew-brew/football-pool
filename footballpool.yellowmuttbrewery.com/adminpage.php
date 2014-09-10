<?php
include_once 'php/db_connect.php';
include_once 'php/functions.php';
sec_session_start ();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>YMB | Admin Page</title>
<meta charset="UTF-8">
</head>
<?php if (login_check($mysqli) == true && htmlentities($_SESSION['username']) == 'admin') : ?>
<h1>Send Text Reminders</h1>
<form method="post" action="php/process_textreminders.php"
	id="textReminders">
	<button type="submit">Send Text Reminders For Week:</button>
	<input type="text" name="weekForReminder" size="2"/>
</form>
<h1>Calculate Score</h1>
<form method="post" action="php/process_scoring.php" id="calcScores">
	<button type="submit" value="Calculate Score">Calculate Score For Week:</button>
	<input type="text" name="weekToScore" size="2"/>
</form>
<h1>Submit Actual Results</h1>
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
	</select>
	<button type="submit" name="showWeek" id="showWeek">Show Week</button>
</form>
	
		<?php
	if (isset ( $_POST ['showWeek'] )) {
		$week = $_POST ['week'];
		getWeekForActualResults ( $mysqli, $week );
	} else {
		echo 'Please select a week!';
	}
	?>
<?php else :  printf("<script>location.href='home.php'</script>"); ?>
<?php endif; ?>
</body>
</html>