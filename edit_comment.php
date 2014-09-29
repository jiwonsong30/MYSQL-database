<!DOCTYPE html>
<?php
session_start();
?>
<html>
<head>
	<title>Edit Your Comment Page</title>
</head>
<body>
	<p>Edit your Comment here :)</p>
	<form action="view_comment.php" method="POST">
		<textarea name="newcontent" rows="8" cols="50">
                <?php
$comment = $_SESSION['comment'];
echo htmlentities($comment);//prints out what you modified.
?>
                </textarea>
		<input type="submit" value="Save" />
                <input type="reset" value="clear">
		<input type="hidden" name="token" value="<?php
echo $_SESSION['token'];
?>" />
	</form>
	
</body>
	</html>