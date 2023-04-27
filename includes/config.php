<?php

/*
 * CONFIG.PHP
 * Configuration Setting
*/

// user authentication
$config['salt'] = 'jK7d?3';
$config['session_timeout'] = 1 * 60; // x * 60s = x minutes

// error reporting
mysqli_report(MYSQLI_REPORT_ERROR);

?>