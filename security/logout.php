<?php
session_start(); // Start the session so we can kill it

// 1. Unset all session variables (clears the username, etc.)
$_SESSION = array();

// 2. Destroy the session entirely
session_destroy();

// 3. Kick the user back to the login page
header("Location: login.php?status=loggedout");
exit();
?>