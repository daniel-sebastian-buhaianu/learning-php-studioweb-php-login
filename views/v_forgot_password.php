<!DOCTYPE html>
<html>
	<head>
		<title>Forgot Password</title>
		<link rel="stylesheet" type="text/css" href="views/style.css" />
	</head>
	<body>
		<h1>Forgot Password</h1>
		<div id="content">
			<form action="" method="post">
				<div>
					
					<!-- Page Info -->
					<h3>Forgot your password?</h3>
					<p>Enter your email below, and we'll email you a link to reset your password.</p>


					<!-- Display error message (if any) -->
					<?php
						if ($error['alert'] != '')
						{
							echo "<div class='alert'>".$error['alert']."</div>";
						}
					?>
					

					<!-- Email Input -->
					<label for="email">Email: *</label>
					<input 
						type="email" 
						name="email"
						value="<?php echo $input['email']; ?>" 
					>					
					<!-- Display error message (if any) -->
					<div class="error"><?php echo $error['email']; ?></div>



					<!-- Display required inputs -->
					<p class="required">* required fields</p>

					<!-- Submit button !-->
					<input type="submit" name="submit" class="submit" value="Submit">
				</div>
			</form>
		</div>
	</body>
</html>