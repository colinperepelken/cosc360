<?php

	require 'config.php';
	session_start();

	// check if user is logged in
	if (isset($_SESSION['username'])) {
		$loggedIn = true;
		$username = $_SESSION['username'];
	} else {
		$loggedIn = false;
	}

	if ($loggedIn && isset($_POST['content'])) {

		$content = $_POST['content'];
		$parent_id = $_POST['parent-id'];


		// get the id of the logged in user
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



		if ($stmt = $mysqli->prepare("INSERT INTO thread_replies(poster_id, content, thread_id) VALUES (?,?,?)")) {

			$stmt->bind_param("isi", $poster_id, $content, $parent_id);

			$stmt->execute();
			
			header("Location: viewpost.php?id=$parent_id");
		} else {
			echo "Error inputting reply into database.";
		}
	}