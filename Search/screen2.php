<?php
error_reporting(0);
require('../connect-db.php');
session_start();
global $db;
$search_error_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    if (empty($_GET['input'])) {       // check for input
        $search_error_msg = "Please enter a unit";
    } else {
        $query = "SELECT * FROM conversions WHERE unit1 = :unit1";       // check that conversion isn't in system yet
        $statement = $db->prepare($query);
        $statement->bindValue(':unit1', $_GET['input']);
        $statement->execute();
        if ($statement->rowCount() == 0) {
            $search_error_msg = "No conversions containing this unit";
        } else {
            $rows = $statement->fetchAll();
        }
        $statement->closeCursor();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">  
    <title>Home | HoosConvert</title>
    <!-- 2. include meta tag to ensure proper rendering and touch zooming -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 
    Bootstrap is designed to be responsive to mobile.
    Mobile-first styles are part of the core framework.
    
    width=device-width sets the width of the page to follow the screen-width
    initial-scale=1 sets the initial zoom level when the page is first loaded-->
    <meta name="author" content="Min Suk Kim & Sierra Arnold">
    <meta name="description" content="Welcome to HoosConvert">  
    <!-- 3. link bootstrap -->
    <!-- if you choose to use CDN for CSS bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <!-- 
    Use a link tag to link an external resource.
    A rel (relationship) specifies relationship between the current document and the linked resource.-->  
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
                    <li class="nav-item" style=''>
                        <?php if(isset($_SESSION['user'])) echo "<a href='../favorites/favorites.php' style='text-decoration:none; color:gray; margin:20px 10px 20px 10px;'>Favorites</a>"?>
                    </li>
                    <li class="nav-item">
                        <?php echo "<a href='screen2.php' style='text-decoration:none; color:gray; margin:20px 10px 20px 10px;'>Search</a>"?>
                    </li>                                         
                    <li class="nav-item">
                        <?php 
                        if(isset($_SESSION['user'])) { echo "<a href='../login/logout.php' style='text-decoration:none; color:gray; margin:20px 10px 20px 10px;'>Log out</a>";}
                        else { echo "<a href='../login/login.php' style='text-decoration:none; color:gray; margin:20px 10px 20px 10px;'>Log in</a>";}?>
                    </li>                       
                </ul>
            </div>  
        </nav>
    </header>

    <!-- container class, this is the converter box/container -->
    <div class = "container">
        <div class = "row">
            <div class = "col-md-8 offset-md-2">
                <!-- Title -->
                <h1 class = "display-3 text-center mb-1">HoosConvert</h1> 
                <!-- Categories list -->
                <form name = "categories_list">
                    <span>
                        <select class = "select_cat" name = "dropdown_list" size = "1"></select>
                    </span>
                    <br>
                    <br>
                    <div class = "form-group">
                        <input name = "input" id = "initialVal" type = "number" min = "0" class = "form-control form-control-lg" placeholder = "Enter value" />
                        <input type = "submit" name = "convt" value = "Convert" class="btn btn-primary"/>
                        <div class="dropdownMenu">
                            <select id="selectFrom" name = "dropdown">
                                <option value = "km">Kilometers</option>
                                <option value = "m">Meters</option>
                                <option value = "cm">Centimeters</option>
                                <option value = "mi">Miles</option>
                            </select>
                        </div>
                        <small id = "warning" class = "form-text text-muted">Must be greater than 0.</small>
                    </div>
                </form>


                <div id = "result">
                    <div class = "card mb-2">
                        <div class = "card-block">
                            <h4>Meters(m):</h4>
                            <div id = "resultInMt"></div>
                        </div>
                    </div>

                    <div class = "card mb-2">
                        <div class = "card-block">
                            <h4>Feet(ft):</h4>
                            <div id = "resultInFt"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h3>Search the database for conversions</h3>
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>" method="get">
            <div class="form-group" style="width: 60%;text-align: center;">
                <input type="text" name="input" placeholder="Enter an input unit" class="form-control <?php echo (!empty($search_error_msg)) ? 'is-invalid' : ''; ?>"/>
                <span class="invalid-feedback"><?php echo $search_error_msg; ?></span>
                <input type="submit" value="Search" name="search" class="btn btn-primary"/><br/>
            </div>      
        </form>

        <table class="table table-striped table-bordered">
            <tr>
                <th>Input Value</th>
                <th>Input Unit</th>
                <th>Output Value</th>
                <th>Output Unit</th>
                <th>Add to Favorites</th>
            </tr>      
            <?php foreach ((array) $rows as $row): ?>
                <tr>
                    <td>
                        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post"> 
                            <input type="submit" style="width:60%" value="Calculate" name="<?php echo $row['conversion_id']?>" class="btn btn-primary" style="margin:auto; display:flex; width:75%;"/>
                            <input type="number" name="<?php echo 'inputval'.$row['conversion_id']?>" step="0.0001"></input>                        
                        </form>
                    </td>
                    <td>
                        <?php echo $row['unit1']; ?> 
                    </td>        
                    <td>
                        <?php
                        $outputval = '';
                        $id = $row["conversion_id"];
                        echo $id;
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['inputval'.$id])) {
                            $num = (float)$row['ratio'];
                            $val = (float)$_POST['inputval'.$id];
                            $outputval = $num * $val;
                        }
                        echo $outputval;
                        ?>
                    </td>                
                    <td>
                        <?php echo $row['unit2']; ?> 
                    </td>                        
                    <td>
                        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                            <input type="submit" value="Save" style="width:80%" name="<?php echo 'favorite'.$row['conversion_id']?>" class="btn btn-secondary"/>
                            <?php
                                $id = $row['conversion_id'];
                                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['favorite'.$id])) {
                                    $query = "INSERT INTO user_conversions (user_id, conversion_id) SELECT u.user_id, c.conversion_id FROM 
                                        (SELECT c.conversion_id FROM conversions AS c UNION u.user_id, u.email FROM user AS u)
                                        WHERE u.email = :email AND c.conversion_id = :currid";
                                    $statement = $db->prepare($query);
                                    $statement->bindValue(':currid', $row['conversion_id']);
                                    $statement->bindValue(':email', $_SESSION['user']);
                                    $statement->execute();
                                    $statement->closecursor();
                                    echo "Saved to favorites";
                                }
                            ?>
                        </form>
                    </td>                                
                </tr>
            <?php endforeach;?>
         </table>
         <br/>
    </div>

    <?php if (!isset($_SESSION['user'])) {?> <style type="text/css">#conversionLink{display:none;}</style> <?php
    } else {?> <style type="text/css">#conversionLink{display:block;}</style> <?php
    } ?>

    <h5 id="conversionLink">Need a conversion that's not in our database?<br/>
        <a href="http://localhost:4200/">Submit your own!</a></h5><br/>

    <script>
        // Variables
        var categories = new Array();
        var initialValue = document.getElementById('initialVal');

        initialValue.addEventListener("keyup", error);

        // Categories
        categories[0] = "Length";
        categories[1] = "Area";

        // Functions
        function update_searchbox(search_list, categories_array) // user-defined function
        {
            search_list.length = categories_array.length;
            for (i = 0; i < categories_array.length; i++) 
            {
                search_list.options[i].text = categories_array[i];
            }
        }

        // Arrow function, resets the values to 0 when error occurs
        let resetVal = (val) => val.value = 0;

        function error() // Error message
        {
            if(!is_numeric(initialValue.value))
            {
                resetVal(initialValue);
            }
        }

        // Actual calculations
        document.getElementById('result').style.visibility = "hidden"; // Hides the output boxes until the user enters values
        document.getElementById('initialVal').addEventListener('input',
            function(e)
            {
            error(); // Checks for validity
            // document.getElementById('result').style.visibility = "visible"; // Shows the boxes
            // let km = e.target.value;
            // Calculations
            // document.getElementById('resultInMt').innerHTML = km * 1000;
            // document.getElementById('resultInFt').innerHTML = km * 3280.8398950131;
            // document.getElementById('resultInMi').innerHTML = km / 1.609344;
        })

        // How the web application will initially look
        window.onload = function(e) 
        {
            update_searchbox(document.categories_list.dropdown_list, categories);
        }
    </script>
</body>
</html>

<?php
    if(isset($_GET['convt'])) // built-in function
    {
        $cc_input = $_GET['input'];
        $cc_dropdown = $_GET['dropdown'];

        // Goes back to kilometers whenever another unit is executed**

        if(!is_numeric($cc_input))
        {
            die("Error: You have put in an invalid entry.");
        }

        if($cc_dropdown == 'km' && is_numeric($cc_input))
        {
            $meters = $cc_input * 1000;
            $feet = $cc_input * 3280.8398950131;
            $miles = $cc_input / 1.609344;
            $yards = $cc_input * 1093.6132983;
            echo '<span class="card" style="width: 27.5em;margin:0 auto"><div class="card-block"><h4>Miles(mi): </h4>'.$meters.'</span>';
            echo '<span class="card" style="width: 27.5em;margin:0 auto"><div class="card-block"><h4>Miles(mi): </h4>'.$feet.'</span>';
            echo '<span class="card" style="width: 27.5em;margin:0 auto"><div class="card-block"><h4>Miles(mi): </h4>'.$miles.'</span>';
            echo '<span class="card" style="width: 27.5em;margin:0 auto"><div class="card-block"><h4>Yards(yd): </h4>'.$yards.'</span>';
        }

        elseif($cc_dropdown == 'm' && is_numeric($cc_input))
        {
            $miles = $cc_input / 1609.344;
            $yards = $cc_input * 1.0936133;
            echo '<span class="card" style="width: 27.5em;margin:0 auto"><div class="card-block"><h4>Miles(mi): </h4>'.$miles.'</span>';
            echo '<span class="card" style="width: 27.5em;margin:0 auto"><div class="card-block"><h4>Yards(yd): </h4>'.$yards.'</span>';
        }
    }

    ?>