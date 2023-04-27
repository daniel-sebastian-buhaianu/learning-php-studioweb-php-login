<?php  

/*
 * LOGOUT.PHP
 * Log out member
*/

// start session
session_start();
session_destroy();

// show logged out page
include 'views/v_loggedout.php';

// redirect to login page after 3s
header("refresh:3;url=login.php");

?>