<!DOCTYPE html>
<?php
session_start();
?>
<html>
<head>
	<title>Edit Your Story Page</title>
</head>
<body>
	<p>Edit your file here :)</p>
	<form action="view_story.php" method="POST">
		<textarea name="newcontent" rows="8" cols="50">
		
                <?php
$commentary = $_SESSION['commentary'];
echo htmlentities($commentary); //prints out what you editted.
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