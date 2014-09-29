<!DOCTYPE HTML>
<?php
session_start();
?>
<html>
<head>
	<title>MyAccount</title>
</head>
<body>
	<h2>My Account</h2>
<?php
if (!isset($_SESSION['login_user'])) {
    echo "You need to create an acount first";
    echo '<br><br><br><a href = "home.php">' . 'Back to the home page' . '</a>';
    exit;
} else {
    $login_user = $_SESSION['login_user'];
}
require 'database.php';
echo "Welcome " . htmlentities($login_user) . "!  ";
echo '<a href = "logout.php">' . '(Logout)' . '</a>';
echo "<br><br>MyAccout page allows you to upload new posts and edit/delete your posts and comments!<br>";
echo '<br><a href = "home.php">' . 'Back to Home' . '</a>';
echo '<br><br><a href = "upload_form.php">' . 'Upload a post' . '</a>';
//this is for story activities.
$stmt = $mysqli->prepare("select * from stories where stories.username=? ORDER BY created DESC");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('s', $login_user);
$stmt->execute();
$stmt->bind_result($user_commentary, $article_title, $article_link, $commentary_title, $commentary, $created);
echo '<br><br>POST ACTIVIES: Please click on the commentary title, the commentary and the desired action (edit or delete).<br>';
echo '<br><form action="option.php" method= "POST">';
while ($stmt->fetch()) {
    echo '<td><input type="radio" name="commentary_title" value="' . $commentary_title . '"></td>';
    echo ' Commentary Title: ';
    echo htmlentities($commentary_title);
    echo '<br>posted by ' . htmlspecialchars($user_commentary);
    echo ' on ' . htmlspecialchars($created);
    echo '<br><td><input type="radio" name="file" value="' . $commentary . '"></td>';
    echo ' Commentary:';
    echo '<br><textarea rows="6" cols="60">' . htmlentities($commentary) . '</textarea>';
    echo '<table style = "width: 5%"><tr>';
    echo '<td><input type="radio" name="radiobutton" value="edit_story" onclick = submit()>' . 'Edit' . '</td>';
    echo '<td><input type="radio" name="radiobutton" value="delete_story" onclick = submit()>' . 'Delete' . '</td>';
    echo '</tr></table>';
    echo '<br>';
}
echo '</form>';
$stmt->close();




//this is for comments activities
$stmt = $mysqli->prepare("select username_comments, username_commentary, comment, commentary_title, created from comments where comments.username_comments=? ORDER BY created DESC");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('s', $login_user);
$stmt->execute();
$stmt->bind_result($user_comments, $user_post, $comment, $commentary_title, $created);
echo '<br>COMMENT ACTIVIES: Please click on the commentary title, the comment and the desired action (edit or delete).<br>';
echo '<br><form action="option.php" method= "POST">';
while ($stmt->fetch()) {
    echo '<td><input type="radio" name="commentary_title" value="' . $commentary_title . '"></td>';
    echo ' Commentary: ';
    echo htmlentities($commentary_title);
    echo '<td><br><input type="radio" name="comment" value="' . $comment . '"></td>';
    echo ' Comment';
    echo ' posted by ' . htmlspecialchars($user_comments);
    echo ' on ' . htmlspecialchars($created);
    echo '<br><textarea rows="1" cols="60">' . htmlentities($comment) . '</textarea>';
    echo '<table style = "width: 5%"><tr>';
    echo '<td><input type="radio" name="radiobutton" value="edit_comment" onclick = submit()>' . 'Edit' . '</td>';
    echo '<td><input type="radio" name="radiobutton" value="delete_comment" onclick = submit()>' . 'Delete' . '</td>';
    echo '</tr></table>';
    echo '<br>';
}
echo '</form>';
$stmt->close();
?>
</body>
</html>