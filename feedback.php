<!DOCTYPE html>
<?php
session_start();

?>
<html>
<head>
	<title>Feedback</title>
</head>
<body>
<h2>Feedback Form</h2>
<?php
if (!isset($_POST["submit"])) {
?>
<form method="post" action="<?php
    echo $_SERVER["PHP_SELF"];
?>">
<table> 
<tr>
<td>Email:</td> 
<td><input type="text" name="from"></td>
</tr> 
<tr> 
<td>Title:</td>
<td><input type="text" name="title" ></td>
</tr> 
<tr> 
<td>Feedback: </td>
<td><textarea rows="10" cols="50" name="message"></textarea></td>
</tr> 
</table>
<input type="submit" name="submit" value="Submit Feedback">
</form>
  <?php
} else { // the user has submitted the form
    if (isset($_POST["from"])) {
        $subject = "Thank you for submitting your feedback"; //the subject for the notification email.
        $content = "You have submitted a feedback about the News Website. We appreciate your time and will listen to your comments."; //the content for email notification.
        $email   = $_POST["from"]; // sender
        $title   = $_POST["title"]; //the title sender wrote
        $message = $_POST["message"]; //the feedback the sender gave.
        $message = wordwrap($message, 70);
        //send email to the administrator
        mail("msjw9353@gmail.com", $title, $message, "\n");
        //send notification to the sender
        mail("$email", $subject, $content, "\n");
        echo "Thank you for sending us feedback";
        echo '<br><a href = "home.php">' . 'Back to the home page' . '</a>';
    }
}
?>