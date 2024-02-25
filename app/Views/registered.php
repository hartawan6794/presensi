<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<div class="container">
		<h2>Register</h2>
		<form action="/submit_registration" method="post">
			<label for="fname">First Name:</label><br>
			<input type="text" id="fname" name="fname"><br>
			<label for="lname">Last Name:</label><br>
			<input type="text" id="lname" name="lname"><br>
			<label for="email">Email:</label><br>
			<input type="email" id="email" name="email"><br>
			<label for="password">Password:</label><br>
			<input type="password" id="password" name="password"><br>
			<input type="submit" value="Register">
		</form>
	</div>
</body>
</html>