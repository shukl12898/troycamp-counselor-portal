<?php
    
    require '../config/config.php';
    
    if (!isset($_SESSION['username'])){
        header("Location: landing_page.php");
    }

    if (!isset($_GET['id'])){
        header("Location: results.php");
    };

    $id = $_GET['id'];
    $prev = urldecode($_GET['prev']);

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno){
		echo $mysqli->connect_error;
		exit();
    }
    
    $sql = "SELECT *
            FROM counselors
            WHERE id = $id;";

    $result = $mysqli->query($sql);

    if (!$result){
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    $row = $result->fetch_assoc();

    $mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Update counselor details on the edit page to ensure profile accuracy and integrity. Admins can edit any counselor's information, whereas non-admin counselors can only edit their own.">
    <title>Edit A Counselor | Troy Camp Counselor Portal</title>
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

        #search-form {
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
            <li class="breadcrumb-item"><a href="<?php echo $prev ?>">Results</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </div>

    <div class="container d-flex flex-column align-items-center justify-content-center">
        <h1>Edit:</h1>
        <form id="edit-form" action="edit_confirmation.php" method="POST">
            <div class="form-group">
                <label for="usc-id-edit">USC ID:</label>
                <input id="usc-id-edit" name="usc-id-edit" type="text" class="form-control" value="<?php echo $id?>">
            </div>
            <div class="form-group">
                <label for="first-name-edit">First Name:</label>
                <input id="first-name-edit" name="first-name-edit" type="text" class="form-control" value="<?php echo $row['first_name']?>">
            </div>
            <div class="form-group">
                <label for="last-name-edit">Last Name:</label>
                <input id="last-name-edit" name="last-name-edit" type="text" class="form-control" value="<?php echo $row['last_name']?>">
            </div>
            <div class="form-group">
                <label for="tc-name-edit">Troy Camp Name:</label>
                <input id="tc-name-edit" name="tc-name-edit" type="text" class="form-control" value="<?php echo $row['tc_name']?>">
            </div>
            <div class="form-group">
                <label for="phone-edit">Phone:</label>
                <input id="phone-edit" name="phone-edit" type="text" class="form-control" value="<?php echo $row['phone']?>">
            </div>
            <div class="form-group">
                <label for="email-edit">Email:</label>
                <input id="email-edit" name="email-edit" type="text" class="form-control" value="<?php echo $row['email']?>">
            </div>
            <div class="form-group">
                <label for="color-edit">Color Team:</label>
                <select id="color-edit" name="color-edit" class="form-control">
                    <option value="1" <?php if ($row['colour_id'] == 1) : ?> selected <?php endif; ?>>Red</option>
                    <option value="2" <?php if ($row['colour_id'] == 2) : ?> selected <?php endif; ?>>Orange</option>
                    <option value="3" <?php if ($row['colour_id'] == 3) : ?> selected <?php endif; ?>>Yellow</option>
                    <option value="4" <?php if ($row['colour_id'] == 4) : ?> selected <?php endif; ?>>Green</option>
                    <option value="5" <?php if ($row['colour_id'] == 5) : ?> selected <?php endif; ?>>Blue</option>
                    <option value="null" <?php if ($row['colour_id'] == null) : ?> selected <?php endif; ?>>No Color Team</option>
                </select>
            </div>
            <div class="form-group">
                <label for="class-edit">Class Standing:</label>
                <select id="class-edit" name="class-edit" class="form-control">
                    <option value="1" <?php if ($row['class_id'] == 1) : ?> selected <?php endif; ?>>Freshman</option>
                    <option value="2" <?php if ($row['class_id'] == 2) : ?> selected <?php endif; ?>>Sophomore</option>
                    <option value="3" <?php if ($row['class_id'] == 3) : ?> selected <?php endif; ?>>Junior</option>
                    <option value="4" <?php if ($row['class_id'] == 4) : ?> selected <?php endif; ?>>Senior</option>
                    <option value="5" <?php if ($row['class_id'] == 5) : ?> selected <?php endif; ?>>Super Senior</option>
                </select>
            </div>
            <div class="text-center">
                <button id="edit-button" class="btn btn-primary">Make Changes</button>
            </div>
        </form>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../js/logout.js"></script>
    <script>

    </script>
</body>

</html>