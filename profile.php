<?php 
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
	    	$stmt->bind_result($user_id, $is_admin);
	    	$stmt->fetch();
		    $stmt->close(); // close the statement
	    } 
	}

	if (isset($_GET['id'])) {
		$user_id = $_GET['id'];

		// fetch user from DB with this id
	    if ($stmt = $mysqli->prepare("SELECT username, email, profile_image_path FROM users WHERE user_id=?")) {

	    	// bind parameters
	    	$stmt->bind_param("i", $user_id);
	    	$stmt->execute();
	    	$stmt->bind_result($username, $email, $profile_image_path);


		    while ($stmt->fetch()) {
		    	$userExists = true;
		    	break; // only want one user
		    }

		    $stmt->close(); // close the statement
	    } 

	    if (!$userExists) {
	    	header('Location: home.php'); // if no user exists with that ID
	    }

	} else {
		header('Location: home.php'); // if no ID is set then re direct to home page
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
   		<link rel="stylesheet" href="style/profile.css" />
   		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	</head>
	<body>
		<header>
			<a href="home.php" id="logo"><img src="images/logo.png" width="300" height="42" /></a>
			<ul>
				<?php if ($loggedIn): ?>
					<?php if ($is_admin == 1): ?>
						<li><a href="admin.php">Admin Controls</a></li>
					<?php endif ?>
					<li><a href="profile.php?id=<?=$user_id?>"><?=$_SESSION['username']?></a></li>
					<li><a href="logout.php">Logout</a></li>
				<?php else: ?>
					<li><a href="login.html">Login</a></li>
					<li><a href="register.html">Register</a></li>
				<?php endif ?>
			</ul>
		</header>
		<div id="main">
			<article id="right-sidebar">
				<div id="sidebar-search">
					<form action="home.php" method="get" enctype="multipart/form-data">
						<input type="text" placeholder="Enter a search term">
						<button type="submit">Search</button>
					</form>
					<p><a href="">Advanced Search</a></p>
				</div>
				<a href="makepost.php"><button type="button">Submit a Post</button></a>
				<br>
				<p><a href="">Browse Forums</a></p>
				<br>
				<p><a href="">My Subscribed</a></p>
				<div id="forum-info">
					<p>Welcome to R3DLINE, your ultimate source for BMW E30s. Please read our rules and guidelines blah blah blah more forum information stuff</p>
				</div>
			</article>
			<article id="center">
			<h2><?=$username?>'s Profile</h2>
				<div id="profile-left">
					<img src="<?=$profile_image_path?>" alt="Profile image" width="450" height="320">
					<?php if ($logged_in_user_id == $user_id): ?> <!-- if user is vewing their own profile -->
						<form action="processimage.php" method="post" enctype="multipart/form-data">
						    <input type="file" name="fileToUpload" id="fileToUpload">
						    <input type="submit" value="Upload Image" name="submit">
						</form>
					<? endif ?>
					<ul>
						<li>Location: Kelowna, BC</li>
						<li>Email: <?=$email?></li>
						<li>Car(s): 1989 325i Convertible</li>
						<li>colinbernard.ca</li>
						<li><button type="button">Message</button></li>
					</ul>
				</div>
				<div id="profile-statistics">
					<h3>Statistics</h3>
					<ul>
						<li>Posts: 27</li>
						<li>Posts Per Day: 0.01</li>
						<li>Account created: 2015-02-23</li>
						<li>Last activity: 2017-03-01</li>
						<li><button type="button">View Posts by <?=$username?></button></li>
					</ul> 
				</div>
				<div id="profile-friends">
					<h3>Friends</h3>
					<ul>
						<li>adamturner</li>
						<li>e30sanfran1</li>
						<li>capozziE30</li>
						<li>ScottProf</li>
						<li><button type="button">Add Friend</button></li>
					</ul> 
				</div>
			</article>
		</div>
		<footer>
			<p>© Colin Bernard 2017</p>
		</footer>
	</body>
</html>