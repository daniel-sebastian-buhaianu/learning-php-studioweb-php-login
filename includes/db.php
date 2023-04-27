<?php

/* DB.PHP
 * Database Settings & Connection
*/


// database settings
$server = 'localhost';
$user = 'dsb99@localhost';
$pass = 'abc123000';
$db = 'login';

// connect to the database
$mysqli = new mysqli($server, $user, $pass, $db);

?>