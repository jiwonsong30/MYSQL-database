<!DOCTYPE html>
<?php
session_start();
?>
<html>
<head>
	<title>Here's your post</title>
</head>
<body>
	<?php
		$new_story_insert = $_POST['newcontent'];
		
		$author = $_SESSION['login_user'];
		$commentary_title = $_SESSION['commentary_title'];
		$_SESSION['commentary'] = $new_story_insert;
		
		require "database.php";
		$query = "UPDATE stories
			  SET commentary = ?
			  WHERE (stories.username = ? AND stories.commentary_title = ?)";
		$stmt = $mysqli->prepare($query);
		if(!$stmt){
			printf("Cannot save the new commentary: %s\n", $mysqli->error);
			echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
			exit;
		}else if(strcmp($new_story_insert,"")==0){
			echo "Please do not leave the commentary blank";
		}
		
		$stmt->bind_param('sss', $new_story_insert, $author, $commentary_title);
		
		if($stmt->execute()){
			$stmt->close();
			echo "You have saved your modifications! :))";
			echo "<br><br>You wrote: <br><br>";
		}else{
			echo "This . Try again";
			echo '<br><br><a href = "home.php">'.'Back to the home page'.'</a>';
			exit;
		}
		?>
	
	<?php
		echo htmlentities($new_story_insert);
		session_destroy();
		session_start();
		$_SESSION['login_user'] = $author;
		echo '<br><br><br><a href = "home.php">'.'Back to the home page'.'</a>';
		echo '<br><a href = "feedback.php">' . 'Give us a feedback!' . '</a>';	
	?>
	
</body>
	</html>