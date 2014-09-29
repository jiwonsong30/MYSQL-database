<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
</head>
<body>
	<h2>Login</h2>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
		<input type="text" name="user" size="5">
                <input type="password" name="password" size ="10">
		<input type="submit" value="LogIn" />
		<input type="reset" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
	</form>
	
	<?php
	session_start();
	
	//if the username/password is empty
	//prompt user to input user and password
	if(!isset($_POST['user'])||!isset($_POST['password'])){
		echo "Please enter your username and password.";
		echo '<br><br><br><a href = "home.php">'.'Back to the home page'.'</a>';
		echo '<br><a href = "feedback.php">' . 'Give us a feedback!' . '</a>';	
		exit;
	}
	
	//see if the combination of username and password represents a valid user
	//by doing database query
        require 'database.php';
        $username = $mysqli->real_escape_string($_POST['user']);
	$pwd = $_POST['password'];
	require "database.php";
	$query = "SELECT username, password, COUNT(*)
                  FROM users
		  WHERE (username = ?)";
        $stmt = $mysqli->prepare($query);
	if(!$stmt){
		printf("New Account failed: %s\n", $mysqli->error);
		echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
		exit;
	}
	$stmt->bind_param('s', $username);
	
	//compare the password_hash with the result from cyprt(function)
	//if matched, start the session variable	
	if($stmt->execute()){
		$stmt->bind_result($returnedName, $pwd_hash, $count);
		$stmt->fetch();
		if($count==1 && crypt($pwd, $pwd_hash)==$pwd_hash){
			echo htmlentities($username).": You logged in successfully!!";
			$_SESSION['login_user'] = $username;
			$_SESSION['token'] = substr(md5(rand()), 0, 10);
			echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
		}else{
			printf("Unmatched username or password. Try again.");
			echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
			exit;
		}
	}
	$stmt->close();
        
	?>
</body>
</html>