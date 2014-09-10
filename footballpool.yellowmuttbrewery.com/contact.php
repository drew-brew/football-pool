<?php
include_once 'php/db_connect.php';
include_once 'php/functions.php';
sec_session_start ();
?>
<!DOCTYPE html>
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css" />
<meta charset="UTF-8">
<title>YMB | Contact Us</title>
<script type="text/JavaScript">
$(window).load(function(){
	$("body").fadeIn(0);
});
</script>
<script type="text/javascript">
   $(document).ready(function () {
	   $("#contactForm [name='name']").focus();
   });
</script>
</head>
<body>
<?php if (login_check($mysqli) == true) : ?>
<?php include 'pages/background.php';?>
<?php include 'pages/headerloggedin.php';?>
<div id="page">
<?php include 'pages/nav.php';?>
	<div id="pagecontent">
		<form id="contactForm" action="php/mail.php" method="post">
			<h4>Please Fill Out the Form to Contact Us</h4>
			<p>
				Please feel free to submit any questions, requests, or comments you
				may have. I will get back to you ASAP. </p><br><label><b>Name: </b></label>
				<input type="text" name="name" size="25" /><br> <br> <label><b>Email:
				</b></label> <input type="text" name="email" size="25" /><br> <br> <label><b>Subject:
				</b></label> <input type="text" name="subject" size="24" /><br> <br>
				<label><b>Message: </b></label>
				<textarea cols=40 rows=10 name="message"></textarea>
				<br> <input type="submit" value="Send" />
		
		</form>
	</div>
</div>
<?php include 'pages/footer.php';?>
<?php else : printf ( "<script>location.href='login.php'</script>" );?>
<?php endif; ?>		
</body>
</html>