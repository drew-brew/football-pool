<div id="header">
	<div id="headerLeft">
		<p id="leftTopText">WELCOME, <?php echo htmlentities($_SESSION['username'])?>!</p>
	</div>
	<div id="headerCenter">
		<a id="logoLink" href="../home.php"><img src="images/circlelogo.svg" alt="background image"
			id="backgroundimage"></a>
	</div>
	<div id="headerRight">
		<a id="rightTopLink" href="php/logout.php">LOGOUT</a><a
			id="rightTopLink" href="account.php">ACCOUNT</a>
	</div>
</div>