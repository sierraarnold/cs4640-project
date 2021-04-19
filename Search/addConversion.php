<?php
$input_error_msg = $success_msg = "";

// implementation for adding new conversion to conversions table
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['unit1']) || empty($_POST['unit2']) || empty($_POST['ratio'])) {       // check for input
        $input_error_msg = "Please fill out all fields";
    } else {
        $query = "SELECT conversion_id FROM conversions WHERE unit1 = :unit1 AND unit2 = :unit2";       // check that conversion isn't in system yet
        $statement = $db->prepare($query);
        $statement->bindValue(':unit1', $_POST['unit1']);
        $statement->bindValue(':unit2', $_POST['unit2']);
        $statement->execute();
        if ($statement->rowCount() == 1) {
            $input_error_msg = "This conversion is already in the database";
        } else {
            $statement->closeCursor();
            $query = "INSERT INTO conversions (conversion_id, unit1, unit2, ratio) VALUES (NULL, :unit1, :unit2, :ratio)";
            $statement = $db->prepare($query);
            $statement->bindValue(':unit1', $_POST['unit1']);
            $statement->bindValue(':unit2', $_POST['unit2']);
            $statement->bindValue(':ratio', $_POST['ratio']);
            $statement->execute();
            $success_msg = "Success!";
        }
        $statement->closeCursor();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- required to handle IE -->
    <meta name="author" content="Min Suk Kim & Sierra Arnold">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../style.css">

    <title>Home | HoosConvert</title>    
</head>
<body>
    <?php if (!isset($_SESSION['user'])) {?> <style type="text/css">#conversionForm{display:none;}</style> <?php
} else {?> <style type="text/css">#conversionForm{display:block;}</style> <?php
} ?>

<div class="container" id="conversionForm" style="width:50%">
    <h5 style="text-align:center">Need a conversion that's not in our database?<br/>Submit your own!</h5>
    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" style="margin:20px 10px 40px 10px;">
        <?php if(!empty($input_error_msg)) {echo '<div class="alert alert-danger";">' . $input_error_msg . '</div>';}?>
        <?php if(!empty($success_msg)) {echo '<div class="alert alert-success">' . $success_msg . '</div>';}?>
        <div class="form-group">
            <label for="unit1">Input Unit</label>
            <input type="text" name="unit1" class="form-control <?php echo (!empty($input_error_msg)) ? 'is-invalid' : ''; ?>"/>
        </div>
        <div class="form-group">
            <label for="unit2">Output Unit</label>  
            <input type="text" name="unit2" class="form-control <?php echo (!empty($input_error_msg)) ? 'is-invalid' : ''; ?>"/>  
        </div>      
        <div class="form-group">
            <label for="ratio">Conversion Ratio</label>
            <input type="number" name="ratio" step="0.0001" class="form-control <?php echo (!empty($input_error_msg)) ? 'is-invalid' : ''; ?>"/>
        </div>
        <input type="submit" value="Submit" class="btn btn-primary" style="margin:auto; display:flex;"/><br/>
    </form>
</div>
</body>
</html>
