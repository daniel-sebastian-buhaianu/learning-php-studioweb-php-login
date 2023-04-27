<?php

/*
 * CONFIG.PHP
 * Configuration Setting
*/

// user authentication
$config['salt'] = 'jK7d?3';
$config['session_timeout'] = 1 * 60; // x * 60s = x minutes


// domain
$config['site_name'] = 'PHP-Login-System';
$config['site_url'] = "https://www.dsb99.app";
$config['site_domain'] = "dsb99.app";

// error reporting
mysqli_report(MYSQLI_REPORT_ERROR);

?>