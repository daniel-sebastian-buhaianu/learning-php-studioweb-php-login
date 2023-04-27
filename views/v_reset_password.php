<!DOCTYPE html>
<html>
	<head>
		<title>Reset Password</title>
		<link rel="stylesheet" type="text/css" href="views/style.css" />
	</head>
	<body>
		<h1>Reset Password</h1>
		<div id="content">
			<form action="" method="post">
				<div>

					<!-- Display error message (if any) -->
					<?php
						if ($error['alert'] != '')
						{
							echo "<div class='alert'>".$error['alert']."</div>";
						}
					?>


					<!-- Page Info -->
					<p> Now you can reset your password below:</p>


					<!-- New Password Input -->
					<label for="new_password">New Password: *</label>
					<input 
						type="password" 
						name="new_password"
						value="<?php echo $input['new_password']; ?>" 
					>
					<!-- Display error message (if any) -->
					<div class="error"><?php echo $error['new_password']; ?></div>


					<!-- Confirm New Password Input -->
					<label for="confirm_new_password">Confirm New Password: *</label>
					<input 
						type="password" 
						name="confirm_new_password"
						value="<?php echo $input['confirm_new_password']; ?>" 
					>
					<!-- Display error message (if any) -->
					<div class="error"><?php echo $error['confirm_new_password']; ?></div>




					<!-- Display required inputs -->
					<p class="required">* required fields</p>

					<!-- Submit button !-->
					<input type="submit" name="submit" class="submit" value="Submit">
				</div>
			</form>
	</body>
</html>