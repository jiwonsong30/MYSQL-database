<!DOCTYPE html>
<html>
<head>
<title>Home Page</title>    
</head>
<body>
	
	<h2>News Website</h2>
	<?php
	session_start();
	
	//Check whether any user has logged in or not
	if (!isset($_SESSION['login_user'])) {
	    echo '<br><a href = "insert_user.php">' . 'New User? Register Here' . '</a>';
	    echo '<br><a href = "login.php">' . 'Already a member? Login Here' . '</a>';
	} else {
	    $login_user = $_SESSION['login_user'];
	    echo "You are now logged in as " . $login_user;
	    echo '<br><a href = "my_account.php">' . 'Go to MyAccount' . '</a>';
	    echo '<br><a href = "logout.php">' . 'Logout' . '</a>';
	}
	
	//Start pull out all contents of commentaries from the database
	require 'database.php';
	$stmt = $mysqli->prepare("select username, article_title, article_link, commentary_title, commentary, created from stories order by username");
	if (!$stmt) {
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
	}
	$stmt->execute();
	$stmt->bind_result($user, $article_title, $article_link, $commentary_title, $commentary, $created);
	echo '<br><br>Please click on a button to view that post.';
	echo '<form action="story.php" method= "POST">';
	//make sure we exhaust all the data saved in MySQL
	while ($stmt->fetch()) {
	    echo '<br><input type="radio" name="commentary" value="' . htmlentities($commentary_title) . '" onclick = "submit()">' . htmlentities($commentary_title);
	    echo '<br>posted by: ' . htmlspecialchars($user);
	    echo ' on ' . htmlspecialchars($created);
	    echo '<br>Commentary:';
	    echo '<br><textarea rows="6" cols="60">' . htmlentities($commentary) . '</textarea>';
	    echo '<br>';
	}
	echo '</form>';
	$stmt->close();
	
	//redirect to the feedback page
	echo '<br><a href = "feedback.php">' . 'Give us a feedback!' . '</a>';
	?>
</body>
</html>