<?php 
require('../connect-db.php');
session_start();
global $db;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {			// need to fix
	$query = "SELECT c.unit1, c.unit2, c.ratio FROM conversions AS c JOIN user_conversions AS uc 
		ON uc.conversion_id = c.conversion_id JOIN user AS u ON uc.user_id = u.user_id WHERE u.email = :email";
	$statement = $db->prepare($query);
	$statement->bindValue(':email', $_SESSION['user']);	
	$statement->execute();
	// fetchAll() returns an array for all of the rows in the result set
	$rows = $statement->fetchAll();
	$statement->closecursor();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">   
	<meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
	<meta name="viewport" content="width=device-width, initial-scale=1">  
	<meta name="author" content="Sierra Arnold & Min Suk Kim">
	<title>Favorites | HoosConvert</title>

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
						<a class="nav-link" href="#">Favorites</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="../search/screen2.php">Search</a>
					</li>                                         
					<li class="nav-item">
						<a class="nav-link" href="../login/logout.php">Log out</a>
					</li>                       
				</ul>
			</div>  
		</nav>
	</header>

	<div>  
		<h3><?php if(isset($_COOKIE['user'])) echo htmlspecialchars($_COOKIE['user'])?>'s Favorites</h3>
		<div class="conversion-box container p-3 my-3 bg-light border rounded-lg">
			<div class="row">
				<div class="col-5 d-flex justify-content-between">
					<input class="conversion-num" input type="number" id="conversion1" oninput="ozConversion()" style="width: 50%;"/>
					<label class="unit">Ounces (Oz)</label>
					<!-- JS has to be inside div because it doesn't display when at the bottom -->
					<script>
						function ozConversion() {
							var num = 0.125*(document.getElementById("conversion1").value);
							document.getElementById("new-num1").innerHTML = num;
						}
					</script>
				</div>
				<div class="col text-center">
					<label class="unit"> = </label>
				</div>
				<div class="col-5 d-flex justify-content-between">
					<h6 class="unit"><p id="new-num1"></p></h6>
					<label class="unit">Cups</label>
					<button type="button" class="btn btn-outline-secondary btn-sm" style="float: right;">x</button>
				</div>
			</div>
		</div>
		<div class="conversion-box container p-3  my-3 bg-light border rounded-lg">
			<div class="row">
				<div class="col-5 d-flex justify-content-between">
					<input class="conversion-num" type="number" id="conversion2" oninput="usdConversion()" style="width: 50%;"/>
					<label class="unit">US Dollars (USD)</label>
					<script>
						function usdConversion() {
							var num = 0.84*(document.getElementById("conversion2").value);
							document.getElementById("new-num2").innerHTML = num;
						}
					</script>
				</div>
				<div class="col text-center">
					<label class="unit"> = </label>
				</div>
				<div class="col-5 d-flex justify-content-between">
					<h6 class="unit"><p id="new-num2"></p></h6>
					<label class="unit">Euros (EUR)</label>
					<button type="button" class="btn btn-outline-secondary btn-sm" style="float: right;">x</button>
				</div>
			</div>
		</div>
		<div class="conversion-box container p-3 my-3 bg-light border rounded-lg">
			<div class="row">
				<div class="col-5 d-flex justify-content-between">
					<input class="conversion-num" type="number" id="conversion3" oninput="kphConversion()" style="width: 50%;"/>
					<label class="unit">Kilometers/hour</label>
					<script>
						function kphConversion() {
							var num = 0.62*(document.getElementById("conversion3").value);
							document.getElementById("new-num3").innerHTML = num;
						}
					</script>
				</div>
				<div class="col text-center">
					<label class="unit"> = </label>
				</div>
				<div class="col-5 d-flex justify-content-between">
					<h6 class="unit"><p id="new-num3"></p></h6>
					<label class="unit">Miles/hour</label>
					<button type="button" class="btn btn-outline-secondary btn-sm" style="float: right;">x</button>
				</div>
			</div>
		</div>
		<div id="hide-row" class="conversion-box container p-3 my-3 bg-light border rounded-lg">
			<div class="row">
				<div class="col-5 d-flex justify-content-between">
					<input class="conversion-num" type="number" id="conversion4" oninput="ftConversion()" style="width: 50%;"/>
					<label class="unit">Feet</label>
					<script>
						function ftConversion() {
							var num = 30.48*(document.getElementById("conversion4").value);
							document.getElementById("new-num4").innerHTML = num;
						}
					</script>
				</div>
				<div class="col text-center">
					<label class="unit"> = </label>
				</div>
				<div class="col-5 d-flex justify-content-between">
					<h6 class="unit"><p id="new-num4"></p></h6>
					<label class="unit">Centimeters (CM)</label>
					<button type="button" class="btn btn-outline-secondary btn-sm" id="hide-btn" style="float: right;">x</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		document.getElementById("hide-btn").addEventListener("click", hideElement);

		function hideElement() {
			var row = document.getElementById("hide-row");
			if (row.style.display === "none") {
				row.style.display = "block";
			} else
			row.style.display = "none";
		}
	</script>

<!-- 	<div class="container">
		<table class="table table-striped table-bordered">
			<tr>
				<th>Input Value</th>
				<th>Input Unit</th>
				<th>Output Value</th>
				<th>Output Unit</th>
				<th>(Delete?)</th>
			</tr>      
			<?php foreach ($rows as $row): ?>
				<tr>
					<td>
						<input type="number" name="inputval"/>
					</td>
					<td>
						<?php echo $row['unit1']; ?> 
					</td>        
					<td>
						<?php
						echo $row['ratio'];			// use ratio to calculate and display new value here
						?> 
					</td>                
					<td>
						<?php echo $row['unit2']; ?> 
					</td>                        
					<td>
						<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
							<input type="submit" value="Delete" name="action" class="btn btn-danger" />      
							<input type="hidden" name="conversion_id" value="<?php echo $task['conversion_id'] ?>" />
						</form>
					</td>                                
				</tr>
			<?php endforeach; ?>
		</table>
	</div> -->

	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>
</html>
