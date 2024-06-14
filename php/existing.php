<?php

require '../config/config.php';

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($mysqli->connect_errno){
        echo $mysqli->connect_error;
        exit();
    }

    $id = $_POST['usc_id'];
    $username = $_POST['username'];

    $sql_check_name = "SELECT *
                       FROM users
                       WHERE username = '$username';";

    $results_check_name = $mysqli->query($sql_check_name);

    if (!$results_check_name){
        echo $mysqli->error;
		$mysqli->close();
		exit();
    }

    $sql_check_id = "SELECT *
                     FROM users 
                     WHERE usc_id = $id;";

    $results_check_id = $mysqli->query($sql_check_id);

    if (!$results_check_id){
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    if ($results_check_name->num_rows > 0){
        echo "This username is already taken! Try again.";
    } else if ($results_check_id->num_rows > 0){
        echo "You already have an account! Try logging in!";
    }
    
?>