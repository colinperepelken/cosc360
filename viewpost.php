<?php 
	error_reporting(E_ALL);
	require 'config.php';
	session_start();
	$loggedIn = false;

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

	// get thread info
	if (isset($_GET['id'])) {

		$thread_id = $_GET['id'];

		// query DB for thread info
		if ($stmt = $mysqli->prepare("SELECT poster_id, title, content, profile_image_path, username FROM threads, users WHERE thread_id=? AND user_id=poster_id;")) { // TODO: add posted_time
		
			$stmt->bind_param("i", $thread_id);

			$stmt->execute();

			$stmt->bind_result($poster_id, $title, $content, $profile_image_path, $username);

			$thread = []; // store the information associated with this thread in obj

			while ($stmt->fetch()) {
				$thread['poster_id'] = $poster_id;
				$thread['title'] = $title;
				$thread['content'] = $content;
				$thread['profile_image_path'] = $profile_image_path;
				$thread['username'] = $username;
				break; // should only return one thread... but just in case
			}
			$stmt->close();
		}
	} else {
		header('Location: home.php');
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
   		<link rel="stylesheet" href="style/viewpost.css" />
   		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

   		<!-- https://www.tinymce.com/download/ -->
		<script src='https://cloud.tinymce.com/stable/tinymce.min.js'></script>
 		<script>
  			tinymce.init({
    			selector: '#mytextarea',
    			plugins: "image",
  				menubar: "insert",
  				toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
  				image_caption: true
 			});
  		</script>
  		<script src="js/togglecomments.js" type="text/javascript"></script>
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
					<form action="home.php" method="get">
						<input type="text" placeholder="Enter a search term">
						<button type="submit">Search</button>
					</form>
					<p><a href="">Advanced Search</a></p>
				</div>
				<button type="button">Add a Reply</button>
				<br>
				<p><a href="">Browse Forums</a></p>
				<br>
				<p><a href="">My Subscribed</a></p>
				<div id="forum-info">
					<p>Welcome to R3DLINE, your ultimate source for BMW E30s. Please read our rules and guidelines blah blah blah more forum information stuff</p>
				</div>
			</article>
			<article id="center">
				<!-- the original thread post/entry -->
				<div class="reply-entry">
					<!-- profile/poster information -->
					<div class="user-info">
						<ul>
							<li><a href="profile.php?id=<?=$thread['poster_id']?>"><b><?=$thread['username']?></b></a></li>
							<li><img src="<?=$thread['profile_image_path']?>" width="80px" height="80px" class="post-profile-picture" alt="profile picture not set"/></li>
							<li>Kelowna, BC</li>
							<li>1987 325is</li>
							<li><button type="button">Message</button></li>
						</ul>
					</div>
					<!-- the reply content -->
					<div class="reply-content">
						<h3><?=$thread['title']?></h3>
						<p class="reply-date"><i>2017-02-17 3:20PM</i></p>
						<p><?=$thread['content']?></p>
					</div>
				</div>

				<?php
					// fetch all thread replies from DB
					$replies = [];
					if ($stmt = $mysqli->prepare("SELECT username, poster_id, content, profile_image_path FROM thread_replies, users WHERE poster_id=user_id AND thread_id=?")) { // TODO: add posted_time
						$stmt->bind_param("s", $thread_id);
						$stmt->execute();

						$stmt->bind_result($username, $poster_id, $content, $profile_image_path);

						while ($stmt->fetch()) {
							echo $content2;
							array_push($replies, [
								'username' => $username,
								'poster_id' => $poster_id,
								'content' => $content,
								'profile_image_path' => $profile_image_path
							]);
						}
					}



				?>
				<!-- more thread replies that aren't the original thread post -->
				<?php foreach ($replies as $reply): ?>
					<div class="reply-entry">
						<div class="user-info">
							<ul>
								<li><a href=""><b><?=$reply['username']?></b></a></li>
								<li><img src="<?=$reply['profile_image_path']?>" width="80px" height="80px" class="post-profile-picture" alt="profile picture not set"/></li>
								<li>Sometown, BC</li>
								<li>1989 325ic</li>
								<li><button type="button">Message</button></li>
							</ul>
						</div>
						<div class="reply-content">
							<p class="reply-date"><i>2017-02-17 5:43PM</i></p>
							<p><?=$reply['content']?></p>
						</div>
					</div>
				<?php endforeach ?>
				<?php if ($loggedIn): ?>
					<div class="reply-entry">
						<form id="post-form" method="post" action="processreply.php">
							<textarea id="mytextarea" form="post-form" name="content" class="required"></textarea>
							<input type="hidden" name="parent-id" value="<?=$thread_id?>" />
							<button type="Submit" form="post-form" value="Submit">Add a Reply</button>
						</form>
					</div>

				<?php else: ?>
					<div class="reply-entry">
						<p>Login to add a reply...</p>
					</div>
				<?php endif ?>
			</article>
		</div>
		<footer>
			<p>© Colin Bernard 2017</p>
		</footer>
	</body>
</html>