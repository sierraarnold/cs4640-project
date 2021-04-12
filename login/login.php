<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">   
	<meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Sierra Arnold & Min Suk Kim">
	<title>Login to HoosConvert</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="../style.css">
</head>

<body>

	<?php session_start(); ?>

	<header>  
		<?php include('navbar.html'); ?>
	</header>

	<?php require('connect-db.php'); ?>

	<div class="container">  
		<h3>Log In to HoosConvert</h3>
		<form class="login-form" onsubmit="return validateLogin()" action="<?php $_SERVER['PHP_SELF']?>" method="post">
			<div class="input-area">
				<label>Email:</label>
				<input type="email" name="email" id="emailaddr" autofocus required/> <br/>
			</div>
			<div class="input-area">
				<label>Password: </label>
				<input type="password" name="password" id="pwd" required></input> <br/>
			</div>

			<input type="submit" value="Login" class="btn btn-secondary" />
			<p class="signup">Don't have an account?<br/><a href="signup.html">Sign up</a> to get access to<br/>
			more conversion tools!</p> <br/>
		</form>
	</div>

	<script type="text/javascript">
		function checkPattern(str)
		{
			var email_format = new RegExp("[a-z]{2,3}[1-9][a-z]{1,3}@virginia.edu");
			var check = email_format.test(str);
			return check;
		}

		function validateLogin()
		{   
			var email = document.getElementById("emailaddr").value;
			if (email == "") {
				alert("Please enter your email");
				return false;
			}
			if (!checkPattern(email)) {
				alert("Please use a UVA email");
				return false;
			}
			var pwd = document.getElementById("pwd").value;
			if (pwd == "") {
				alert ("Please enter your password");
				return false;
			}
			/*if (pwd != "password") {
				alert("Incorrect password");
				return false;
			}*/
			else
				return true;
		}
	</script>

	<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	  $_SESSION['user'] = $_POST['email'];
	  $_SESSION['pwd'] = $_POST['password'];
	  header('Location: favorites.php');
	}
	?>

	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>
</html>