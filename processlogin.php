<?php
	require 'config.php';

	session_start();

	if (isset($_SESSION['username'])) {
		header('Location: home.php'); // if the user is already logged in , redirect to home
	} else {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			if (isset($_POST['username']) && isset($_POST['password'])) {

				$username = $_POST['username'];
				$password = $_POST['password'];



				$userExists = false;

			    // prepare the statement
			    if ($stmt = $mysqli->prepare("SELECT * FROM users WHERE username=? AND password=?")) {
			    	
			    	$password = md5($password);

			    	// bind parameters
			    	$stmt->bind_param("ss", $username, $password);

			    	// execute query
			    	$stmt->execute();

				    // checks if a user already exists
				    while ($stmt->fetch()) {
				    	
				    	$_SESSION['username'] = $username; // store new session
				    	echo "<script>alert(Login Successful!)</script>";
				    	header('Location: home.php'); // re direct user to home page

				    	$userExists = true;
				    	break; // only display the above once
				    }

				    $stmt->close(); // close the statement
			    }

			    if (!$userExists) {
			    	echo "<script>alert(Invalid login!)</script>";
			    	header('Location: login.html'); // if user name or password are invalid/dont exist
			    }



			    $mysqli->close(); // close the connection
				
			}
		} else { // if GET method is used
			header('Location: login.html');
		}
	}

?>