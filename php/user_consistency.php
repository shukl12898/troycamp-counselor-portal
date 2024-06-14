<?php

    require '../config/config.php';

    if (!isset($_SESSION['username'])){
        header("Location: landing_page.php");
    }

    $id = $_GET['id'];
    $prev = urldecode($_GET['prev']);
    
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno){
		echo $mysqli->connect_error;
		exit();
    }
    
    $sql = "SELECT tc_name, first_name, sps AS SPs, lit AS LIT, leads AS 'TC Leads', yearlong AS Yearlong, meeting, programming, mandatory, total
            FROM counselors
            WHERE id = $id;";

    $result = $mysqli->query($sql);

    if (!$result){
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    $row = $result->fetch_assoc();
    $tc_name = $row['tc_name'];

    if (empty($tc_name)){
        $tc_name = $row['first_name'];
    }

    $total_score = $row['total'] . '%';
    $programming_score = $row['programming'] . '%';
    $mandatory_score = $row['mandatory'] . '%';
    $meeting_score = $row['meeting'] . '%';

    $programmings = [];

    foreach ($row as $key => $value){
        if ($value !== null && $key !== 'total' && $key != 'programming' && $key != 'mandatory' && $key !== 'meeting' && $key !== 'tc_name' && $key !== 'first_name'){
            $programmings[$key] = $value;
        }
    }

    $meeting_sql = "SELECT * 
                    FROM points 
                        LEFT JOIN programming 
                            ON points.programming_id = programming.id
                        LEFT JOIN credit
                            ON points.credit_id = credit.id
                        LEFT JOIN dates
                            ON points.date_id = dates.date_id
                    WHERE points.usc_id = $id AND programming.name = 'General Meeting';";

    $meeting_result = $mysqli->query($meeting_sql);

    if (!$meeting_result) {
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    $point_breakdowns = [];

    $sp_sql = "SELECT * 
               FROM points 
                    LEFT JOIN programming 
                        ON points.programming_id = programming.id
                    LEFT JOIN credit
                        ON points.credit_id = credit.id
                    LEFT JOIN dates
                        ON points.date_id = dates.date_id
                WHERE points.usc_id = $id AND programming.name = 'Elementary School Programming';";

    $sp_result = $mysqli->query($sp_sql);

    if (!$sp_result) {
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    $point_breakdowns['SPs'] = $sp_result;

    $lit_sql = "SELECT * 
               FROM points 
                    LEFT JOIN programming 
                        ON points.programming_id = programming.id
                    LEFT JOIN credit
                        ON points.credit_id = credit.id
                    LEFT JOIN dates
                        ON points.date_id = dates.date_id
                WHERE points.usc_id = $id AND programming.name = 'Middle School Programming';";

    $lit_result = $mysqli->query($lit_sql);

    if (!$lit_result) {
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    $point_breakdowns['LIT'] = $lit_result;


    $leads_sql = "SELECT * 
               FROM points 
                    LEFT JOIN programming 
                        ON points.programming_id = programming.id
                    LEFT JOIN credit
                        ON points.credit_id = credit.id
                    LEFT JOIN dates
                        ON points.date_id = dates.date_id
                WHERE points.usc_id = $id AND programming.name = 'High School Programming';";

    $leads_result = $mysqli->query($leads_sql);

    if (!$leads_result) {
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    $point_breakdowns['TC Leads'] = $leads_result;

    $yearlong_sql = "SELECT * 
               FROM points 
                    LEFT JOIN programming 
                        ON points.programming_id = programming.id
                    LEFT JOIN credit
                        ON points.credit_id = credit.id
                    LEFT JOIN dates
                        ON points.date_id = dates.date_id
                WHERE points.usc_id = $id AND programming.name = 'Yearlong';";

    $yearlong_result = $mysqli->query($yearlong_sql);

    if (!$yearlong_result) {
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    $point_breakdowns['Yearlong'] = $yearlong_result;

    $mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Access detailed consistency scores for selected counselors, available exclusively to admins and authorized users.">
    <title>Consistency Score | Troy Camp Counselor Portal</title>
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

        .carousel {
            width: 80%;
            margin: auto;
            margin-top: 30px;
        }

        .carousel-item {
            height: 800px;
            width: 100%;
        }

        .carousel-control-prev {
            left: -80px;
        }

        .carousel-control-next {
            right: -80px;
        }


        .progress {
            width: 500px;
            height: 500px;
            line-height: 750px;
            background: none;
            position: absolute;
            margin: 140px;
            border-radius: 50%;
            border: 3px solid;
            border-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .progress>span {
            width: 50%;
            height: 100%;
            overflow: hidden;
            position: absolute;
            top: 0;
        }

        .progress .progress-left {
            left: 0;
        }

        .progress .progress-bar {
            width: 100%;
            height: 100%;
            background: none;
            border-width: 5px;
            border-style: solid;
            position: absolute;
            top: 0;
        }

        .progress .progress-left .progress-bar {
            left: 100%;
            border-top-right-radius: 250px;
            /*Change this to change height*/
            border-bottom-right-radius: 250px;
            /*Change this to change height*/
            border-left: 0;
            transform-origin: center left;
        }

        .progress .progress-right {
            right: 0;
        }

        .progress .progress-right .progress-bar {
            left: -100%;
            border-top-left-radius: 250px;
            /*Change this to change height*/
            border-bottom-left-radius: 250px;
            /*Change this to change height*/
            border-right: 0;
            transform-origin: center right;
        }

        .progress .progress-value {
            width: 86%;
            height: 86%;
            border-radius: 50%;
            border: 5px dotted white;
            font-size: 100px;
            text-align: center;
            position: absolute;
            top: 7.5%;
            left: 7.5%;
        }

        .consistency-score {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .breakdown {
            border: #fff solid 3px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: absolute;
            right: 100px;
            top: 25%;
            width: 600px;
            height: 400px;
            color: white;
            overflow-y: scroll;
        }

        .breakdown h2 {
            margin-top: 10px;
        }

        h1 {
            width: 100%;
            text-align: center;
            display: block;
            position: relative;
            top: 60px;
            font-size: 3em;
            color: white;
        }

        table {
            width: 600px;
            text-align: center;
            color: white;
        }

        td {
            border: #fff solid 1px;
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
            <li class="breadcrumb-item active" aria-current="page">Consistency</li>
        </ol>
    </div>

    <h1><?php echo $tc_name ?>'s Consistency Score Breakdown</h1>

    <div id="carousel" class="carousel slide container-fluid">
        <div class="carousel-indicators">
            <?php for ($i = 0; $i < count($programmings) + 3; $i++) : ?>
                <button type="button" data-bs-target="#carousel" data-bs-slide-to="<?php echo $i ?>" <?php if ($i == 0) : ?> class="active" aria-current="true" <?php endif; ?> aria-label="Slide <?php echo $i + 1 ?>"></button>
            <?php endfor; ?>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="progress">
                    <span class="progress-left d-flex justify-content-center">
                        <span class="progress-bar"></span>
                    </span>
                    <span class="progress-right">
                        <span class="progress-bar"></span>
                    </span>
                    <div class="progress-value">
                        <div class="consistency-score"><?php echo $total_score?></div>
                    </div>
                </div>
                <div class="breakdown">
                    <h2 class="text-center">Total Consistency Score</h2>
                    <table>
                        <tr>
                            <td>Programming Score</td>
                            <td><?php echo $programming_score?></td>
                        </tr>
                        <tr>
                            <td>Mandatory Score</td>
                            <td><?php echo $mandatory_score?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="carousel-item">
                <div class="progress">
                    <span class="progress-left">
                        <span class="progress-bar"></span>
                    </span>
                    <span class="progress-right">
                        <span class="progress-bar"></span>
                    </span>
                    <div class="progress-value">
                        <div class="consistency-score"><?php echo $programming_score?></div>
                    </div>
                </div>
                <div class="breakdown">
                    <h2 class="text-center">Programming Score</h2>
                    <table>
                        <?php foreach ($programmings as $key => $value) : ?>
                            <tr>
                                <td><?php echo $key ?></td>
                                <td><?php echo $value . '%'?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <div class="carousel-item">
                <div class="progress">
                    <span class="progress-left">
                        <span class="progress-bar"></span>
                    </span>
                    <span class="progress-right">
                        <span class="progress-bar"></span>
                    </span>
                    <div class="progress-value">
                        <div class="consistency-score"><?php echo $meeting_score?></div>
                    </div>
                </div>
                <div class="breakdown">
                    <h2 class="text-center">Meeting Attendance</h2>
                    <table>
                        <?php if ($meeting_result->num_rows == 0) : ?>
                            <tr class="text-center">
                                <td>
                                    <?php $tc_name . " completed all their commitments for this programming!"; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php while ($row = $meeting_result->fetch_assoc()) : ?>    
                        <tr>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php 
                                if ($row['name'] == "Attendance") {
                                    if ($row['points'] == 0){
                                        echo "Unexcused Absence";
                                    } else {
                                        echo "Excused Absence";
                                    }
                                } else {
                                    echo "Missed Calling";
                                }
                            ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </table>
                </div>
            </div>
            <?php foreach ($programmings as $key => $value) : ?>
                <div class="carousel-item">
                    <div class="progress">
                        <span class="progress-left">
                            <span class="progress-bar"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar"></span>
                        </span>
                        <div class="progress-value">
                            <div class="consistency-score"><?php echo $value . '%' ?></div>
                        </div>
                    </div>
                    <div class="breakdown">
                        <h2 class="text-center"><?php echo $key?></h2>
                        <table>
                            <?php if ($meeting_result->num_rows == 0) : ?>
                                <tr class="text-center">
                                    <td>
                                        <?php echo $tc_name . " completed all their commitments for this programming!"; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php while ($row = $point_breakdowns[$key]->fetch_assoc()) : ?>    
                        <tr>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php 
                                if ($row['name'] == "Attendance") {
                                    if ($row['points'] == 0){
                                        echo "Unexcused Absence";
                                    } else {
                                        echo "Excused Absence";
                                    }
                                } else {
                                    echo "Missed Calling";
                                }
                            ?></td>
                        </tr>
                        <?php endwhile; ?>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            // Select all carousel items
            const carouselItems = document.querySelectorAll(".carousel-item");

            // Loop through each carousel item
            carouselItems.forEach(function (item) {
                const progressBarLeft = item.querySelector(".progress-left .progress-bar");
                const progressBarRight = item.querySelector(".progress-right .progress-bar");
                const progressValue = item.querySelector(".progress-value");
                // Use 'textContent' to get the text inside the class
                const consistencyScoreText = item.querySelector(".consistency-score").textContent;

                // Parse the percentage value from the text
                const valFloat = parseFloat(consistencyScoreText.replace('%', ''));
                const valRounded = Math.round(valFloat);

                // Set the colors according to the value
                setProgressColors(progressBarLeft, progressBarRight, progressValue, valRounded);

                // Calculate the degrees for each half-circle
                let leftDeg = 0;
                let rightDeg = 0;

                if (valRounded <= 50) {
                    rightDeg = (valRounded / 50) * 180;
                } else {
                    rightDeg = 180;
                    leftDeg = ((valRounded - 50) / 50) * 180;
                }

                // Apply the rotation styles directly to each progress bar
                progressBarRight.style.transform = `rotate(${rightDeg}deg)`;
                progressBarLeft.style.transform = `rotate(${leftDeg}deg)`;
            });

            // Function to set the colors based on the score
            function setProgressColors(progressBarLeft, progressBarRight, progressValue, value) {
                let color;
                if (value < 50) {
                    color = "#ac3c3c";
                } else if (value >= 50 && value <= 60) {
                    color = "#DAA520";
                } else {
                    color = "#009A44";
                }
                progressBarLeft.style.borderColor = color;
                progressBarRight.style.borderColor = color;
                progressValue.style.color = color;
            }
        });


    </script>
</body>

</html>