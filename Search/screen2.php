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
                        <select class = "select_cat" name = "dropdown_list" size = "1" onChange = "changeUnits(this, document.convertFrom.dropdown_unit)"></select>
                    </span>
                </form>
                <br>
                <br>
                <!-- The calculator, minimum value is 0 since 'negative miles' doesn't make sense -->
                <div class = "form-group">
                    <form name = "convertFrom">
                        <input name = "input" id = "initialVal" type = "number" min = "0" class = "form-control form-control-lg" placeholder = "Enter: " />
                        <input type = "submit" class="btn btn-primary" name = "convt" value = "Convert" />
                        <div class="dropdownMenu">
                            <select id = "selectFrom" name = "dropdown_unit"></select>
                        </div>
                    </form>
                    <small id = "warning" class = "form-text text-muted">Must be greater than 0.</small>
                </div>
                <div id = "result"></div>
            </div>
        </div>
    </div>

    <?php
        if(isset($_GET['convt'])) // built-in function
        {
            $cc_input = $_GET['input'];
            $cc_dropdown = $_GET['dropdown_unit'];

            // Goes back to kilometers whenever another unit is executed**
            if(!is_numeric($cc_input))
            {
                die("Error: You have put in an invalid entry.");
            }
            if($cc_dropdown == 'Kilometers' && is_numeric($cc_input))
            {
                $meters = $cc_input * 1000;
                $feet = $cc_input * 3280.8398950131;
                $miles = $cc_input / 1.609344;
                $yards = $cc_input * 1093.6132983;
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Meters(m): </h4>'.$meters.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Feet(ft): </h4>'.$feet.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Miles(mi): </h4>'.$miles.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Yards(yd): </h4>'.$yards.'</span>';
            }
            elseif($cc_dropdown == 'Meters' && is_numeric($cc_input))
            {
                $kilometers = $cc_input / 1000;
                $feet = $cc_input * 3.28084;
                $miles = $cc_input / 1609.344;
                $yards = $cc_input * 1.0936133;
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Kilometers(km): </h4>'.$kilometers.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Feet(ft): </h4>'.$feet.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Miles(mi): </h4>'.$miles.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Yards(yd): </h4>'.$yards.'</span>';
            }
            elseif($cc_dropdown == 'Centimeters' && is_numeric($cc_input))
            {
                $kilometers = $cc_input / 100000;
                $feet = $cc_input / 0.0328084;
                $miles = $cc_input / 160934;
                $yards = $cc_input * 0.010936132983;
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Kilometers(km): </h4>'.$kilometers.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Feet(ft): </h4>'.$feet.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Miles(mi): </h4>'.$miles.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Yards(yd): </h4>'.$yards.'</span>';
            }
            elseif($cc_dropdown == 'Inches' && is_numeric($cc_input))
            {
                $kilometers = $cc_input /39370;
                $feet = $cc_input / 12;
                $miles = $cc_input / 63360;
                $yards = $cc_input / 36;
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Kilometers(km): </h4>'.$kilometers.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Feet(ft): </h4>'.$feet.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Miles(mi): </h4>'.$miles.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Yards(yd): </h4>'.$yards.'</span>';
            }
            elseif($cc_dropdown == 'Celsius' && is_numeric($cc_input))
            {
                $fahrenheit = ($cc_input * 1.8) + 32;
                $kelv = $cc_input + 273.15;
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Fahrenheit: </h4>'.$fahrenheit.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Kelvin(K): </h4>'.$kelv.'</span>';
            }
            elseif($cc_dropdown == 'Fahrenheit' && is_numeric($cc_input))
            {
                $celsius = (($cc_input - 32) * 5 / 9);
                $kelv = (($cc_input - 32) * 5 / 9) + 273.15;
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Celsius: </h4>'.$celsius.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Kelvin(K): </h4>'.$kelv.'</span>';
            }
            elseif($cc_dropdown == 'Kelvin' && is_numeric($cc_input))
            {
                $fahrenheit = ($cc_input - 273.15) * 1.8 + 32;
                $celsius = $cc_input - 273.15;
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Fahrenheit: </h4>'.$fahrenheit.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Celsius(K): </h4>'.$celsius.'</span>';
            }
            elseif($cc_dropdown == 'Kilograms' && is_numeric($cc_input))
            {
                $lb = $cc_input * 2.2046226218;
                $oz = $cc_input * 35.273962;
                $gram = $cc_input * 1000;
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Pounds(lb): </h4>'.$lb.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Ounces(oz): </h4>'.$oz.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Grams(g): </h4>'.$gram.'</span>';
            }
            elseif($cc_dropdown == 'Pounds' && is_numeric($cc_input))
            {
                $kg = $cc_input / 2.2046226218;
                $oz = $cc_input * 16;
                $gram = $cc_input * 453.59237;
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Kilograms(kg): </h4>'.$kg.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Ounces(oz): </h4>'.$oz.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Grams(g): </h4>'.$gram.'</span>';
            }
            elseif($cc_dropdown == 'Ounces' && is_numeric($cc_input))
            {
                $lb = $cc_input / 16;
                $kg = $cc_input / 35.273962;
                $gram = $cc_input * 28.34952;
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Pounds(lb): </h4>'.$lb.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Kilograms(kg): </h4>'.$kg.'</span>';
                echo '<span class="card" style="width: 27.5em;margin:0 auto"><h4>Grams(g): </h4>'.$gram.'</span>';
            }
        }
    ?>

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
                            <input type="submit" value="Calculate" name="<?php echo $row['conversion_id']?>" 
                            class="btn btn-primary" style="margin:auto; display:flex; width:75%;"/>
                            <input type="number" name="<?php echo 'inputval'.$row['conversion_id']?>" step="0.0001"/>                        
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
        var unitsArr = new Array();

        categories[0] = "Length";
        categories[1] = "Temperature";
        categories[2] = "Mass";

        unitsArr[0] = new Array("Kilometers", "Meters", "Centimeters", "Inches");
        unitsArr[1] = new Array("Celsius", "Fahrenheit", "Kelvin");
        unitsArr[2] = new Array("Kilograms", "Pounds", "Ounces");

        var initialValue = document.getElementById('initialVal');

        initialValue.addEventListener("keyup", error);

        // Functions
        function error() // Error message
        {
            if(!is_numeric(initialValue.value))
            {
                resetVal(initialValue);
            }
        }

        function changeUnits(category, unit) {
            var i;
            i = category.selectedIndex;
            updateArr(unit, unitsArr[i]);
        }

        function updateArr(dropdownLst, dropdownArr) {
            var i;
            dropdownLst.length = dropdownArr.length;
            for (i = 0; i < dropdownArr.length; i++) {
                dropdownLst.options[i].text = dropdownArr[i];
            }
        }

        document.getElementById('result').style.visibility = "hidden"; // Hides the output boxes until the user enters values
        document.getElementById('initialVal').addEventListener('input',
            function(e)
            {
            error(); // Checks for validity
        })

        window.onload = function(e) 
        {
            updateArr(document.categories_list.dropdown_list, categories);
            changeUnits(document.categories_list.dropdown_list, document.convertFrom.dropdown_unit);
        }
    </script>
</body>
</html>