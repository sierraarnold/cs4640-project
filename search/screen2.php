<!-- 1. create HTML5 doctype -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">  
    <title>Unit Conversion</title>
    
    <!-- 2. include meta tag to ensure proper rendering and touch zooming -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- 
    Bootstrap is designed to be responsive to mobile.
    Mobile-first styles are part of the core framework.
    
    width=device-width sets the width of the page to follow the screen-width
    initial-scale=1 sets the initial zoom level when the page is first loaded   
    -->
    
    <meta name="author" content="Min Suk Kim & Sierra Arnold">
    <meta name="description" content="Welcome to HoosConvert">  
    
    <!-- 3. link bootstrap -->
    <!-- if you choose to use CDN for CSS bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    
    <!-- 
    Use a link tag to link an external resource.
    A rel (relationship) specifies relationship between the current document and the linked resource. 
    -->
    <style>
        /* Overall website */
        body {
            background-color: silver;
        }

        /* Title */
        h1 {
            font-family: cursive;
            color: black;
            line-height: normal;
            font-weight: 200;
        }

        /* The container box */
        .container {
            text-align: center;
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Dropdown searchbox position*/
        .container span {
            position: relative;
            width: 100%;
        }

        /* The actual searchbox */
        .container span select {
            background-color: steelblue;
            color: seashell;
            padding: 9px;
            border: 0;
            font-size: large;
            width: 100%;
        }

        /* Dropdown list */
        .container span::before, .container span::after {
            position: absolute;
            content: "";
            pointer-events: none;
        }

        /* The small box that contains the dropdown arrow symbol */
        .container span::before {
            background-color: steelblue;
            top: 0;
            right: 0;
            bottom: 0;
            width: 2em;
            border-radius: 0;
        }

        /* The arrow symbol */
        .container span::after {
            color: black;
            top: 50%;
            right: 1em;
            margin-top: -.5em;
            font-size: 0.71em;
            content: "\25BC";
        }

        /* The warning message */
        .form-group {
            text-align: right;
            line-height: 13px;
        }

        input[type = button]:hover {
            background: black;
            color: white;
        }

        .dropdownMenu {
            text-align: left;
            overflow: visible;
            height: 100%;
        }

        .inputOption {
            width: 100%;
        }

        .selectOption {
            height: 25px;
            font-size: 15px;
        }

        input, select, textarea {
            box-sizing: border-box;
        }

        #warning {
            text-align: center;
        }

        /* The result boxes */
        #result {
            text-align: left;
        }
    </style>
       
</head>

<body>

    <?php include('header.html') ?>

    <!-- container class, this is the converter box/container -->
    <div class = "container">
        
        <div class = "row">
            <div class = "col-md-8 offset-md-2">
                <!-- Title -->
                <h1 class = "display-3 text-center mb-1">HoosConvert</h1> 
                <!-- Categories list -->
                <form name = "categories_list">
                    
                    <span>
                        <select class = "select_cat" name = "dropdown_list" size = 1></select>
                    </span>
                    <br>
                    <br>
                    <!-- The calculator, minimum value is 0 since 'negative miles' doesn't make sense -->
                    <div class = "form-group">
                        
                        <input name = "input" id = "initialVal" type = "number" min = "0" class = "form-control form-control-lg" placeholder = "Enter kilometers" />
                        <input type = "submit" name = "convt" value = "Convert" />
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

                <!-- The following three "cards" are the converted units. They will show up automatically as the user updates values-->
                <div id = "result">
                    <!-- Meters, mb-2 needed for the spacing between the boxes -->
                    <div class = "card mb-2">
                        <div class = "card-block">
                            <h4>Meters(m):</h4>
                            <div id = "resultInMt"></div>
                        </div>
                    </div>

                    <!-- Feet, mb-2 needed for the spacing between the boxes -->
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
        if(initialValue.value < 0) 
            {
            resetVal(initialValue);
            alert("Error: You have entered an invalid number. Please try again.")
            }
        }

        // Actual calculations
        document.getElementById('result').style.visibility = "hidden"; // Hides the output boxes until the user enters values
        document.getElementById('initialVal').addEventListener('input',
        function(e)
        {
            error(); // Checks for validity
            document.getElementById('result').style.visibility = "visible"; // Shows the boxes
            let km = e.target.value;
            // Calculations
            document.getElementById('resultInMt').innerHTML = km * 1000;
            document.getElementById('resultInFt').innerHTML = km * 3280.8398950131;
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

        

        if($cc_dropdown == 'km')
        {
            $miles = $cc_input / 1.609344;
            $yards = $cc_input * 1093.6132983;
            echo '<span class="card" style="width: 27.5em;margin:0 auto"><div class="card-block"><h4>Miles(mi): </h4>'.$miles.'</span>';
            echo '<span class="card" style="width: 27.5em;margin:0 auto"><div class="card-block"><h4>Yards(yd): </h4>'.$yards.'</span>';
        }

        elseif($cc_dropdown == 'm')
        {
            $miles = $cc_input / 1609.344;
            $yards = $cc_input * 1.0936133;
            echo '<span class="card" style="width: 27.5em;margin:0 auto"><div class="card-block"><h4>Miles(mi): </h4>'.$miles.'</span>';
            echo '<span class="card" style="width: 27.5em;margin:0 auto"><div class="card-block"><h4>Yards(yd): </h4>'.$yards.'</span>';
        }
    }

?>