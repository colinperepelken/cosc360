<?php
	error_reporting(E_ALL);
	require 'config.php';

	$registrationSuccessful = false;

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];



			$userExists = false;

		    // prepare the statement to check if there already exists a user
		    if ($stmt = $mysqli->prepare("SELECT * FROM users WHERE username=? OR email=?")) {

		    	// bind parameters
		    	$stmt->bind_param("ss", $username, $email);

		    	// execute query
		    	$stmt->execute();


			    // checks if a user already exists
			    while ($stmt->fetch()) {
			    	$userExists = true;
			    	break; // only display the above once
			    }

			    $stmt->close(); // close the statement
		    } 

		    // insert the user
		    if (!$userExists) {


				// INSERT NEW USER INTO DB
		    	if ($stmt = $mysqli->prepare("INSERT INTO users(username, email, password) VALUES (?,?,?)")) {

		    		$password = md5($password); // hash the password

		    		// bind params
		    		$stmt->bind_param("sss", $username, $email, $password);

		    		// execute insert
		    		$stmt->execute();
		    		$stmt->close();

		    		$registrationSuccessful = true;
		    	}

		    }

		    $mysqli->close(); // close the connection
			
		} 
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
   		<link rel="stylesheet" href="style/about.css" />
   		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	</head>
	<body>
		<header>
			<a href="home.php" id="logo"><img src="images/logo.png" width="300" height="42" /></a>
			<ul>
				<li><a href="login.html">Login</a></li>
				<li><a href="register.html">Register</a></li>
			</ul>
		</header>
		<article>
			<div id="container">
				<?php if ($registrationSuccessful): ?>
					<h2>Registration Successful</h2>
					<p>Account for user "<?=$username?>" successfully created!</p>
					<p><a href="login.html">Proceed to login page.</a></p>
				<?php else: ?>
					<h2>Error</h2>
					<p>A user with that username and/or email already exists!</p>
					<p><a href="register.html">Back to Registration Page</a></p>
				<?php endif ?>
			</div>
		</article>

		<footer>
			<p>© Colin Bernard 2017</p>
		</footer>
	</body>
</html>
