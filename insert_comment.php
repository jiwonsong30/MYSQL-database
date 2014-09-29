<!DOCTYPE html>
<?php
session_start();
?>
<html>
 <head>
<title>Story Room </title>
 </head>
<body>
<?php
require 'database.php';
$login_user       = $_SESSION['login_user'];
$commentary       = $_SESSION['commentary'];
$author           = $_SESSION['user_commentary'];
$commentary_title = $_SESSION['commentary_title'];

$comment = $_POST['comment'];

$stmt = $mysqli->prepare("insert into comments (username_comments, username_commentary, comment, commentary_title) values (?, ?, ?, ?)");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('ssss', $login_user, $author, $comment, $commentary_title);

$stmt->execute();

$stmt->close();

//*****creative portion************
//lets the person who posted this commentary know when other people commented on it.
$subject = "News Website Notification"; //subject of the email.
//content of the email saying that another user has commented on his/her post.
$message = "Dear $author, \n $login_user posted a comment on your post: $commentary_title \n Check it out!.\n From the News Website team";

//query to get the email address of the post author.
$stmt1 = $mysqli->prepare("select email from users WHERE username LIKE '" . $author . "' ORDER BY email ");
if (!$stmt1) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt1->execute();
$stmt1->bind_result($email_author);

while ($stmt1->fetch()) {
    mail("$email_author", $subject, $message, "From: \n"); //once the email of the user is queried, you can send the email.
    
}
$stmt1->close();
echo 'Your comment has been successfully upload!';
echo '<br><a href = "home.php">' . 'Go back to Home Page' . '</a><br>';
?>
</body>
</html>
