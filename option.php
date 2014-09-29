<?php
session_start();
$option = $_POST['radiobutton'];
require "database.php";
    if(strcmp($option, "edit_story")==0){
        $_SESSION['commentary'] = $_POST['file'];
	$_SESSION['commentary_title'] = $_POST['commentary_title'];
        header("Location: edit_story.php");
        exit;
    }elseif(strcmp($option, "delete_story")==0){
        $commentary = $_POST['file'];
        $commentary_title = $_POST['commentary_title'];
        $author = $_SESSION['login_user'];
        $query = "DELETE FROM comments
		  WHERE commentary_title = ?";
        $stmt = $mysqli->prepare($query);
        if(!$stmt){
                printf("Cannot Delete this Commentary: %s\n", $mysqli->error);
                echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
                exit;
        } 
        $stmt->bind_param('s', $commentary_title);
        $stmt->execute();
        $stmt->close();
        
        $query1 = "DELETE FROM stories
		  WHERE commentary_title = ?";
        $stmt1 = $mysqli->prepare($query1);
        if(!$stmt1){
                printf("Cannot Delete this Commentary: %s\n", $mysqli->error);
                echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
                exit;
        } 
        $stmt1->bind_param('s', $commentary_title);
        $stmt1->execute();
        $stmt1->close();
        
        echo "You have deleted this commentary! :))";
        session_destroy();
	session_start();
	$_SESSION['login_user']=$author;
	echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
        exit;
    }elseif(strcmp($option, "edit_comment")==0){
        $_SESSION['comment'] = $_POST['comment'];
        $author = $_SESSION['login_user'];
        $_SESSION['commentary_title'] = $_POST['commentary_title'];
        header("Location: edit_comment.php");
         exit;
    }elseif(strcmp($option, "delete_comment")==0){
        
        $comment = $_POST['comment'];
        $author = $_SESSION['login_user'];
        $commentary_title = $_POST['commentary_title'];
        $query = "DELETE FROM comments
		  WHERE (comment = ? AND username_comments = ? AND commentary_title = ? )";
        $stmt = $mysqli->prepare($query);
        if(!$stmt){
                printf("Cannot Delete this Commentary: %s\n", $mysqli->error);
                echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
                exit;
        } 
        $stmt->bind_param('sss', $comment, $author, $commentary_title);
        $stmt->execute();
        $stmt->close();
        
        echo "Your comment has been deleted";
        session_destroy();
	session_start();
	$_SESSION['login_user']=$author;
	
	echo '<br><a href = "home.php">'.'Back to the home page'.'</a>';
        exit;
    }
?>