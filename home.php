<?php 
	error_reporting(E_ALL);
	require 'config.php';
	session_start();
	$loggedIn = false;

	// check if logged in
	if (isset($_SESSION['username'])) {
		$username = $_SESSION['username'];
		$loggedIn = true;
	}


	$threads = [];

	// fetch all posts
	if ($stmt = $mysqli->prepare("SELECT thread_id, poster_id, title, content FROM threads")) {
		
		$stmt->execute();

		$stmt->bind_result($thread_id, $poster_id, $title, $content);


		// fetch all posts and store in an array $threads
		while ($stmt->fetch()) {
			array_push($threads, [
				'thread_id' => $thread_id,
				'poster_id' => $poster_id,
				'title' => $title,
				'content' => $content
			]);
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
					<li><a href="profile.html"><?=$username?></a></li>
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
					<form action="home.php" method="get">
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
				<?php foreach ($threads as $thread): ?> <!-- loop through all posts -->

					<div class="post">
						<div class="post-score">
							<a href=""><img src="images/arrow_up.png" width="20" height="20"></a>
							<p>2</p>
							<a href=""><img src="images/arrow_down.png" width="20" height="20"></a>
						</div>
						<p class="title"><a href="viewpost.php?id=<?=$thread['thread_id']?>"><?=$thread['title']?></a></p>
						<p class="post-info">submitted 3 hours ago by <a href="profile.html">Colin</a> in <a href="">Members' Cars</a></h2>
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