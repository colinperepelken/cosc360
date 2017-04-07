<?php

	error_reporting(E_ALL);
	require 'config.php';
	session_start();
	$loggedIn = false;
	$success = false;

	// check if logged in
	if (isset($_SESSION['username'])) {
		$username = $_SESSION['username'];
		$loggedIn = true;
	}

	if (!$loggedIn) { // if the user is not logged in, proceed to password reset

		// get email
		if (isset($_POST['email'])) {
			$email = $_POST['email'];

			// reset password
			$newPass = "colinisdabomb";
			$newPassHashed = md5($newPass); // hash it

			if ($stmt = $mysqli->prepare("UPDATE users SET password=? WHERE email=?")) {
				$stmt->bind_param("ss", $newPassHashed, $email);
				$stmt->execute();

				// send an email with the temp password
				mail($email,"EZRyderz Password Reset", "Your new password is $newPass\nPlease log in at colinbernard.ca/cosc360/login.html");
				$success = true;
			} 
		}



	} else {
		header('Location: home.php'); // if the user IS logged in redirect to home... they dont need to recover pass
	}

?>

<!DOCTYPE html>
<html>
	<head lang="en">
   		<meta charset="utf-8">
   		<title>R3DLINE</title>
   		<link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Lato">
   		<link rel="stylesheet" href="style/all.css" />
   		<link rel="stylesheet" href="style/simpleform.css" />
   		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
   		<script type="text/javascript" src="js/register.js"></script>
	</head>
	<body>
		<header>
			<a href="home.php" id="logo"><img src="images/logo.png" width="300" height="42" /></a>
			<ul>
				<li><a href="login.html">Login</a></li>
				<li><a href="register.html">Register</a></li>
			</ul>
		</header>

		<div id="register">
			<h2>Password Reset</h2>
			<?php if($success): ?>
				<p>A temporary password has been sent to: <?=$email?></p>
			<?php else: ?>
				<p>Something went wrong trying to reset your password.</p>
			<?php endif ?>
			<a href="login.html">Back to Login</a> 
		</div>
		<footer>
			<p>© Colin Bernard 2017</p>
		</footer>
	</body>
</html>