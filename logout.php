<?php 

	session_start();

	if (isset($_SESSION['username'])) {
		unset($_SESSION['username']); // log the user out
		header('Location: ' . $_SERVER['HTTP_REFERER']); // re direct to last page
	} else {
		header('Location: login.php'); // user is not logged in, redirect
	}