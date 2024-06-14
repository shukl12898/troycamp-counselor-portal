<?php
    
    require '../config/config.php';
    
    if (!isset($_SESSION['username'])){
        header("Location: landing_page.php");
    }

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno){
		echo $mysqli->connect_error;
		exit();
    }
    
    $id = $_POST['usc-id-edit'];
    $first = $_POST['first-name-edit'];
    $last = $_POST['last-name-edit'];
    $tc = $_POST['tc-name-edit'];
    $color = $_POST['color-edit'];
    $class = $_POST['class-edit'];
    $phone = $_POST['phone-edit'];
    $email = $_POST['email-edit'];
                
    if (isset($_POST['color-edit'])){
        $color = $_POST['color-edit'];
    } else {
        $color = 'NULL';
    }

    if (empty(trim($_POST['tc-name-edit']))){
        $tc_name = null;
    } else {
        $tc_name = $_POST['tc-name-edit'];
    }        
    
    $sql_update = "UPDATE counselors 
                   SET 
                       first_name = '$first', 
                       last_name = '$last', 
                       tc_name = '$tc', 
                       phone = '$phone', 
                       email = '$email', 
                       colour_id = $color, 
                       class_id = $class 
                    WHERE id = $id;";

    $results_update = $mysqli->query($sql_update);

    if (!$results_update){
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    $mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your profile updates have been successfully saved. Thank you for helping to keep counselor information current.">
    <title>Edit Confirmation | Troy Camp Counselor Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/shared.css">
    <link rel="stylesheet" href="../css/nav.css">
    <style>
        .fa-user {
            color: #fff;
        }

        #user-under {
            background-color: #fff;
        }

        h1 {
            margin-top: 10px;
            text-align: center;
            color: white;
        }

        #add-form {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            width: 600px;
        }

        .form-control {
            padding: 0.375rem 0.75rem;
            margin-bottom: 1rem;
        }

        label {
            font-weight: bold;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background-color: #ac3c3c;
            border: none;
            font-size: 1.3em;
            margin: 10px;
            width: 500px;
        }

        .btn-primary:hover {
            background-color: #612222;
        }

        .btn-primary:active {
            background-color: #612222;
        }

        h6 {
            margin-top: 20px;
            padding: 10px;
            border-radius: 15px;
            cursor: pointer;
            color: #fff;
        }

        h6:hover {
            color: #fff;
            background-color: #612222;
        }

        #breadcrumb {
            margin: 20px;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: #fff;
            font-weight: bold;
        }

        .breadcrumb-item a {
            text-decoration: none;
            color: white;
        }

        .breadcrumb-item.active {
            color: #fff;
            opacity: 50%;
        }

    </style>
</head>

<body>

    <?php
        include '../html/nav.html';
    ?>

    <div id="breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="user_page.php">Search</a></li>
            <li class="breadcrumb-item"><a href="results.php">Results</a></li>
            <li class="breadcrumb-item"><a href="edit_page.php">Edit</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Confirmation</li>
        </ol>
    </div>

    <div class="container d-flex flex-column align-items-center justify-content-center">
        <h1><?php echo $first?> "<?php echo $tc?>" <?php echo $last?>'s data has successfully been edited!</h1>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../js/logout.js"></script>
    <script>

    </script>
</body>

</html>