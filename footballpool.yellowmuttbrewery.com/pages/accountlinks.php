<ul>
	<li><a href="modifyaccount.php">View Account Details</a></li>
	<li><a href="viewmypicks.php">View My Picks</a></li>
	<li><a href="viewuserpicks.php">View Other User's Picks</a></li>
	<li><a href="updatepicks.php">Update My Picks</a></li>
	<li><a href="standings.php">View Standings</a></li>
	<?php if (htmlentities($_SESSION['username']) == 'admin') : ?>
	<li><a href="adminpage.php">Admin Page</a></li>
	<?php endif; ?>
</ul>