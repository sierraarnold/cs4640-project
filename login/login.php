<?php
require('../connect-db.php');
session_start();

$email_error_msg = $pw_error_msg = $email = $pwd = "";
global $db;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (empty($_POST['emailaddr'])) {								// check for input
		$email_error_msg = "Please enter your email address";
	} else {
		$query = "SELECT user_id FROM user WHERE email = :email";	// check that email is associated with existing account 
		$statement = $db->prepare($query);
		$statement->bindValue(':email', $_POST['emailaddr']);
		$statement->execute();
		if ($statement->rowCount() == 1) {
			$email = $_POST['emailaddr'];
		} else {
			$email_error_msg = "This email is not associated with an account";
		}
		$statement->closecursor();
	}

	if (empty($_POST['password'])) {								// check for input
		$pw_error_msg = "Please enter your password";
	} else {
		$pwd = $_POST['password'];
		$query = "SELECT pwd FROM user WHERE email = :email";	// check that input matches stored password 
		$statement = $db->prepare($query);
		$statement->bindValue(':email', $_POST['emailaddr']);
		$statement->execute();
		$hash = $statement->fetch(); 
		if (password_verify($pwd, $hash["pwd"])) {
			if (empty($email_error_msg) && empty($pw_error_msg)) {
				session_start();
				$_SESSION['user'] = $email;
				setcookie('user', $email, time() + (86400 * 30), "/");
				header('Location: ../favorites/favorites.php');
			}
		} else {
			$pw_error_msg = "Incorrect password";
		}
		$statement->closecursor();
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
	<title>Login to HoosConvert</title>

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
						<a class="nav-link" href="../search/screen2.php">Search</a>
					</li>                                         
					<li class="nav-item">
						<a class="nav-link" href="login.php">Log in</a>
					</li>                       
				</ul>
			</div>  
		</nav>
	</header>

	<div class="container">  
		<h3>Log In to HoosConvert</h3>
		<form class="login-form" action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
			<div class="input-area">
				<label>Email:</label>
				<input type="email" name="emailaddr" id="emailaddr" 
				class="form-control <?php echo (!empty($email_error_msg)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>"
				autofocus required /> <br/>
				<span class="invalid-feedback"><?php echo $email_error_msg;?></span>
			</div>
			<div class="input-area">
				<label>Password: </label>
				<input type="password" name="password" id="pwd" 
				class="form-control <?php echo (!empty($pw_error_msg)) ? 'is-invalid' : ''; ?>" required></input> <br/>
				<span class="invalid-feedback"><?php echo $pw_error_msg;?></span>
			</div>

			<input type="submit" value="Login" class="btn btn-secondary" />
			<p class="signup">Don't have an account?<br/><a href="signup.php">Sign up</a> to get access to<br/>
			more conversion tools!</p> <br/>
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
	</script> -->

	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>
</html>
