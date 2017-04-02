<?php
	error_reporting(E_ALL);
	require 'config.php';
	session_start();


	if (isset($_SESSION['username'])) {
		$username = $_SESSION['username']; // retrieve user name from session
	} else {
		header('Location: home.php'); // if the user is not logged in re direct them to home
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		if (isset($_POST['title']) && isset($_POST['content']) && isset($username)) {

			$title = $_POST['title'];
			$content = $_POST['content'];


			$userExists = false;
			$poster_id = "";

		    // prepare the statement to fetch the user id from the session username
		    if ($stmt = $mysqli->prepare("SELECT user_id FROM users WHERE username=?")) {

		    	// bind parameters
		    	$stmt->bind_param("s", $username);

		    	// execute query
		    	$stmt->execute();

		    	$stmt->bind_result($user_id);

			    // fetch the user with the username who is posting
			    while ($stmt->fetch()) {
			    	$poster_id = $user_id;
			    	$userExists = true;
			    	break; // only display the above once
			    }

			    $stmt->close(); // close the statement
		    } 

		    
		    if ($userExists) {
		    	echo $poster_id;
		    	echo $title;
		    	echo $content;

				// INSERT NEW USER INTO DB
		    	if ($stmt = $mysqli->prepare("INSERT INTO threads(poster_id, title, content) VALUES (?,?,?)")) {

		    		// bind params
		    		$stmt->bind_param("sss", $poster_id, $title, $content); 
		    		// execute insert
		    		$stmt->execute();
		    		$stmt->close();


		    		// get the id of the thread that was just created so can re direct to it
		    		if ($stmt = $mysqli->prepare("SELECT thread_id FROM threads WHERE poster_id=? AND title=? AND content=?;")) {
		    			
		    			$stmt->bind_param("iss", $poster_id, $title, $content);
		    			$stmt->execute();
		    			$stmt->bind_result($thread_id);
		    			$stmt->fetch();
		    			header("Location: viewpost.php?id=$thread_id"); // re direct to the post that was just made
		    		}
		    	}
		    }

		    $mysqli->close(); // close the connection
			
		} 
	} else {
		header('Location: home.php'); // if GET is used, re direct home page
	}