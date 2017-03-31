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

	// get thread info
	if (isset($_GET['id'])) {

		$thread_id = $_GET['id'];

		// query DB for thread info
		if ($stmt = $mysqli->prepare("SELECT poster_id, title, content FROM threads WHERE thread_id=?;")) { // TODO: add posted_time
		
			$stmt->bind_param("i", $thread_id);

			$stmt->execute();

			$stmt->bind_result($poster_id, $title, $content);

			$thread = []; // store the information associated with this thread in obj

			while ($stmt->fetch()) {
				$thread['poster_id'] = $poster_id;
				$thread['title'] = $title;
				$thread['content'] = $content;
				break; // should only return one thread... but just in case
			}
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
							<li><a href="profile.html"><b>Brad</b></a></li>
							<li><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSa4LNlO5Bf7frUdD0vorSMVi4fJqA-KCpiMhkpw102Ql0OfRMWPekFb7pD" width="80px" height="80px" class="post-profile-picture"/></li>
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
						<button type="button" class="reply-button">Add a Comment</button>
						<a href="javascript:toggleComments()" id="toggle-comments">Hide Comments &uarr;</a>
					</div>
					<!-- comments on the original thread post/entry -->
					<div class="comment">
						<p class="comment-info"><a href=""><b>Anastasia</b></a> <i>(2017-02-17 3:25PM)</i></p>
						<p class="comment-content">Hey have you fermentum id lacus interdum pellentesque. Donec at diam nec lectus efficitur sodales. Pellentesque eget orci dignissim, eleifend tortor aliquet, euismod risus.</p>
					</div>
					<div class="comment">
						<p class="comment-info"><a href=""><b>James Yu</b></a> <i>(2017-02-17 4:58PM)</i></p>
						<p class="comment-content">This is such a sick comment right here!!!!</p>
					</div>
				</div>
				<!-- more thread replies that aren't the original thread post -->
				<div class="reply-entry">
					<div class="user-info">
						<ul>
							<li><a href=""><b>Cassandra</b></a></li>
							<li><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/32/2008-2010_Toyota_Yaris_(NCP93R)_YRS_sedan_(2010-12-28).jpg/250px-2008-2010_Toyota_Yaris_(NCP93R)_YRS_sedan_(2010-12-28).jpg" width="80px" height="80px" class="post-profile-picture"/></li>
							<li>Losertown, BC</li>
							<li>2008 Toyota Yaris Custom</li>
							<li><button type="button">Message</button></li>
						</ul>
					</div>
					<div class="reply-content">
						<p class="reply-date"><i>2017-02-17 5:43PM</i></p>
						<p>Did you torque the tensioner bolts to spec? I had the same issue with my implementation of Ask() Inference engine using inference rules to show KB != beta.
						</p>
						<p>

						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eu mi venenatis, ultricies lacus sed, malesuada dui. Fusce consectetur turpis ac enim iaculis laoreet. Aliquam iaculis, lectus viverra vestibulum imperdiet, sem tellus tincidunt urna, quis congue enim sapien sit amet neque. Donec id quam viverra, porta velit quis, fringilla tellus. Nulla sit amet nulla consequat tellus mollis mollis in et mi. Donec nec erat eget sapien ullamcorper eleifend in eget nisl. Praesent hendrerit velit risus, at interdum purus blandit vel. Aliquam feugiat dolor ex.
						</p>
						<p>
						Vivamus tincidunt erat vel efficitur consequat. Integer semper varius sollicitudin. Sed fermentum id lacus interdum pellentesque. Donec at diam nec lectus efficitur sodales. Pellentesque eget orci dignissim, eleifend tortor aliquet, euismod risus. Donec a efficitur ante. Donec ultrices, libero id tincidunt tincidunt, ex diam dapibus urna, sit amet volutpat lectus nunc in dui. Praesent eleifend vulputate turpis ut commodo. In lobortis viverra pretium. Vestibulum tempus tempus varius.
						</p>
						<p>
						Sed eget maximus erat, nec ullamcorper sapien. Pellentesque tempus ornare justo vitae sodales. Phasellus et ultrices metus. Suspendisse potenti. Vestibulum egestas condimentum sagittis. Fusce non finibus ipsum, vel euismod lectus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur pulvinar ut ex sit amet posuere. Ut gravida, nunc quis imperdiet blandit, libero sem congue ante, ut elementum dolor massa pharetra enim. Pellentesque arcu diam, sodales non dignissim id, sollicitudin id ante. Pellentesque molestie est quis nunc semper, ac ultrices dui eleifend. Curabitur metus felis, porttitor sed gravida venenatis, vehicula ac ligula. Nullam ut ultricies quam. Curabitur sit amet convallis metus, ac venenatis ex. </p>
						<button type="button" class="reply-button">Add a Comment</button>
					</div>
				</div>
			</article>
		</div>
		<footer>
			<p>© Colin Bernard 2017</p>
		</footer>
	</body>
</html>