<!DOCTYPE html>
<?php
session_start();
?>
<html>
<head>
	<title>Here's your comment</title>
</head> 
<body>
	<?php
		$new_comment_insert = $_POST['newcontent'];
		
		$author = $_SESSION['login_user'];
		$commentary_title = $_SESSION['commentary_title'];
		
		require "database.php";
		$query = "UPDATE comments
			  SET comment = ?
			  WHERE (comments.username_comments = ? AND comments.commentary_title = ?)";
		$stmt = $mysqli->prepare($query);
		if(!$stmt){
			printf("Cannot save the new comment: %s\n", $mysqli->error);
			echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
			exit;
		}else if(strcmp($new_comment_insert,"")==0){
			echo "Please do not leave the comment blank";
		}
		
		$stmt->bind_param('sss', $new_comment_insert, $author, $commentary_title);
		
		if($stmt->execute()){
			$stmt->close();
			echo "You have saved your modifications! :))";
			echo "<br><br>You wrote: <br><br>";
		}else{
			echo "Try again";
			echo '<br><br><a href = "home.php">'.'Back to the home page'.'</a>';
			exit;
		}
		?>
	
	<?php
		echo htmlentities($new_comment_insert);
		session_destroy();
		session_start();
		$_SESSION['login_user'] = $author;
		echo '<br><br><br><a href = "home.php">'.'Back to the home page'.'</a>';
		echo '<br><a href = "feedback.php">' . 'Give us a feedback!' . '</a>';	
	?>
	
</body>
	</html>