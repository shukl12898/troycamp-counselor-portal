<?php

    require '../config/config.php';

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno){
		echo $mysqli->connect_error;
		exit();
	}

    $id = $_POST['usc_id'];

    $sql = "SELECT *
            FROM counselors
            WHERE id = $id;";

    $results = $mysqli->query($sql);

    if (!$results){
        echo $mysqli->error;
		$mysqli->close();
		exit();
    }

    if ($results->num_rows > 0){
        echo "valid";
    } else {
        echo "invalid";
    }

?>