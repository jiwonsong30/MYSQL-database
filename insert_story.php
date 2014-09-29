<!DOCTYPE HTML>
<?php
session_start();
?>
<html>
<head>
	<title>Upload a Story</title>
</head>
<body>
<?php
require 'database.php';
$login_user       = $_SESSION['login_user']; //session variable created when the user logs in.
//from the upload form
$commentary_title = $_POST['commentarytitle'];
$article_link     = $_POST['link'];
$article_title    = $_POST['articletitle'];
$commentary       = $_POST['commentary'];

//do a query to insert those post variables into the stories table.
$stmt = $mysqli->prepare("insert into stories (username, article_title, article_link, commentary_title, commentary) values (?, ?, ?, ?, ?)");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('sssss', $login_user, $article_title, $article_link, $commentary_title, $commentary);
$stmt->execute();
$stmt->store_result();
if (strcmp($article_title, "") == 0 || strcmp($article_link, "") == 0 || strcmp($commentary_title, "") == 0 || strcmp($commentary, "") == 0) {
    echo "You must not leave the text boxes blank!"; //this checks to see if the user left any blanks in the text field.
} else {
    $stmt->close();
    echo 'Your post ' . htmlentities($commentary_title) . ' has been successfully uploaded!'; //prints out the title of the post
    echo '<br><a href = "my_account.php">' . 'Go to MyAccount' . '</a>'; //back to MyAccout
    echo '<br><a href = "home.php">' . 'Go to home page' . '</a>'; //back to home.
}
//***********creative portion**************
//lets the followers know when the user posted a new commentary.
//query serach to see if anyone is following this user who is posting a new commentary.If the value is not null, then it will send an email to the user following this person.
$stmt1 = $mysqli->prepare("select followed_by, followed_by_email from followed where username=?");
if (!$stmt1) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt1->bind_param('s', $login_user); //the user logged in to post this commentary is the person being followed in this query search.
$stmt1->execute();
$stmt1->bind_result($followed_by, $followed_by_email);
$subject = "News Website Notification"; //title of the email notification
$message = "Dear $followed_by, \n $login_user uploaded a new post: $commentary_title \n Check it out!.\n From the News Website team";
while ($stmt1->fetch()) {
    mail("$followed_by_email", $subject, $message, "From: \n"); //email the followers of this user.
}
;
$stmt1->close();
?>
</body>
</html>