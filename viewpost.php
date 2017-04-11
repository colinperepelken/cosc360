<?php 
	error_reporting(E_ALL);
	require 'config.php';
	session_start();
	$loggedIn = false;

	if (isset($_SESSION['username'])) {
		$username = $_SESSION['username'];
		$loggedIn = true;

		// get the id of the logged in user and check if they are admin
	    if ($stmt = $mysqli->prepare("SELECT user_id, is_admin, location, bio, profile_image_path FROM users WHERE username=?")) {

	    	// bind parameters
	    	$stmt->bind_param("s", $username);
	    	$stmt->execute();
	    	$stmt->bind_result($user_id, $is_admin, $logged_in_location, $logged_in_bio, $logged_in_image);
	    	$stmt->fetch();
		    $stmt->close(); // close the statement
	    } 
	}

	// get thread info
	if (isset($_GET['id'])) {

		$thread_id = $_GET['id'];

		// query DB for thread info
		if ($stmt = $mysqli->prepare("SELECT poster_id, title, content, profile_image_path, username, location, bio, posted_time FROM threads, users WHERE thread_id=? AND user_id=poster_id;")) { // TODO: add posted_time
		
			$stmt->bind_param("i", $thread_id);

			$stmt->execute();

			$stmt->bind_result($poster_id, $title, $content, $profile_image_path, $username, $location, $bio, $posted_time);

			$thread = []; // store the information associated with this thread in obj

			while ($stmt->fetch()) {
				$thread['poster_id'] = $poster_id;
				$thread['title'] = $title;
				$thread['content'] = $content;
				$thread['profile_image_path'] = $profile_image_path;
				$thread['username'] = $username;
				$thread['location'] = $location;
				$thread['bio'] = $bio;
				$thread['posted_time'] = $posted_time;
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
   		<script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script><!-- jquery for AJAX -->
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
  		<script type="text/javascript">
  			function post() {

  				var reply = tinyMCE.activeEditor.getContent({format : 'raw'});
  				var name = document.getElementById("reply-username").value;
  				var location = document.getElementById("reply-location").value;
  				var bio = document.getElementById("reply-bio").value;
  				var image = document.getElementById("reply-image").value;
  				var parent_id = document.getElementById("parent-id").value;


  				if (reply && name) {
  					$.ajax
  					({
  						type: 'post',
  						url: 'processreply.php',
  						data:
  						{
  							content:reply,
  							parentid:parent_id,
  							username:name,
  							location:location,
  							bio:bio,
  							image:image
  						},
  						success: function (response) 
  						{
  							var center = document.getElementById("center-container");

  							center.innerHTML = center.innerHTML + response; // add new comment

  							tinyMCE.activeEditor.setContent(''); // clear text area
  						}
  					});
  				}
  				return false;
  			}
  		</script>
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
					<form action="home.php" method="get">
						<input type="text" placeholder="Enter a search term">
						<button type="submit">Search</button>
					</form>
					<p><a href="">Advanced Search</a></p>
				</div>
				<br>
				<br>
				<div id="forum-info">
					<p>Welcome to R3DLINE, your ultimate source for BMW E30s. Please read our rules and guidelines blah blah blah more forum information stuff</p>
				</div>
			</article>
			<article id="center">
				<div id="center-container">
				<!-- the original thread post/entry -->
				<div class="reply-entry">
					<!-- profile/poster information -->
					<div class="user-info">
						<ul>
							<li><a href="profile.php?id=<?=$thread['poster_id']?>"><b><?=$thread['username']?></b></a></li>
							<li><img src="<?=$thread['profile_image_path']?>" width="80px" height="80px" class="post-profile-picture" alt="profile picture not set"/></li>
							<li><?=$thread['location']?></li>
							<li><?=$thread['bio']?></li>
							<li><button type="button">Message</button></li>
						</ul>
					</div>
					<!-- the reply content -->
					<div class="reply-content">
						<h3><?=$thread['title']?></h3>
						<p class="reply-date"><i><?=$thread['posted_time']?></i></p>
						<p><?=$thread['content']?></p>
					</div>
				</div>

				<?php
					// fetch all thread replies from DB
					$replies = [];
					if ($stmt = $mysqli->prepare("SELECT username, poster_id, content, profile_image_path, location, bio, posted_time FROM thread_replies, users WHERE poster_id=user_id AND thread_id=?")) { // TODO: add posted_time
						$stmt->bind_param("s", $thread_id);
						$stmt->execute();

						$stmt->bind_result($username, $poster_id, $content, $profile_image_path, $location, $bio, $posted_time);

						while ($stmt->fetch()) {
							array_push($replies, [
								'username' => $username,
								'poster_id' => $poster_id,
								'content' => $content,
								'profile_image_path' => $profile_image_path,
								'location' => $location,
								'bio' => $bio,
								'posted_time' => $posted_time
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
								<li><?=$reply['location']?></li>
								<li><?=$reply['bio']?></li>
								<li><button type="button">Message</button></li>
							</ul>
						</div>
						<div class="reply-content">
							<p class="reply-date"><i><?=$reply['posted_time']?></i></p>
							<p><?=$reply['content']?></p>
						</div>
					</div>
				<?php endforeach ?>
				</div>
				<?php if ($loggedIn): ?>
					<div class="reply-entry">
						<form id="post-form" method="post" action="" onsubmit="return post();">
							<textarea id="mytextarea" form="post-form" name="content" class="required"></textarea>
							<input type="hidden" id="parent-id" name="parentid" value="<?=$thread_id?>" />
							<input type="hidden" id="reply-username" name="username" value="<?=$_SESSION['username']?>"/>
							<input type="hidden" id="reply-image" name="image" value="<?=$logged_in_image?>"/>
							<input type="hidden" id="reply-location" name="location" value="<?=$logged_in_location?>"/>
							<input type="hidden" id="reply-bio" name="bio" value="<?=$logged_in_bio?>"/>
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