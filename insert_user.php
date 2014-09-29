<!DOCTYPE html>
<?php
session_start();
?>
<html>
<head>
	<title>Register Page</title>
</head>
<body>
	<p>Please entry username and password to register.</p>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
		<input type="text" name="user" size="5">
		<input type="text" name="email" size="25">
                <input type="password" name="password" size ="10">
		<input type="submit" value="Submit" />
		<input type="reset" />
		<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
	</form>
	<?php
	
	if(!isset($_POST['user'])||!isset($_POST['password'])||!isset($_POST['email'])){
		echo "Please enter your username, email, and password";
		echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
		exit;
	}
	
	
        require 'database.php';
        $username = $mysqli->real_escape_string($_POST['user']);
	$email = $_POST['email'];
	$pwd = $_POST['password'];
	$encrypted = crypt($pwd);
	
	
	require "database.php";
	
	$stmt1 = $mysqli->prepare("insert into followed (username) values (?)");
	if(!$stmt1){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
	}
 
	$stmt1->bind_param('s', $username);
 
	$stmt1->execute();
 
	$stmt1->close();
	
	
	$query = "INSERT INTO users (username, password, email)
		  VALUES (?,?,?)";
        $stmt = $mysqli->prepare($query);
	if(!$stmt){
		printf("New Account failed: %s\n", $mysqli->error);
		echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
		exit;
	}else if(strcmp($username,"")==0||strcmp($pwd,"")==0||strcmp($email,"")==0){
		echo "Please enter valid username, password, or email";
	}
	
	$stmt->bind_param('sss', $username, $encrypted, $email);
	if($stmt->execute()){
		$stmt->close();
		echo "New account succesfully created :))";
		echo "<br>Please go back to the home page and log in.";
		echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
	}else{
		echo "This username has been taken. Try another one";
		echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
		exit;
	}
	 
        
	?>
</body>
</html>