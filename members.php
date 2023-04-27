<?php  

/*
 * MEMBERS.PHP
 * Password protected area for members only
*/

// start session
session_start();

// include config.php
include 'includes/config.php';

// check that the user is logged in
if (!isset($_SESSION['username'])) 
{
    // redirect to login.php
	header("Location: login.php?unauthorized");
}

// check for inactivity
if (time() > $_SESSION['last_active'] + $config['session_timeout'])
{
    // log out user
    session_destroy();
    header("Location: login.php?timeout");
}
else
{
    $_SESSION['last_active'] = time();
}

// show members only page
include 'views/v_members.php';

?>