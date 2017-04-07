<?php

	error_reporting(E_ALL);
	require 'config.php';
	session_start();


	// check if logged in
	if (isset($_SESSION['username'])) {
		$username = $_SESSION['username'];


		// get the id of the logged in user and check if they are admin
	    if ($stmt = $mysqli->prepare("SELECT user_id, is_admin FROM users WHERE username=?")) {

	    	// bind parameters
	    	$stmt->bind_param("s", $username);
	    	$stmt->execute();
	    	$stmt->bind_result($user_id, $is_admin);
	    	$stmt->fetch();
		    $stmt->close(); // close the statement
	    }


	    if ($is_admin == 1) { // if the user is logged in and an admin


	    	if (isset($_GET['ban'])) { // banning a user
	    		$user_to_ban = $_GET['ban'];
	    		if ($stmt = $mysqli->prepare("UPDATE users SET is_banned=1 WHERE user_id=?")) {
	    			$stmt->bind_param("i", $user_to_ban);
	    			$stmt->execute();
	    		}
	    	}

	    	if (isset($_GET['unban'])) { // unbanning a user
	    		$user_to_unban = $_GET['unban'];
	    		if ($stmt = $mysqli->prepare("UPDATE users SET is_banned=0 WHERE user_id=?")) {
	    			$stmt->bind_param("i", $user_to_unban);
	    			$stmt->execute();
	    		}
	    	}

	    	if (isset($_GET['remove'])) { // removing a thread
	    		$threadToRemove = $_GET['remove'];
	    		if ($stmt = $mysqli->prepare("DELETE FROM threads WHERE thread_id=?")) {
	    			$stmt->bind_param("i", $threadToRemove);
	    			$stmt->execute();
	    		}
	    	}

	    	$users = [];

	    	if (isset($_GET['username'])) { // searching for a user
	    		$userToSearch = $_GET['username'];
	    		if ($stmt = $mysqli->prepare("SELECT username, is_banned, is_admin, email, user_id FROM users WHERE username LIKE ?")) {
	    			$userToSearch = '%'.$userToSearch.'%';
	    			$stmt->bind_param("s", $userToSearch);
	    			$stmt->execute();
	    			$stmt->bind_result($username, $is_banned, $is_admin, $email, $user_id);

		    		while ($stmt->fetch()) {
		    			array_push($users, [
		    				'username' => $username,
		    				'is_banned' => $is_banned,
		    				'is_admin' => $is_admin,
		    				'email' => $email,
		    				'user_id' => $user_id
		    			]);
		    		}
	    		}
	    	} else { // get list of ALL users
	    		if ($stmt = $mysqli->prepare("SELECT username, is_banned, is_admin, email, user_id FROM users")) {
	    	
		    		$stmt->execute();
		    		$stmt->bind_result($username, $is_banned, $is_admin, $email, $user_id);

		    		while ($stmt->fetch()) {
		    			array_push($users, [
		    				'username' => $username,
		    				'is_banned' => $is_banned,
		    				'is_admin' => $is_admin,
		    				'email' => $email,
		    				'user_id' => $user_id
		    			]);
		    		}

	    		}
	    	}

	    	$threads = [];
	    	if (isset($_GET['title'])) { // seaching for a thread title

	    		$titleToSearch = $_GET['title'];
	    		if ($stmt = $mysqli->prepare("SELECT title, thread_id FROM threads WHERE title LIKE ?")) {
	    			$titleToSearch = '%'.$titleToSearch.'%';
	    			$stmt->bind_param("s", $titleToSearch);
	    			$stmt->execute();
	    			$stmt->bind_result($title, $thread_id);

	    			while ($stmt->fetch()) {
	    				array_push($threads, [
	    					'thread_id' => $thread_id,
	    					'title' => $title
	    				]);
	    			}
	    		}

	    	} else { // show ALL threads
	    		if ($stmt = $mysqli->prepare("SELECT title, thread_id FROM threads")) {
	    			$stmt->execute();
	    			$stmt->bind_result($title, $thread_id);

	    			while ($stmt->fetch()) {
	    				array_push($threads, [
	    					'thread_id' => $thread_id,
	    					'title' => $title
	    				]);
	    			}
	    		}
	    	}




	    } else {
	    	header('Location: home.php');
	    }

	} else {
		header('Location: home.php');
	}


?>
<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8">
		<title>Admin</title>
	</head>
	<body>
		<h1>Admin Controls</h1>
		<p><a href="home.php">Back to Home</a></p>
		<form method="get" action="admin.php">
			<input type="text" name="username" placeholder="search for a username"/>
			<input type="text" name="title" placeholder="search for a thread title"/>
			<input type="submit" value="Search" name="submit"/>
		</form>
		<br>
		<hr>
		<br>
		<table>
			<caption>Users</caption>
			<tr>
				<th>user_id</th>
				<th>username</th>
				<th>email</th>
				<th>is_admin</th>
				<th>is_banned</th>
			</tr>
			<?php foreach ($users as $user): ?>
				<tr>
					<td><?=$user['user_id']?></td>
					<td><?=$user['username']?></td>
					<td><?=$user['email']?></td>
					<td><?=$user['is_admin']?></td>
					<td><?=$user['is_banned']?></td>
					<?php if ($user['is_banned']==0): ?>
						<td><a href="admin.php?ban=<?=$user['user_id']?>">ban user</a></td>
					<?php else: ?>
						<td><a href="admin.php?unban=<?=$user['user_id']?>">unban user</a></td>
					<?php endif ?>
				</tr>
			<?php endforeach ?>
		</table>
		<br>
		<hr>
		<br>
		<table>
			<caption>Threads</caption>
			<tr>
				<th>thread_id</th>
				<th>title</th>
			</tr>
			<?php foreach ($threads as $thread): ?>
				<tr>
					<td><?=$thread['thread_id']?></td>
					<td><?=$thread['title']?></td>
					<td><a href="admin.php?remove=<?=$thread['thread_id']?>">Remove</a></td>
				</tr>
			<?php endforeach ?>
		</table>
	</body>
</html>