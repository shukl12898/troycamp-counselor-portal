<?php

    require '../config/config.php';

    if (!isset($_SESSION['username'])){
        header("Location: landing_page.php");
    }

    $username = $_SESSION['username'];
    $admin = $_SESSION['admin'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Search and manage counselor profiles by name, color team, and class standing. Admins can also add new counselors here.">
    <title>User Page | Troy Camp Counselor Portal</title>
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
            margin-top: 50px;
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
        }

        h6:hover {
            background-color: #612222;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

    </style>
</head>

<body>

    <?php
        include '../html/nav.html';
    ?>

    <div class="container d-flex flex-column align-items-center justify-content-center">
        <h1>Search for Counselors:</h1>
        <form id="search-form" action="results.php" method="GET">
            <div class="form-group">
                <label for="first-name-search">First Name:</label>
                <input id="first-name-search" name="first-name-search" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="last-name-search">Last Name:</label>
                <input id="last-name-search" name="last-name-search" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="tc-name-search">Troy Camp Name:</label>
                <input id="tc-name-search" name="tc-name-search" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="color-search">Color Team:</label>
                <select id="color-search" name="color-search" class="form-control">
                    <option selected disabled>--Select One--</option>
                    <option value="1">Red</option>
                    <option value="2">Orange</option>
                    <option value="3">Yellow</option>
                    <option value="4">Green</option>
                    <option value="5">Blue</option>
                    <option value="null">No Color Team</option>
                </select>
            </div>
            <div class="form-group">
                <label for="class-search">Class Standing:</label>
                <select id="class-search" name="class-search" class="form-control">
                    <option selected disabled>--Select One--</option>
                    <option value="1">Freshman</option>
                    <option value="2">Sophomore</option>
                    <option value="3">Junior</option>
                    <option value="4">Senior</option>
                    <option value="5">Super Senior</option>
                </select>
            </div>
            <div class="text-center">
                <button id="search-button" class="btn btn-primary">Search</button>
            </div>
        </form>
        <?php if ($admin == 2) :?>
            <h6 id="add-button"><a href="add_page.php">Click here to add a counselor!</a></h6>
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