<?php
session_start();    
session_unset();  
session_destroy();  

// NOW redirect them to the login page
header("Location: login.php");
exit();
?>