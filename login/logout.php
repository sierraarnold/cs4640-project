<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">   
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Sierra Arnold & Min Suk Kim">
  <title>Log out | HoosConvert</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <?php session_start(); ?>

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
    Successfully logged out 
  </div>

  <?php 
  if (count($_SESSION) > 0) {
   foreach ($_SESSION as $k => $v) {
    unset($_SESSION[$k]); 
  }
  session_destroy();
  setcookie("PHPSESSID", "", time()-3600, "/");
  }
  ?>

</body>
</html>