<?php

	/* DATABASE CONNECTION INFORMATION */

	$DBHOST = "colinbernard.ca";
	$DBNAME = "cosc360";
	$DBUSER = "c31141147";
	$DBPASSWORD = "121796";

	$mysqli = mysqli_connect($DBHOST, $DBUSER, $DBPASSWORD, $DBNAME);

	$error = mysqli_connect_error();
	if ($error != null) {
		$output = "<p>Unable to connect to database!</p>";
		exit($output);
	}
