<?php 


/* 
 * LOGIN.PHP
 * Log in members
*/

// start session / load configs
session_start();
include 'includes/config.php';
include 'includes/db.php';

// form defaults
$error['alert'] = '';
$error['user'] = '';
$error['pass'] = '';
$input['user'] = '';
$input['pass'] = '';

// check if submit btn was clicked
if (isset($_POST['submit'])) 
{
	// process form
	if ($_POST['username'] == '' || $_POST['password'] == '') 
	{
		// both fields need to be filled in
		if ($_POST['username'] == '') 
		{
			$error['user'] = 'required!';
		}

		if ($_POST['password'] == '') 
		{
			$error['pass'] = 'required!';
		}

		// set error message
		$error['alert'] = 'Please fill in the required fields!';

		// store user's input (make sure it's clean by using htmlentities)
		// -> prevent SQL injection / malicious code
		$input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
		$input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);

		// show login page
		include 'views/v_login.php';

	} 
	else 
	{
		// store user's input (make sure it's clean by using htmlentities)
		// -> prevent SQL injection / malicious code
		$input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
		$input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);

		// create SQL query
		$stmt = $mysqli->prepare("SELECT id FROM members WHERE username = ? AND password = ?");

		// check if SQL query has been successful
		if ($stmt)
		{
			$stmt->bind_param("ss", $input['user'], md5($input['pass'] . $config['salt']));
			$stmt->execute();
			$stmt->bind_result($id);
			$stmt->fetch();

			// check if there's a match
			if ($id)
			{
				//set session variable
				$_SESSION['id'] = $id;
				$_SESSION['username'] = $input['user'];
				$_SESSION['last_active'] = time();

				// redirect to members.php
				header("Location: members.php");
			} 
			else 
			{
				// username/password is incorrect
				$error['alert'] = "Username or password is incorrect";

				// show login page
				include 'views/v_login.php';
			}
		}
		else
		{
			// print error if SQL query failed
			echo "ERROR: Could not prepare MySQLi statement.";
		}
	}
} 
else 
{
	// Check if user tried to access members only area
	if (isset($_GET['unauthorized'])) 
	{
		// Provide feedback to user
		$error['alert'] = 'Please login to view that page';
	}

	// Check if session has timed out
	if (isset($_GET['timeout']))
	{
		// Provide feedback to user
		$error['alert'] = 'Your session has expired. Please log in again.';
	}

	// show login page
	include 'views/v_login.php';	
}

// close database connection
$mysqli->close();
	
?>