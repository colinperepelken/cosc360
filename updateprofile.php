<?php

	error_reporting(E_ALL);
	require 'config.php';
	session_start();
	$loggedIn = false;


	// check if logged in
	if (isset($_SESSION['username'])) {
		$username = $_SESSION['username'];
		$loggedIn = true;

		// get the id of the logged in user and check if they are admin
	    if ($stmt = $mysqli->prepare("SELECT user_id, is_admin FROM users WHERE username=?")) {

	    	// bind parameters
	    	$stmt->bind_param("s", $username);
	    	$stmt->execute();
	    	$stmt->bind_result($logged_in_user_id, $is_admin);
	    	$stmt->fetch();
		    $stmt->close(); // close the statement
	    }

	    if (isset($_POST['location']) && isset($_POST['bio'])) {
	    	$location = $_POST['location'];
	    	$bio = $_POST['bio'];

	    	if (strlen($location) > 100 || strlen($bio) > 100) { // validation on length
	    		header("Location: profile.php?id=$logged_in_user_id");
	    	}
	    } else {
	    	header('Location: home.php');
	    }

		// update user info
	    if ($stmt = $mysqli->prepare("UPDATE users SET location=?, bio=? WHERE username=?")) {

	    	// bind parameters
	    	$stmt->bind_param("sss", $location, $bio, $username);
	    	$stmt->execute();
		    $stmt->close(); // close the statement


		    header("Location: profile.php?id=$logged_in_user_id"); // go back to profile once info updated
	    } 
	} else {
		header('Location: home.php'); // re direct user if they are not logged in
	}


?>