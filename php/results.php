<?php

require '../config/config.php';

if (!isset($_SESSION['username'])){
    header("Location: landing_page.php");
}

$username = $_SESSION['username'];
$logged_id = $_SESSION['usc_id'];
$admin = $_SESSION['admin'];

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$url = urlencode($_SERVER['REQUEST_URI']);

if ($mysqli->connect_errno){
    echo $mysqli->connect_error;
    exit();
}

$sql = "SELECT *
        FROM counselors
        WHERE 1=1";

$all_counselors_sql = $sql . ";";
$all_results = $mysqli->query($all_counselors_sql);
$num_total_counselors = $all_results->num_rows; 

if (isset($_GET['first-name-search']) && !empty($_GET['first-name-search'])){
    $first = $_GET['first-name-search'];
    $sql .= " AND first_name LIKE '%$first%'";
}

if (isset($_GET['last-name-search']) && !empty($_GET['last-name-search'])){
    $last = $_GET['last-name-search'];
    $sql .= " AND last_name LIKE '%$last%'";
}

if (isset($_GET['tc-name-search']) && !empty($_GET['tc-name-search'])){
    $tc = $_GET['tc-name-search'];
    $sql .= " AND tc_name LIKE '%$tc%'";
}

if (isset($_GET['color-search'])){
    $colour = $_GET['color-search'];
    if ($colour == "null"){
        $sql .= " AND colour_id IS NULL";
    } else {
        $sql .= " AND colour_id = $colour";
    }
}

if (isset($_GET['class-search'])){
    $class = $_GET['class-search'];
    $sql .= " AND class_id = $class";
}

$sql_colours_breakdown = "SELECT colour.name, COUNT(filtered_counselors.id) AS count
                          FROM ($sql) AS filtered_counselors
                            LEFT JOIN colour
                                ON filtered_counselors.colour_id = colour.id
                          GROUP BY colour.name;";

$colours_breakdown = $mysqli->query($sql_colours_breakdown);

if (!$colours_breakdown){
    echo $mysqli->error;
    $mysqli->close();
    exit();
}

$colors_count = [];
while ($row = $colours_breakdown->fetch_assoc()){
    $colors_count[$row['name']] = $row['count'];
}

$sql_class_breakdown = "SELECT class.name, COUNT(filtered_counselors.id) AS count
                          FROM ($sql) AS filtered_counselors
                            LEFT JOIN class
                                ON filtered_counselors.class_id = class.id
                          GROUP BY class.name;";

$class_breakdown = $mysqli->query($sql_class_breakdown);

if (!$class_breakdown){
    echo $mysqli->error;
    $mysqli->close();
    exit();
}

$class_count = [];
while ($row = $class_breakdown->fetch_assoc()){
    $class_count[$row['name']] = $row['count'];
}

$sql_programming_breakdown = "SELECT COUNT(lit) AS LIT,
                                     COUNT(sps) AS SPs,
                                     COUNT(yearlong) AS Yearlong,
                                     COUNT(leads) AS 'TC Leads'
                              FROM ($sql) AS filtered_counselors;";

$programming_breakdown = $mysqli->query($sql_programming_breakdown);

if (!$programming_breakdown){
    echo $mysqli->error;
    $mysqli->close();
    exit();
}

$programming_count = $programming_breakdown->fetch_assoc();

$sql_none_programming = "SELECT COUNT(*) AS count
                         FROM ($sql) AS filtered_counselors
                         WHERE lit IS NULL AND sps IS NULL AND yearlong IS NULL AND leads IS NULL;";

$none_programming = $mysqli->query($sql_none_programming);

if (!$none_programming){
    echo $mysqli->error;
    $mysqli->close();
    exit();
}

$none_count = $none_programming->fetch_assoc()['count'];

$sql_total_average = "SELECT AVG(total) AS Total, AVG(programming) AS Programming, AVG(meeting) AS Meeting, 
                      AVG(sps) AS SPs, AVG(lit) AS LIT, AVG(leads) AS 'TC Leads', AVG(yearlong) AS Yearlong
                    FROM ($sql) AS filtered_counselors;";

$average_result = $mysqli->query($sql_total_average);

if (!$average_result){
    echo $mysqli->error;
    $mysqli->close();
    exit();
}

$total_average = $average_result->fetch_assoc();

$sql .= ' ORDER BY first_name;';

$results = $mysqli->query($sql);
$num_results = $results->num_rows;

if (!$results){
    echo $mysqli->error;
    $mysqli->close();
    exit();
}

$sql_colors = "SELECT *
               FROM colour;";

$results_colors = $mysqli->query($sql_colors);

if (!$results_colors){
    echo $mysqli->error;
    $mysqli->close();
    exit();
}

$colors = [];

while ($row = $results_colors->fetch_assoc()){
    $colors[$row['id']] = $row['name'];
}

$sql_class = "SELECT *
               FROM class;";

$results_class = $mysqli->query($sql_class);

if (!$results_class){
    echo $mysqli->error;
    $mysqli->close();
    exit();
}

$class_array = [];

while ($row = $results_class->fetch_assoc()){
    $class_array[$row['id']] = $row['name'];
}

$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Scroll through search results to find specific counselors. Edit your profile, or if you're an admin, manage or delete others' profiles.">
    <title>Search Results | Troy Camp Counselor Portal</title>
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
            margin-top: 0px;
            text-align: center;
            color: white;
            margin-bottom: 0px;
        }

        .table-container {
            margin-top: 20px;
            width: 1200px;
            max-height: 720px; 
            overflow-y: auto;
        }

        table {
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            font-size: 1em;
        }

        td {
            padding: 10px;
            text-align: center;
            color: white;
            border: 0.3px solid #ddd;
        }

        tr {
            border-bottom: 1px solid #ddd;
        }

        tr:last-child {
            border-bottom: none;
        }

        .fa-edit {
            color: white;
        }

        .fa-edit:hover {
            color: #DAA520;
        }

        .fa-trash {
            color: #fff;
        }

        .fa-trash:hover {
            color: #ac3c3c;
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

        #summary {
            margin-top: 5px;
            background-color: #612222;
            border: none;
        }

        #summary:hover {
            background-color: #800020;
        }

        table a {
            color: white;
            text-decoration: none;
        }

        table a:hover {
            color: dodgerblue;
            text-decoration: underline;
        }
        
        em {
            text-align: left;
            color: white;
        }

        .modal-body td {
            color: black;
        }

        .modal-body h6 {
            margin: 5px;
            text-decoration: underline;
        }

        .modal-footer button {
            background-color: #612222;
        }

        .carousel-indicators {
            position: absolute;
            bottom: -20px;
        }

        .carousel-inner table {
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
            margin-bottom: 40px;
            width: 500px;
        }

        .carousel-inner th {
            background-color: lightgrey;
        }

    </style>
</head>

<body>

    <?php include '../html/nav.html'; ?>

    <div id="breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="user_page.php">Search</a></li>
            <li class="breadcrumb-item active" aria-current="page">Results</li>
        </ol>
    </div>

    <div class="container d-flex flex-column align-items-center justify-content-center">
        <h1>Search Results:</h1>
        <em>Showing <?php echo $num_results ?> out of <?php echo $num_total_counselors ?> Counselors</em>
        <?php if ($admin) : ?>
            <div class="text-center">
                <button id="summary" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#summaryModal">Summary of Results</button>
            </div>
        <?php endif; ?>
        <div class="modal modal-lg fade" id="summaryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel">Summary of <?php echo $num_results?> Search Results</h5>
            </div>
            <div class="modal-body">
            <div id="carouselExampleDark" class="carousel carousel-dark slide">
                <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3" aria-label="Slide 4"></button>
                </div>
                    <div class="carousel-inner text-center">
                        <div class="carousel-item active">
                            <h6>Colour Team Breakdown</h6>
                            <table>
                                <tr>
                                    <th>
                                        Colour Team
                                    </th>
                                    <th>
                                        Count
                                    </th>
                                </tr>
                                <?php foreach ($colors_count as $color => $count) : ?>
                                <tr>
                                    <td><?php 
                                        if ($color === "") {
                                            echo "Not Assigned";
                                        } else {
                                            echo $color;
                                        }
                                    ?></td>
                                    <td><?php echo $count ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <div class="carousel-item">
                            <h6>Class Breakdown</h6>
                            <table>
                                <tr>
                                    <th>
                                        Class Standing
                                    </th>
                                    <th>
                                        Count
                                    </th>
                                </tr>
                                <?php foreach ($class_count as $class => $count) : ?>
                                    <tr>
                                        <td><?php echo $class; ?></td>
                                        <td><?php echo $count; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <div class="carousel-item">
                            <h6>Programming Breakdown</h6>
                            <table>
                                <tr>
                                    <th>
                                        Programming
                                    </th>
                                    <th>
                                        Count
                                    </th>
                                </tr>
                                <?php foreach ($programming_count as $programming => $count) : ?>
                                    <tr>
                                        <td><?php echo $programming; ?></td>
                                        <td><?php echo $count; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if ($none_count != 0) : ?>
                                    <tr>
                                        <td><?php echo "Not In Any Programming"; ?></td>
                                        <td><?php echo $none_count; ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="carousel-item">
                            <h6>Consistency Score Averages</h6>
                            <table>
                                <tr>
                                    <th>
                                        Score Category
                                    </th>
                                    <th>
                                        Average Consistency Score
                                    </th>
                                </tr>
                            <?php foreach ($total_average as $programming => $average) : ?>
                                <tr>
                                    <td><?php echo $programming; ?></td>
                                    <td><?php echo number_format($average,2) . "%"; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>
        <div class="table-container">
            <table>
                <?php while ($row = $results->fetch_assoc()) : ?>
                    <tr>
                        <?php if ($admin > 0) : ?>
                            <td>
                                <a href="edit_page.php?id=<?php echo $row['id']?>&prev=<?php echo $url?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                            <?php if ($admin == 2) : ?>
                                <td>
                                    <a href="delete_page.php?id=<?php echo $row['id']?>&prev=<?php echo $url?>">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php if ($logged_id == $row['id']) : ?>
                                <td>
                                    <a href="edit_page.php?id=<?php echo $row['id']?>&prev=<?php echo $url?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            <?php else : ?>
                                <td>
                                    <i class="fas fa-edit disabled" data-bs-toggle="tooltip" data-bs-placement="top" title="You cannot edit someone else's info!"></i>
                                </td>
                            <?php endif; ?>
                        <?php endif; ?>
                        <td><?php if ($admin) : ?><a href="user_consistency.php?id=<?php echo $row['id']?>&prev=<?php echo $url?>"><?php endif;?>
                            <?php echo $row['first_name'] ?>
                        <?php if ($admin): ?></a><?php endif;?></td>
                            <td><?php if ($admin) : ?><a href="user_consistency.php?id=<?php echo $row['id']?>&prev=<?php echo $url?>"><?php endif;?>
                            <?php echo $row['tc_name'] ?>
                        <?php if ($admin): ?></a><?php endif;?></td>
                            <td><?php if ($admin) : ?><a href="user_consistency.php?id=<?php echo $row['id']?>&prev=<?php echo $url?>"><?php endif;?>
                            <?php echo $row['last_name'] ?>
                        <?php if ($admin): ?></a><?php endif;?></td>
                        <td><?php 
                            $col_index = $row['colour_id'];
                            if ($col_index != null){
                                echo $colors[$col_index];
                            }
                        ?></td>
                        <td><?php echo $class_array[$row['class_id']] ?></td>
                        <td><?php echo $row['phone'] ?></td>
                        <td><?php echo $row['email'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        
        const tooltipTriggerList = document.querySelectorAll('[title]');
        for (let i = 0; i < tooltipTriggerList.length; i++) {
            new bootstrap.Tooltip(tooltipTriggerList[i]);
        }

        const editButtons = document.querySelectorAll(".disabled");
        editButtons.forEach(function(button) {

            button.addEventListener('mouseover', function() {
                button.style.color = 'darkgray';
            });

            button.addEventListener('mouseout', function() {
                button.style.color = 'white';
            });

        });

    </script>
</body>

</html>
