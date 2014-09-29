<!DOCTYPE HTML>
<?php
session_start();
?>
<html>
<head>
	<title>Follow a user</title>
</head>
<body>
<?php
require 'database.php';

$login_user          = $_SESSION['login_user'];
$user_being_followed = $_POST['follow']; //This is the username of the person being followed.

//query search to get the email of the current login_user.
$stmt1 = $mysqli->prepare("select email from users where username=?");
if (!$stmt1) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt1->bind_param('s', $login_user);
$stmt1->execute();
$stmt1->bind_result($login_user_email);

while ($stmt1->fetch()) {
    $email = $login_user_email; //set that email to a new variable.
}
$stmt1->close();

//query to put the username of the person being followed, the user following that person and his/her email
$stmt = $mysqli->prepare("insert into followed (username, followed_by, followed_by_email) values (?, ?, ?)");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('sss', $user_being_followed, $login_user, $email);
$stmt->execute();
$stmt->close();

echo 'You have successfully followed ';
echo htmlentities($user_being_followed);

echo '<br><a href = "home.php">' . 'Go to home page' . '</a>';
?>
</body>
</html>