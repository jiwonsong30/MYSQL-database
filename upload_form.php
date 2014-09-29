<!DOCTYPE HTML>
<?php
session_start();
?>
<html>
<head>
	<title>New Post Form</title>
</head>
<body>
<?php
$login_user = $_SESSION['login_user'];
?>
<form action="insert_story.php" method="POST">
	<table> 
	<tr>
	<td>Title:</td> 
        <td><input type="text" name="commentarytitle" size="50"></td>
	</tr>
	<tr>
	<td>Article title:</td>
	<td><input type="text" name="articletitle" size="50"></td>
	</tr>
	<tr>
	<td>Article link:</td>
	<td><input type="text" name="link" size="50"></td>
	</tr>
	<tr>
	<td>Commentary:</td>
	<td><input type="text" name="commentary" size="100" style="height:100px"></td>
	</tr>
	</table>
	<input type="submit" value="Submit" />
	<input type="reset" />
	<input type="hidden" name="token" value="<?php
echo $_SESSION['token'];
?>" />
</form>
</body>
</html>
