<?php 


/* 
 * RESET_PASSWORD.PHP
 * Helps users reset their password if they forgot it
*/

// start session / load configs
session_start();
include 'includes/config.php';
include 'includes/db.php';


// form defaults
$error['alert'] = '';
$error['email'] = '';
$error['new_password'] = '';
$error['confirm_new_password'] = '';
$input['email'] = '';
$input['new_password'] = '';
$input['confirm_new_password'] = '';

if (isset($_GET['key']))
{
	/*
		User entering a new password
	*/
	
	// check if submit btn was clicked
	if (isset($_POST['submit'])) 
	{
		// process form
		$input['new_password'] = htmlentities($_POST['new_password'], ENT_QUOTES);
		$input['confirm_new_password'] = htmlentities($_POST['confirm_new_password'], ENT_QUOTES);

		if ($_POST['new_password'] == '' || $_POST['confirm_new_password'] == '') 
		{
			// both fields must be filled in
			$error['new_password'] = 'required!';
			$error['confirm_new_password'] = 'required';
			$error['alert'] = 'Please fill in required fields!';

			// show reset password page
			include 'views/v_reset_password.php';
		}
		else if ($input['new_password'] != $input['confirm_new_password'])
		{
			// passwords must match
			$error['alert'] = 'Passwords must be the same!';

			// show reset password page
			include 'views/v_reset_password.php';
		}
		else
		{
			// change password

			// store user's input (make sure it's clean by using htmlentities)
			// -> prevent SQL injection / malicious code
			$input['new_password'] = htmlentities($_POST['new_password'], ENT_QUOTES);
			$input['confirm_new_password'] = htmlentities($_POST['confirm_new_password'], ENT_QUOTES);

			// query db
			$stmt = $mysqli->prepare("UPDATE members SET password = ? WHERE pw_reset = ?");

			// if query is successful
			if ($stmt)
			{
				// Execute SQL command
				$stmt->bind_param("ss", md5($input['new_password'] . $config['salt']), $_GET['key']);
				$stmt->execute();
				$stmt->close();

				// Store feedback and reset form input fields
				$error['alert'] = 'Password has been changed successfully!';
				$input['new_password'] = '';
				$input['confirm_new_password'] = '';

				// Show reset password page
				include 'views/v_reset_password.php';
			}
			else
			{
				echo "ERROR: Could not prepare MySQLi statement.";
			}
		}
	}
}
else
{
	/*
		User requesting a password reset
	*/

	// check if submit btn was clicked
	if (isset($_POST['submit'])) 
	{
		// process form
		$input['email'] = htmlentities($_POST['email'], ENT_QUOTES);

		if ($_POST['email'] == '') 
		{
			// email is blank
			$error['email'] = 'required!';
			$error['alert'] = 'Please fill in required fields!';

			// show forgot password page
			include 'views/v_forgot_password.php';
		}
		else
		{
			$email_validation_regex = "/^[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/"; 
			
			if (!preg_match($email_validation_regex, $input['email']))
			{
				// email is invalid
				$error['email'] = 'invalid';
				$error['alert'] = 'Please enter a valid email.';

				// show forgot password page
				include 'views/v_forgot_password.php';
			}
			else
			{
				// check that email exists in the database
				$check = $mysqli->prepare("SELECT email FROM members WHERE email = ?");
				$check->bind_param("s", $input['email']);
				$check->execute();
				$check->store_result();
				if ($check->num_rows == 0)
				{
					// close connection
					$check->close();

					// email is not in database
					$error['alert'] = "This email doesn't exist. Please check for typos.";

					// show forgot password page
					include 'views/v_forgot_password.php';
				}
				else
				{
					// close connection
					$check->close();

					// create key
					$key = generate_random_string(16);

					// create email
					$subject = 'Password reset request from ' . $config['site_name'];

					$message = '<html><body>';
					$message .= '<p>Hello,</p';
					$message .= '<p>You (or someone claiming to be you) recently asked that your '
								. $config['site_name'] . ' password to be reset.';
					$message .= 'If so, please click the link below to reset your password.';
					$message .= 'If you don\'t want to reset your password, or if the request was in error, ';
					$message .= 'please ignore this message.</p>';
					$message .= '<p><a href=\'' . $config['site_url'] . '/reset_password.php?key=' . $key . '\'>';
					$message .= 'Reset your password</a></p><br><br>';
					$message .= '<p>Kind regards,</p>';
					$message .= '<p>The Support Team</p></body></html>';

					// create email headers
					$headers = 'MIME-VERSION: 1.0' . "\r\n";
					$headers .= 'Content-Type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: ' . $config['site_name'];
					$headers .= ' <noreply@' . $config['site_domain'] . '>' . "\r\n";
					$headers .= 'X-Sender: <noreply@' . $config['site_domain'] . '>' . "\r\n";
					$headers .= 'Reply-To: <noreply@' . $config['site_domain'] . '>' . "\r\n";

					// send email
					mail($input['email'], $subject, $message, $headers);

					// update database
					$stmt = $mysqli->prepare("UPDATE members SET pw_reset = ? WHERE email = ?");
					$stmt->bind_param("ss", $key, $input['email']);
					$stmt->execute();
					$stmt->close();

					// add alert and clear form values
					$error['alert'] = 'Reset password link has been sent. Please check your email.';
					$input['email'] = '';
					include 'views/v_forgot_password.php';

				}
			}
		}
	}
	else
	{
		// show forgot password page
		include 'views/v_forgot_password.php';
	}
}


// close database connection
$mysqli->close();


function generate_random_string($length) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string = '';

	for ($p = 0; $p < length; $p++)
	{
		$string .= $characters[mt_rand(0, strlen($characters) - 1)];
	}

	return $string;
}

?>