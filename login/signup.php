<?php
require('../connect-db.php');
session_start();

$email_error_msg = $pw_error_msg = $email = $pwd = "";
global $db;
$pattern = "/[a-z]{2,3}[1-9][a-z]{1,3}@virginia.edu/i";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (empty($_POST['emailaddr'])) {									// check for input
		$email_error_msg = "Please enter your email address";
	} elseif (!preg_match($pattern, $_POST['emailaddr'])) {				// check that input matches UVA format
		$email_error_msg = "Only UVA email addresses are accepted";
	} else {
		$query = "SELECT user_id FROM user WHERE email = :email";		// check that email isn't associated with existing account
		$statement = $db->prepare($query);
		$statement->bindValue(':email', $_POST['emailaddr']);
		$statement->execute();
		if ($statement->rowCount() == 1) {
			$email_error_msg = "This email is already associated with an account";
		} else {
			$email = $_POST['emailaddr'];
		}
		$statement->closecursor();
	}

	if (empty($_POST['password'])) {									// check for input
		$pw_error_msg = "Please enter a password";
	} elseif ($_POST['password'] != $_POST['password-confirm']) {		// check that passwords match/check for confirm-password input
		$pw_error_msg = "Passwords do not match";
	} else {
		$pwd = $_POST['password'];
		if (empty($email_error_msg) && empty($pw_error_msg)) {			// if no errors, create user account
			$query = "INSERT INTO user (user_id, email, pwd) VALUES (NULL, :email, :pwd)";
			$statement = $db->prepare($query);
			$statement->bindValue(':email', $email);
			$pwd_hash = password_hash($pwd, PASSWORD_BCRYPT);
			$statement->bindValue(':pwd', $pwd_hash);
			$statement->execute();

			$_SESSION['user'] = $_POST['emailaddr'];
			setcookie('user', $email, time() + (86400 * 30), "/");
			header('Location: ../favorites/favorites.php');
			$statement->closecursor();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">   
	<meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
	<meta name="viewport" content="width=device-width, initial-scale=1">  
	<meta name="author" content="Sierra Arnold & Min Suk Kim">
	<title>Sign Up | HoosConvert</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="../style.css">
</head>

<body>
	<header>  
		<nav class="navbar navbar-expand-md bg-light navbar-light">
			<a class="navbar-brand" href="#">HoosConvert</a>

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">   
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="screen2.html">Search</a>
					</li>                                         
					<li class="nav-item">
						<a class="nav-link" href="login.php">Log in</a>
					</li>                       
				</ul>
			</div>  
		</nav>
	</header>

	<div class="container">  
		<h3>Create a HoosConvert Account</h3>
		<form class="signup-form" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
			<div class="input-area">
				<label>Email:</label>
				<input type="email" name="emailaddr" id="emailaddr" class="form-control <?php echo (!empty($email_error_msg)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>"/> <br/>
				<span class="invalid-feedback"><?php echo $email_error_msg; ?></span>
			</div>
			<div class="input-area">
				<label>Password: </label>
				<input type="password" name="password" id="pwd" class="form-control <?php echo (!empty($pw_error_msg)) ? 'is-invalid' : ''; ?>" value="<?php echo $pwd; ?>"/> <br/>
				<span class="invalid-feedback"><?php echo $pw_error_msg; ?></span>
			</div>
			<div class="input-area">
				<label>Confirm Password: </label>
				<input type="password" name="password-confirm" id="pwd-confirm"></input> <br/>
			</div>

			<input type="submit" value="Sign up" class="btn btn-secondary" />
			<p class="signup">Already have an account?<br/>
				<a href="login.php">Log in</a> here</p> <br/>
			</form>
		</div>

	<!-- unused JS error messages -->
	<!-- <script type="text/javascript">
		function checkPattern(str)
		{
			var email_format = new RegExp("[a-z]{2,3}[1-9][a-z]{1,3}@virginia.edu");
			var check = email_format.test(str);
			return check;
		}

		function validateSignup()
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
			var pwd_2 = document.getElementById("pwd-confirm").value;			
			if (pwd == "") {
				alert ("Please enter a password");
				return false;
			}
			if (pwd.length < 8) {
				alert ("Please use a password that is at least 8 characters");
				return false;
			}
			if (pwd != pwd_2) {
				alert("Passwords do not match");
				return false;
			}
			else
				return true;
		}
	</script> -->

	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>
</html>
