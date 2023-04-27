<!DOCTYPE html>
<html>
	<head>
		<title>Change Password</title>
		<link rel="stylesheet" type="text/css" href="views/style.css" />
	</head>
	<body>
		<h1>Change Password</h1>
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
					

					<!-- Current Password Input -->
					<label for="current_password">Current Password: *</label>
					<input 
						type="password" 
						name="current_password"
						value="<?php echo $input['current_password']; ?>" 
					>					
					<!-- Display error message (if any) -->
					<div class="error"><?php echo $error['current_password']; ?></div>


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

			<!-- Navigate to other pages -->
			<p>
				<a href="members.php">Back to member's area</a> |
				<a href="logout.php">Log Out</a>
			</p>
		</div>
	</body>
</html>