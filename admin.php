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


	    	if (isset($_GET['ban'])) {
	    		$user_to_ban = $_GET['ban'];
	    		if ($stmt = $mysqli->prepare("UPDATE users SET is_banned=1 WHERE user_id=?")) {
	    			$stmt->bind_param("i", $user_to_ban);
	    			$stmt->execute();
	    		}
	    	}

	    	if (isset($_GET['unban'])) {
	    		$user_to_unban = $_GET['unban'];
	    		if ($stmt = $mysqli->prepare("UPDATE users SET is_banned=0 WHERE user_id=?")) {
	    			$stmt->bind_param("i", $user_to_unban);
	    			$stmt->execute();
	    		}
	    	}


	    	// get list of all users
	    	$users = [];
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
		<table>
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
	</body>
</html>