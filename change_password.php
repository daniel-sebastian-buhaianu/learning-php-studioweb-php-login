<?php 


/* 
 * CHANGE_PASSWORD.PHP
 * Changes user's password
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
$error['current_password'] = '';
$error['new_password'] = '';
$error['confirm_new_password'] = '';
$input['current_password'] = '';
$input['new_password'] = '';
$input['confirm_new_password'] = '';

// check if submit btn was clicked
if (isset($_POST['submit'])) 
{
	// process form
	if ($_POST['current_password'] == '' || $_POST['new_password'] == '' || $_POST['confirm_new_password'] == '') 
	{
		// all fields need to be filled in
		if ($_POST['current_password'] == '') 
		{
			$error['current_password'] = 'required!';
		}

		if ($_POST['new_password'] == '') 
		{
			$error['new_password'] = 'required!';
		}

		if ($_POST['confirm_new_password'] == '') 
		{
			$error['confirm_new_password'] = 'required!';
		}

		// set error message
		$error['alert'] = 'Please fill in the required fields!';

		// store user's input (make sure it's clean by using htmlentities)
		// -> prevent SQL injection / malicious code
		$input['current_password'] = htmlentities($_POST['current_password'], ENT_QUOTES);
		$input['new_password'] = htmlentities($_POST['new_password'], ENT_QUOTES);
		$input['confirm_new_password'] = htmlentities($_POST['confirm_new_password'], ENT_QUOTES);

		// show change password page
		include 'views/v_change_password.php';

	}
	else if ($_POST['new_password'] != $_POST['confirm_new_password'])
	{ 	
		// both password fields must match
		$error['alert'] = "New Password must match Confirm New Password";

		// store user's input (make sure it's clean by using htmlentities)
		// -> prevent SQL injection / malicious code
		$input['current_password'] = htmlentities($_POST['current_password'], ENT_QUOTES);
		$input['new_password'] = htmlentities($_POST['new_password'], ENT_QUOTES);
		$input['confirm_new_password'] = htmlentities($_POST['confirm_new_password'], ENT_QUOTES);

		// show change password page
		include 'views/v_change_password.php';
	} 
	else 
	{
		// store user's input (make sure it's clean by using htmlentities)
		// -> prevent SQL injection / malicious code
		$input['current_password'] = htmlentities($_POST['current_password'], ENT_QUOTES);
		$input['new_password'] = htmlentities($_POST['new_password'], ENT_QUOTES);
		$input['confirm_new_password'] = htmlentities($_POST['confirm_new_password'], ENT_QUOTES);


		// query database
		$check = $mysqli->prepare("SELECT password FROM members WHERE id = ?");

		// check if SQL query valid and get password hash from db
		if ($check)
		{
			// Execute SQL command
			$check->bind_param("s", $_SESSION['id']);
			$check->execute();
			$check->bind_result($current_password);
			$check->fetch();
			$check->close();
		}
		else
		{
			echo "ERROR: Could not prepare MySQLi statement.";
		}

		if (md5($input['current_password'] . $config['salt']) != $current_password)
		{
			// current_password input must match user's password 
			$error['alert'] = 'Current Password is incorrect.';
			$error['current_password'] = 'incorrect';

			// show change password page
			include 'views/v_change_password.php';
		}
		else
		{
			// query db
			$stmt = $mysqli->prepare("UPDATE members SET password = ? WHERE id = ?");

			// if query is successful
			if ($stmt)
			{
				// Execute SQL command
				$stmt->bind_param("si", md5($input['new_password'] . $config['salt']), $_SESSION['id']);
				$stmt->execute();
				$stmt->close();
			}
			else
			{
				echo "ERROR: Could not prepare MySQLi statement.";
			}
		}

			// Store feedback and reset form input fields
			$error['alert'] = 'Password has been changed successfully!';
			$input['current_password'] = '';
			$input['new_password'] = '';
			$input['confirm_new_password'] = '';

			// Show change password page
			include 'views/v_change_password.php';
	}
} 
else 
{
	// Show change password page
	include 'views/v_change_password.php';	
}

// close database connection
$mysqli->close();

?>