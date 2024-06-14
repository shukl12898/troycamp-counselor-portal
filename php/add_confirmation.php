<?php
    
    require '../config/config.php';
    
    if (!isset($_SESSION['username'])){
        header("Location: landing_page.php");
    }

    $id = $_POST['usc-id-add'];
    $first = $_POST['first-name-add'];
    $last = $_POST['last-name-add'];
    $class = $_POST['class-add'];
    $email = $_POST['email-add'];
    $phone = $_POST['phone-add'];

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno){
		echo $mysqli->connect_error;
		exit();
    }

    $sql_id_check = "SELECT * 
                     FROM counselors
                     WHERE id = $id;";

    $result_check = $mysqli->query($sql_id_check);

    if (!$result_check){
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    if ($result_check->num_rows > 0){
        $error = "This USC ID is already in the database!";
    } else {

        if (isset($_POST['color-add'])){
            $color = $_POST['color-add'];
        } else {
            $color = 'NULL';
        }

        if (empty(trim($_POST['tc-name-add']))){
            $tc_name = null;
        } else {
            $tc_name = $_POST['tc-name-add'];
        }

        $sql = "INSERT INTO counselors (id, first_name, last_name, tc_name, phone, email, colour_id, class_id, meeting, programming, mandatory, total)
                VALUES ($id, '$first', '$last', '$tc_name', '$phone', '$email', $color, $class, 0.0, 0.0, 0.0, 0.0)";

        $results = $mysqli->query($sql);

        if (!$results){
            echo $mysqli->error;
            $mysqli->close();
            exit();
        }

    }

    $mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="New counselor successfully added to the counselor database! We welcome them to the Troy Camp counselor community and thank you for ensuring their details are accurately reflected in the database.">
    <title>Add Confirmation | Troy Camp Counselor Portal</title>
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

        #error {
            color: #ac3c3c;
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
            <li class="breadcrumb-item"><a href="add_page.php">Add Counselor</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Confirmation</li>
        </ol>
    </div>

    <div class="container d-flex flex-column align-items-center justify-content-center">
        <?php if (isset($error)) : ?>
            <h1 id="error"><?php echo $error?></h1>
        <?php else : ?>
            <h1><?php echo $first ?> 
            <?php if (!empty($tc_name)) :?> "<?php echo $tc_name?>" <?php endif; ?>
            <?php echo $last ?> successfully added to Troy Camp's roster!</h1>
        <?php endif; ?>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../js/logout.js"></script>
    <script>

    </script>
</body>

</html>