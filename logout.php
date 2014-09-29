<?php
    //destroy session variables and return to the login page
    session_start();
    session_destroy();
    header('Location: home.php');
    exit;
?>
