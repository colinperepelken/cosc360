<?php 
	require 'config.php';
	session_start();
	$loggedIn = false;

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
   		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

   		<script type="text/javascript" src="js/makepost.js"></script>

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
				<button type="button">Submit a Post</button>
				<br>
				<p><a href="">Browse Forums</a></p>
				<br>
				<p><a href="">My Subscribed</a></p>
				<div id="forum-info">
					<p>Welcome to R3DLINE, your ultimate source for BMW E30s. Please read our rules and guidelines blah blah blah more forum information stuff</p>
				</div>
			</article>
			<article id="center">
				<div class="container">
					<h2>Posting in Engines/M20</h2>
					<form method="post" action="processpost.php" id="post-form">
						<fieldset>
							<label><b>Title</b></label>
							<input type="text" name="title" id="title" class="required"/>

							<label><b>Content</b></label>
							<textarea id="mytextarea" form="post-form" name="content" class="required"></textarea>

							<button type="Submit" form="post-form" value="Submit">Submit</button>
						</fieldset>
					</form>
				</div>
			</article>
		</div>
		<footer>
			<p>© Colin Bernard 2017</p>
		</footer>
	</body>
</html>