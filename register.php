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
$error['email'] = '';
$error['pass'] = '';
$error['pass2'] = '';
$input['user'] = '';
$input['email'] = '';
$input['pass'] = '';
$input['pass2'] = '';

// check if submit btn was clicked
if (isset($_POST['submit'])) 
{
	// process form
	if ($_POST['username'] == '' || $_POST['email'] == '' || $_POST['password'] == '' || $_POST['password2'] == '') 
	{
		// all fields need to be filled in
		if ($_POST['username'] == '') 
		{
			$error['user'] = 'required!';
		}

		if ($_POST['email'] == '') 
		{
			$error['email'] = 'required!';
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
		$input['email'] = htmlentities($_POST['email'], ENT_QUOTES);
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
		$input['email'] = htmlentities($_POST['email'], ENT_QUOTES);
		$input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
		$input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);

		// show register page
		include 'views/v_register.php';
	}
	// email validation using RegExp -- NOT WORKING PROPERLY DONT KNOW WHY
	// else if (!preg_match('/^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$/', $input['email']))
	// {
	// 	echo "here";
	// 	// email is invalid
	// 	$error['alert'] = "Email is invalid";

	// 	// store user's input (make sure it's clean by using htmlentities)
	// 	// -> prevent SQL injection / malicious code
	// 	$input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
	// 	$input['email'] = htmlentities($_POST['email'], ENT_QUOTES);
	// 	$input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
	// 	$input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);

	// 	// show register page
	// 	include 'views/v_register.php';
	// }
	else 
	{
		// store user's input using htmlentities
		// -> prevent SQL injection / malicious code
		$input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
		$input['email'] = htmlentities($_POST['email'], ENT_QUOTES);
		$input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);


		// check if email is taken
		$check = $mysqli->prepare("SELECT email FROM members WHERE email = ? ");
		$check->bind_param("s", $input['email']);
		$check->execute();
		$check->store_result();
		if ($check->num_rows != 0)
		{
			// email is already taken
			$error['alert'] = "This email is already in use. Please choose a different email address.";
			$error['email'] = "Email already in use.";

			// show register page
			include 'views/v_register.php';
			exit;
		}

		// check if username is taken
		$check = $mysqli->prepare("SELECT username FROM members WHERE username = ? ");
		$check->bind_param("s", $input['user']);
		$check->execute();
		$check->store_result();
		if ($check->num_rows != 0)
		{
			// username is already taken
			$error['alert'] = "This username is already taken. Please choose a different username.";
			$error['user'] = "Username taken.";

			// show register page
			include 'views/v_register.php';
			exit;
		}



		// insert into database
		$stmt = $mysqli->prepare("INSERT members (username, email, password) VALUES(?, ?, ?)");

		// check if SQL query valid
		if ($stmt)
		{
			// Execute SQL command
			$stmt->bind_param("sss", $input['user'], $input['email'], md5($input['pass'] . $config['salt']));
			$stmt->execute();
			$stmt->close();

			// Store feedback and reset form input fields
			$error['alert'] = 'Member added successfully!';
			$input['user'] = '';
			$input['email'] = '';
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