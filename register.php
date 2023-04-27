<?php 


/* 
 * REGISTER.PHP
 * Register new members
*/

// start session / load configs
session_start();
include 'includes/config.php';
include 'includes/db.php';

// check that the user is logged in
if (!isset($_SESSION['username'])) 
{
	header("Location: login.php");
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

// form defaults
$error['alert'] = '';
$error['user'] = '';
$error['pass'] = '';
$error['pass2'] = '';
$input['user'] = '';
$input['pass'] = '';
$input['pass2'] = '';

// check if submit btn was clicked
if (isset($_POST['submit'])) 
{
	// process form
	if ($_POST['username'] == '' || $_POST['password'] == '' || $_POST['password2'] == '') 
	{
		// all fields need to be filled in
		if ($_POST['username'] == '') 
		{
			$error['user'] = 'required!';
		}

		if ($_POST['password'] == '') 
		{
			$error['pass'] = 'required!';
		}

		if ($_POST['password2'] == '') 
		{
			$error['pass2'] = 'required!';
		}

		// set error message
		$error['alert'] = 'Please fill in the required fields!';

		// store user's input (make sure it's clean by using htmlentities)
		// -> prevent SQL injection / malicious code
		$input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
		$input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
		$input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);

		// show register page
		include 'views/v_register.php';

	}
	else if ($_POST['password'] != $_POST['password2'])
	{ 	
		// both password fields must match
		$error['alert'] = "Passwords don't match";

		// store user's input (make sure it's clean by using htmlentities)
		// -> prevent SQL injection / malicious code
		$input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
		$input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
		$input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);

		// show register page
		include 'views/v_register.php';
	} 
	else 
	{
		// store user's input using htmlentities
		// -> prevent SQL injection / malicious code
		$input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
		$input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);

		// insert into database
		$stmt = $mysqli->prepare("INSERT members (username, password) VALUES(?, ?)");

		// check if SQL query valid
		if ($stmt)
		{
			// Execute SQL command
			$stmt->bind_param("ss", $input['user'], md5($input['pass'] . $config['salt']));
			$stmt->execute();
			$stmt->close();

			// Store feedback and reset form input fields
			$error['alert'] = 'Member added successfully!';
			$input['user'] = '';
			$input['pass'] = '';
			$input['pass2'] = '';

			// Show register page
			include 'views/v_register.php';

		}
		// SQL query invalid
		else
		{
			echo "ERROR: Could not prepare MySQLi statement.";
		}
	}
} 
else 
{
	// Show register page
	include 'views/v_register.php';	
}

// close database connection
$mysqli->close();

?>