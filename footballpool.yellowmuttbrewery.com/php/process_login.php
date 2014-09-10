<?php
include_once 'functions.php';
sec_session_start(); // Our custom secure way of starting a PHP session.
include_once 'db_connect.php';

if (isset($_POST['username'], $_POST['p'])) {
	$username = $_POST['username'];
	$password = $_POST['p']; // The hashed password.

	if (login($username, $password, $mysqli) == true) {
		if (htmlentities($_SESSION['username']) == 'admin'){
			header('Location: ../adminpage.php');
		}else {
			header('Location: ../home.php');
		}
		
	} else {
		echo "<script>alert('Username or password are incorrect!');window.location.href = \"../login.php\";</script>";
	}
} else {
	echo 'Invalid Request';
}
?>