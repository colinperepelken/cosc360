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
	    	$stmt->bind_result($user_id, $is_admin);
	    	$stmt->fetch();
		    $stmt->close(); // close the statement
	    } 
	}

	if (isset($_GET['forum'])) { // user has selected a forum
		$current_forum_id = $_GET['forum'];

		// get the name of this forum
		if ($stmt = $mysqli->prepare("SELECT forum_name FROM forums WHERE forum_id=?")) {
			$stmt->bind_param("i", $current_forum_id);
			$stmt->execute();
			$stmt->bind_result($current_forum_name);
			$stmt->fetch();
		}

		// posts will be filtered below when pulled from DB
	}

	if (isset($_GET['upts'])) { // increase points of a thread
		$upts = $_GET['upts'];

		if ($stmt = $mysqli->prepare("UPDATE threads SET points=points+1 WHERE thread_id=?")) {
			$stmt->bind_param("i", $upts);
			$stmt->execute();
		}
	}

	if (isset($_GET['dpts'])) { // decrease points of a thread
		$dpts = $_GET['dpts'];

		if ($stmt = $mysqli->prepare("UPDATE threads SET points=points-1 WHERE thread_id=?")) {
			$stmt->bind_param("i", $dpts);
			$stmt->execute();
		}
	}


	// get list of forums
	$forums = [];
	if ($stmt = $mysqli->prepare("SELECT forum_id, forum_name FROM forums")) {
		$stmt->execute();
		$stmt->bind_result($forum_id, $forum_name);

		while ($stmt->fetch()) {
			array_push($forums, [
				'forum_id' => $forum_id,
				'forum_name' => $forum_name
			]);
		}
	}


	// if user is searching for a post using a search term
	if (isset($_GET['search'])) {
		$search_term = $_GET['search'];
		$threads = [];

		if (isset($current_forum_name)) { // user if browsing in a forum
			$sql = "SELECT thread_id, poster_id, title, content, username, posted_time, forum_name, forums.forum_id, points FROM threads, users, forums WHERE poster_id=user_id AND title LIKE ? AND threads.forum_id=? AND forums.forum_id=threads.forum_id ORDER BY thread_id DESC";
		} else {
			$sql = "SELECT thread_id, poster_id, title, content, username, posted_time, forum_name, forums.forum_id, points FROM threads, users, forums WHERE poster_id=user_id AND title LIKE ? AND forums.forum_id=threads.forum_id ORDER BY thread_id DESC";
		}

		if ($stmt = $mysqli->prepare($sql)) {

			$search_term = '%'.$search_term.'%';

			if (isset($current_forum_id)) {
				$stmt->bind_param("si", $search_term, $current_forum_id);
			} else {
				$stmt->bind_param("s", $search_term);
			}

			$stmt->execute();

			$stmt->bind_result($thread_id, $poster_id, $title, $content, $username, $posted_time, $forum_name, $forum_id, $points);


			// fetch all posts and store in an array $threads
			while ($stmt->fetch()) {
				array_push($threads, [
					'thread_id' => $thread_id,
					'poster_id' => $poster_id,
					'title' => $title,
					'content' => $content,
					'username' => $username,
					'posted_time' => $posted_time,
					'forum_name' => $forum_name,
					'forum_id' => $forum_id,
					'points' => $points
				]);
			}
		}

	} else { // if the user is not searching for a specific post, get them all

		if (isset($current_forum_name)) { // user is browsing in a forum
			$sql = "SELECT thread_id, poster_id, title, content, username, posted_time, forum_name, forums.forum_id, points FROM threads, users, forums WHERE poster_id=user_id AND threads.forum_id=? AND threads.forum_id=forums.forum_id ORDER BY thread_id DESC";
		} else {
			$sql = "SELECT thread_id, poster_id, title, content, username, posted_time, forum_name, forums.forum_id, points FROM threads, users, forums WHERE poster_id=user_id AND threads.forum_id=forums.forum_id ORDER BY thread_id DESC";
		}

		$threads = [];

		// fetch all posts
		if ($stmt = $mysqli->prepare($sql)) {

			if (isset($current_forum_name)) { // if a forum is specified
				$stmt->bind_param("i", $current_forum_id);
			}
			
			$stmt->execute();

			$stmt->bind_result($thread_id, $poster_id, $title, $content, $username, $posted_time, $forum_name, $forum_id, $points);


			// fetch all posts and store in an array $threads
			while ($stmt->fetch()) {
				array_push($threads, [
					'thread_id' => $thread_id,
					'poster_id' => $poster_id,
					'title' => $title,
					'content' => $content,
					'username' => $username,
					'posted_time' => $posted_time,
					'forum_name' => $forum_name,
					'forum_id' => $forum_id,
					'points' => $points
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
   		<script src="js/browseforums.js" type="text/javascript"></script>
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
				<p><a href="javascript:toggleForums()" id="forum-toggle">Browse Forums</a></p>
				<div id="forum-links">
						<ul>
							<?php foreach ($forums as $forum): ?>
								<li><a href="home.php?forum=<?=$forum['forum_id']?>"><?=$forum['forum_name']?></a></li>
							<?php endforeach ?>
						</ul>
					</div>
				<br>
				<?php if ($loggedIn): ?>
					<p><a href="">My Subscribed</a></p>
				<?php endif ?>
				<div id="forum-info">
					<p>Welcome to R3DLINE, your ultimate source for BMW E30s. Please read our rules and guidelines blah blah blah more forum information stuff</p>
				</div>
			</article>
			<article id="center">
				<?php if (isset($current_forum_name)): ?>
					<h2>Forum: <?=$current_forum_name?></h2>
				<?php else: ?>
					<h2>Forum: all</h2>
				<?php endif ?>
				<?php foreach ($threads as $thread): ?> <!-- loop through all posts -->

					<div class="post">
						<div class="post-score">
							<a href="home.php?upts=<?=$thread['thread_id']?>"><img src="images/arrow_up.png" width="20" height="20"></a>
							<p><?=$thread['points']?></p>
							<a href="home.php?dpts=<?=$thread['thread_id']?>"><img src="images/arrow_down.png" width="20" height="20"></a>
						</div>
						<p class="title"><a href="viewpost.php?id=<?=$thread['thread_id']?>"><?=$thread['title']?></a></p>
						<p class="post-info">submitted on <?=$thread['posted_time']?> ago by <a href="profile.php?id=<?=$thread['poster_id']?>"><?=$thread['username']?></a> in <a href="home.php?forum=<?=$thread['forum_id']?>"><?=$thread['forum_name']?></a></h2>
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