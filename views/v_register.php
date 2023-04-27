<!DOCTYPE html>
<html>
	<head>
		<title>Register Member</title>
		<link rel="stylesheet" type="text/css" href="views/style.css" />
	</head>
	<body>
		<h1>Register Member</h1>
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
					
					<!-- Username Input -->
					<label for="username">Username: *</label>
					<input 
						type="text" 
						name="username"
						value="<?php echo $input['user']; ?>" 
					>
					
					<!-- Display error message (if any) -->
					<div class="error"><?php echo $error['user']; ?></div>

					<!-- Password Input -->
					<label for="password">Password: *</label>
					<input 
						type="password" 
						name="password"
						value="<?php echo $input['pass']; ?>" 
					>
					
					<!-- Display error message (if any) -->
					<div class="error"><?php echo $error['pass']; ?></div>

					<!-- Confirm Password Input -->
					<label for="password2">Confirm Password: *</label>
					<input 
						type="password" 
						name="password2"
						value="<?php echo $input['pass2']; ?>" 
					>

					<!-- Display error message (if any) -->
					<div class="error"><?php echo $error['pass2']; ?></div>

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