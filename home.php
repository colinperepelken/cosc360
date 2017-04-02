<?php 
	error_reporting(E_ALL);
	require 'config.php';
	session_start();
	$loggedIn = false;

	// check if logged in
	if (isset($_SESSION['username'])) {
		$username = $_SESSION['username'];
		$loggedIn = true;

		// get the id of the logged in user
	    if ($stmt = $mysqli->prepare("SELECT user_id FROM users WHERE username=?")) {

	    	// bind parameters
	    	$stmt->bind_param("s", $username);
	    	$stmt->execute();
	    	$stmt->bind_result($user_id);
	    	$stmt->fetch();
		    $stmt->close(); // close the statement
	    } 
	}

	// if user is searching for a post using a search term
	if (isset($_GET['search'])) {
		$search_term = $_GET['search'];
		$threads = [];

		if ($stmt = $mysqli->prepare("SELECT thread_id, poster_id, title, content, username FROM threads, users WHERE poster_id=user_id AND title LIKE ? ORDER BY thread_id DESC")) {

			$search_term = '%'.$search_term.'%';
			$stmt->bind_param("s", $search_term);
			$stmt->execute();

			$stmt->bind_result($thread_id, $poster_id, $title, $content, $username);


			// fetch all posts and store in an array $threads
			while ($stmt->fetch()) {
				array_push($threads, [
					'thread_id' => $thread_id,
					'poster_id' => $poster_id,
					'title' => $title,
					'content' => $content,
					'username' => $username
				]);
			}
		}

	} else {


		$threads = [];

		// fetch all posts
		if ($stmt = $mysqli->prepare("SELECT thread_id, poster_id, title, content, username FROM threads, users WHERE poster_id=user_id ORDER BY thread_id DESC")) {
			
			$stmt->execute();

			$stmt->bind_result($thread_id, $poster_id, $title, $content, $username);


			// fetch all posts and store in an array $threads
			while ($stmt->fetch()) {
				array_push($threads, [
					'thread_id' => $thread_id,
					'poster_id' => $poster_id,
					'title' => $title,
					'content' => $content,
					'username' => $username
				]);
			}
		}
	}




?>

<!DOCTYPE html>
<html>
	<head lang="en">
   		<meta charset="utf-8">
   		<title>R3DLINE</title>
   		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Lato">
   		<link rel="stylesheet" href="style/all.css" />
   		<link rel="stylesheet" href="style/simpleform.css" />
   		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	</head>
	<body>
		<header>
			<a href="home.php" id="logo"><img src="images/logo.png" width="300" height="42" /></a>
			<ul>
				<?php if ($loggedIn): ?>
					<li><a href="profile.php?id=<?=$user_id?>"><?=$username?></a></li>
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
					<form action="home.php" method="get" id="search-form">
						<input type="text" placeholder="Enter a search term" name="search">
						<button type="submit" value="Search" form="search-form">Search</button>
					</form>
					<p><a href="">Advanced Search</a></p>
				</div>
				<?php if ($loggedIn): ?>
					<a href="makepost.php"><button type="button">Submit a Post</button></a>
				<?php endif ?>
				<br>
				<p><a href="">Browse Forums</a></p>
				<br>
				<?php if ($loggedIn): ?>
					<p><a href="">My Subscribed</a></p>
				<?php endif ?>
				<div id="forum-info">
					<p>Welcome to R3DLINE, your ultimate source for BMW E30s. Please read our rules and guidelines blah blah blah more forum information stuff</p>
				</div>
			</article>
			<article id="center">
				<?php foreach ($threads as $thread): ?> <!-- loop through all posts -->

					<div class="post">
						<div class="post-score">
							<a href=""><img src="images/arrow_up.png" width="20" height="20"></a>
							<p>2</p>
							<a href=""><img src="images/arrow_down.png" width="20" height="20"></a>
						</div>
						<p class="title"><a href="viewpost.php?id=<?=$thread['thread_id']?>"><?=$thread['title']?></a></p>
						<p class="post-info">submitted 3 hours ago by <a href="profile.php?id=<?=$thread['poster_id']?>"><?=$thread['username']?></a> in <a href="">Members' Cars</a></h2>
						<p class="post-stats">40 replies, 1543 views</p>
					</div>

				<?php endforeach ?>
			</article>
		</div>
		<footer>
			<p>© Colin Bernard 2017</p>
		</footer>
	</body>
</html>