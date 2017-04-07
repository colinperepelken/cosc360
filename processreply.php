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
		$parent_id = $_POST['parentid'];


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


	    $posted_time = date("Y/m/d");

		if ($stmt = $mysqli->prepare("INSERT INTO thread_replies(poster_id, content, thread_id, posted_time) VALUES (?,?,?,?)")) {

			$stmt->bind_param("isis", $poster_id, $content, $parent_id, $posted_time);

			$stmt->execute();
			
			//header("Location: viewpost.php?id=$parent_id");
		} else {
			echo "Error inputting reply into database.";
		}
	}
?>

<div class="reply-entry" id="new-comment">
	<div class="user-info">
		<ul>
			<li><a href=""><b><?=$_POST['username']?></b></a></li>
			<li><img src="<?=$_POST['image']?>" width="80px" height="80px" class="post-profile-picture" alt="profile picture not set"/></li>
			<li><?=$_POST['location']?></li>
			<li><?=$_POST['bio']?></li>
			<li><button type="button">Message</button></li>
		</ul>
	</div>
	<div class="reply-content">
		<p class="reply-date"><i><?=$posted_time?></i></p>
		<p><?=$content?></p>
	</div>
</div>