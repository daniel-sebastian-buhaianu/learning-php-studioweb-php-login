<!DOCTYPE html>
<html>
	<head>
		<title>Log In</title>
		<link rel="stylesheet" type="text/css" href="views/style.css" />
	</head>
	<body>
		<h1>Log In</h1>
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

					
					<!-- Display required inputs -->
					<p class="required">* required fields</p>

					<!-- Submit button !-->
					<input type="submit" name="submit" class="submit" value="Submit">
				</div>
			</form>
		</div>
	</body>
</html>