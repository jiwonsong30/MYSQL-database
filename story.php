<!DOCTYPE html>
<?php
session_start();
?>
<html>
 <head>
<title>Story Room </title>
 </head>
<body>
<h2>Story Room</h2>
<?php
if (!isset($_SESSION['login_user'])) {
$commentary_title_read = $_POST['commentary']; 
require 'database.php';
$stmt1 = $mysqli->prepare("select username, article_title, article_link, commentary_title, commentary, stories.created FROM stories
                          WHERE commentary_title LIKE '" . $commentary_title_read . "'");
if (!$stmt1) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt1->execute();
$stmt1->bind_result($user, $article_name, $article_link, $commentary_title, $commentary, $time);
$stmt1->fetch();
    $_SESSION['commentary']      = $commentary;
    $_SESSION['user_commentary'] = $user;
    $_SESSION['commentary_title']    = $commentary_title;
    $follow = "Like this post by $user? Follow $user to get email notifications when $user uploads new posts!";
    echo htmlentities($commentary_title);
    echo '<br> posted by ';
    echo htmlentities($user);
    echo " on ".$time;
    echo '<br>';
    echo '<br>Go to the Original article:';
    echo '<a href = "'. htmlspecialchars($article_link).'">'.htmlspecialchars($article_name).'</a>';
    echo '<br><br>Commentary:';
    echo '<br><textarea rows="6" cols="60">'.htmlentities($commentary).'</textarea>';
    echo '<br>';
    echo '<br>';
$stmt1->close();
}
else {
$login_user = $_SESSION['login_user'];//the user logged in at this page
$commentary_title_read = $_POST['commentary'];
require 'database.php';
$stmt1 = $mysqli->prepare("select username, article_title, article_link, commentary_title, commentary, stories.created FROM stories
                          WHERE commentary_title LIKE '" . $commentary_title_read . "'");
if (!$stmt1) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt1->execute();
$stmt1->bind_result($user, $article_name, $article_link, $commentary_title, $commentary, $time);
echo "\n";
$stmt1->fetch();
    $_SESSION['commentary']      = $commentary;
    $_SESSION['user_commentary'] = $user;
    $_SESSION['commentary_title']    = $commentary_title;
    $follow = "Like this post by $user? Follow $user to get email notifications when $user uploads new posts!";
    echo htmlentities($commentary_title);
    echo '<br> posted by ';
    echo htmlentities($user);
    echo " on ".$time;
    echo '<br>';
    echo '<br>Go to the Original article:';
    echo '<a href = "'. htmlspecialchars($article_link).'">'.htmlspecialchars($article_name).'</a>';
    echo '<br><br>Commentary:';
    echo '<br><textarea rows="6" cols="60">'.htmlentities($commentary).'</textarea>';
    echo '<br>';
    echo '<br>';
$stmt1->close();

//creative portion: If the user hasn't followed the person who posted this commentary already, a button will show up to give the user the option to follow.
$stmt2 = $mysqli->prepare("select followed_by_email from followed where username=? and followed_by=?");
if(!$stmt2){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
} 
$stmt2->bind_param('ss', $user, $login_user);
$stmt2->execute();
$stmt2->store_result();
$stmt2->bind_result($email);
$stmt2->fetch();
$stmt2->close();
if(!isset($email) && ($user != $login_user)) {
 echo '<br><form action="follow.php" method= "POST">'; 
 echo '<td><input type="radio" name="follow" value="' . htmlentities($user) . '" onclick = "submit()"></td>' . htmlentities($follow);
}
}
echo "<br><br><br>Comments:"; //comments
$query = "SELECT stories.username, stories.article_title, stories.article_link, stories.commentary, username_comments, comment, comments.created
          FROM comments
          JOIN stories on (comments.commentary_title = stories.commentary_title AND comments.username_commentary = stories.username)
          WHERE stories.commentary_title LIKE '" . $commentary_title_read . "'";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->execute();
//author here is the one who wrote the commentary
$stmt->bind_result($author, $article_name, $article_link, $commentary, $username_comment, $comment, $created);
$stmt->store_result();
//printf("Number of rows: %d.\n", $stmt->num_rows);
if ($stmt->num_rows == 0) {
    echo '<br>No comments have been made to this post!';
} else {
    $stmt->fetch();
     while ($stmt->fetch()) {
        echo "<br>";
        echo '<br><textarea rows="1" cols="60">'.$comment.'</textarea>';
        echo 'posted by '.$username_comment;
        echo ' on '.$created;
    }
    $stmt->close();
}
if (isset($login_user)) {
 echo '<form action="insert_comment.php" method="POST">
       Please type in your comments here:
       <input type="text" name="comment" size="100">
       <input type="submit" value="Submit" />
       <input type="reset" />
       </form>';
 echo '<br><a href = "home.php">'.'Go back to Home Page'.'</a><br>';
 echo '<a href = "my_account.php">'.'Go to My Account'.'</a>';
}
else {
 echo '<br><br>This website allows only logged in users to write comments. Please <a href = "login.php">' . 'Sign in' . '</a>  or <a href = "insert_user.php">' . 'Register!' . '</a>';
 echo '<br><a href = "home.php">'.'Go back to Home Page'.'</a><br>';
}
?>
</body>
</html>