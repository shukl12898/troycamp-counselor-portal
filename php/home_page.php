<?php
    
    require '../config/config.php';

    if (!isset($_SESSION['username'])){
        header("Location: landing_page.php");
    }

    $user = $_SESSION['username'];
    $id = $_SESSION['usc_id'];

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if ($mysqli->connect_errno){
		echo $mysqli->connect_error;
		exit();
    }
    
    $sql = "SELECT total, tc_name
            FROM counselors
            WHERE id = $id;";

    $result = $mysqli->query($sql);

    if (!$result){
        echo $mysqli->error;
        $mysqli->close();
        exit();
    }

    $row = $result->fetch_assoc();
    $score = $row['total'] . '%';
    $name = $row['tc_name'];

    date_default_timezone_set('America/Los_Angeles');
    $hour = date('H');

    if ($hour >= 6 && $hour < 12) {
        $greeting = "Morning";
    } elseif ($hour >= 12 && $hour < 17) {
        $greeting = "Afternoon";
    } else {
        $greeting = "Evening";
    }

    $mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home | Troy Camp Counselor Portal</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Access your to-do list, overall consistency score, daily weather updates, and a motivational quote on your home dashboard.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/shared.css">
    <link rel="stylesheet" href="../css/nav.css">
    <style>
        .fa-house {
            color: #fff;
        }

        #home-under {
            background-color: #fff;
        }

        .progress {
            width: 650px;
            /*Change this to change height*/
            height: 650px;
            /*Change this to change height*/
            line-height: 650px;
            /*Change this to change height*/
            background: none;
            position: relative;
            border-radius: 50%;
            border: 5px solid;
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
            border-width: 10px;
            border-style: solid;
            position: absolute;
            top: 0;
        }

        .progress .progress-left .progress-bar {
            left: 100%;
            border-top-right-radius: 325px;
            /*Change this to change height*/
            border-bottom-right-radius: 325px;
            /*Change this to change height*/
            border-left: 0;
            transform-origin: center left;
            transition: transform 1s linear;
        }

        .progress .progress-right {
            right: 0;
        }

        .progress .progress-right .progress-bar {
            left: -100%;
            border-top-left-radius: 325px;
            /*Change this to change height*/
            border-bottom-left-radius: 325px;
            /*Change this to change height*/
            border-right: 0;
            transform-origin: center right;
            transition: transform 1s linear;
        }

        .progress .progress-value {
            width: 86%;
            height: 86%;
            border-radius: 50%;
            border: 5px dotted white;
            font-size: 160px;
            text-align: center;
            position: absolute;
            top: 7.5%;
            left: 7.5%;
        }

        #consistency-score {
            position: absolute;
            top: 43%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        #progress-value-text {
            position: absolute;
            top: 120%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 27px;
        }

        .progress .progress-bar {
            border-color: black;
        }

        .progress .progress-value {
            color: black;
        }

        .progress-border-hover .progress,
        .progress-border-hover .progress-value {
            border-color: black;
        }

        .progress:hover,
        .progress:hover .progress-value {
            cursor: pointer;
        }

        #quote {
            opacity: 50%;
            color: white;
        }

        h5 {
            opacity: 50%;
            text-align: right;
            font-size: 0.8em;
            position: relative;
            top: -10px;
        }

        #programming {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            border: 2px dotted white;
            border-radius: 15px;
            height: 550px;
            width: 550px;
            text-align: left;
            overflow-y: scroll;
        }

        #content {
            left: 20px;
        }

        .greeting-container {
            display: flex;
            justify-content: center;
            /* Center items horizontally */
            align-items: center;
            /* Align items vertically */
        }

        #weather img {
            height: 100px;
            position: relative;
            top: -10px;
            display: inline-block;
            filter: brightness(0) invert(1);
        }

        #programming h2 {
            margin-top: 15px;
            color: white;
        }

        .custom-checkbox .custom-control-input {
            transform: scale(3);
            margin: 20px;
            margin-left: 50px;
        }

        .task {
            display: flex;
            color: white;
        }

        .task h3 {
            position: relative;
            top: 10px;
            margin-left: 8px;
        }

        h1 {
            color: white;
        }
        
        .todo-header {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border-bottom: 2px dotted white;
        }

        .todo-header h2 {
            margin-right: 10px;
        }

        #list {
            padding-top: 15px;
        }

        .icons {
            display: flex;
            font-size: 2em;
            margin-left: 60px;
        }

        .icons i {
            padding: 5px;
            cursor: pointer;
            color: white;
        }

        .icons .fa-refresh:hover {
            color: blue;
        }

        .fa-xmark:hover {
            color: red;
        }

        .task {
            display: flex;
        }

        .task .fa-xmark {
            cursor: pointer;
            margin-left: auto;       
            position: relative;
            left: -15px;   
            top: 18px;  
        }

    </style>
</head>

<body>

    <?php
        include '../html/nav.html';
    ?>

    <div class="container-fluid" id="main">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-5 d-flex flex-column text-center align-items-center justify-content-center">
                <div class="greeting-container d-flex align-items-center justify-content-center">
                    <!-- Container for greeting and doodle -->
                    <h1>Good <?php echo $greeting?>, <?php echo $name?>!</h1>
                    <div id="weather">
                        <img id="doodle" src="../img/logo.png" alt="Weather Condition">
                    </div>
                </div>
                <h3 id="quote"><em>Loading quote...</em>
                </h3>
                <div id="programming">
                    <div class="todo-header">
                        <h2>Programming To-Do List</h2>
                        <div class="icons">
                            <i class="fa fa-refresh" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset List" aria-hidden="true"></i>
                            <i class="fa-solid fa-xmark" data-bs-toggle="tooltip" data-bs-placement="top" title="Clear All Tasks" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div id="list">
                    </div>
                </div>
            </div>
            <div class="col-5 d-flex justify-content-end align-items-center">
                <a href="consistency_score_page.php">
                    <div class="progress">
                        <span class="progress-left">
                            <span class="progress-bar"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar"></span>
                        </span>
                        <div class="progress-value">
                            <div id="consistency-score"><?php echo $score?></div>
                            <div id="progress-value-text">Total Consistency Score</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-1"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            weatherDoodle();
            setQuote();            
            
            if (!loadTasksFromLocalStorage()){
                generateTodoList();
            }

            bindCheckButtons();
            bindDeleteButtons();

            consistencyDisplay();

            const tooltipTriggerList = document.querySelectorAll('[title]');
            for (let i = 0; i < tooltipTriggerList.length; i++) {
                new bootstrap.Tooltip(tooltipTriggerList[i]);
            }

        });

        function setQuote() {
            const quote = document.querySelector("#quote");
            const today = new Date().toDateString();

            const storedQuote = localStorage.getItem('dailyQuote');
            const storedDate = localStorage.getItem('quoteDate');

            if (storedQuote && storedDate == today){
                quote.textContent = storedQuote;
            } else {
                fetch('https://api.quotable.io/random')
                .then(response => response.json())
                .then(data => {
                    const string = `${data.content} — ${data.author}`;
                    quote.textContent = string;
                    localStorage.setItem('dailyQuote', string);
                    localStorage.setItem('quoteDate', today);
                });
            }
            
        }

        function weatherDoodle() {
            const apiUrl = `http://api.weatherapi.com/v1/current.json?key=ef9738eaa801484f86f85459240105&q=90007&aqi=no`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    // Extract weather condition (e.g., 'Rain', 'Clear', 'Cloudy')
                    img = 'https:' + data.current.condition.icon;
                    document.querySelector("#doodle").src = img;
                    document.querySelector("#doodle").alt = data.current.condition.text;
                })
                .catch(error => {
                    console.error('Error fetching weather data:', error);
                });
        }

        function consistencyDisplay() {
            const progressBarLeft = document.querySelector(".progress .progress-left .progress-bar");
            const progressBarRight = document.querySelector(".progress .progress-right .progress-bar");
            const progressValue = document.querySelector(".progress-value");
            const progressContainer = document.querySelector(".progress");
            const valString = document.querySelector("#consistency-score").innerHTML;
            const valFloat = parseFloat(valString.replace('%', ''));
            const valRounded = Math.round(valFloat);

            setProgressColors(valRounded);

            progressContainer.addEventListener('mouseenter', function () {
                progressContainer.style.borderColor = 'white';
                progressValue.style.color = 'white';
                progressValue.style.borderColor = 'white';
                progressBarLeft.style.borderColor = progressBarRight.style.borderColor = 'white';
                progressValue.style.fontSize = '180px';
            });

            progressContainer.addEventListener('mouseleave', function () {
                setProgressColors(valRounded);
                progressValue.style.fontSize = '160px';
            });

            function setProgressColors(value) {
                let color;
                if (value < 50) {
                    color = "#ac3c3c";
                } else if (value >= 50 && value <= 60) {
                    color = "#DAA520";
                } else {
                    color = "#009A44";
                }

                progressBarLeft.style.borderColor = progressContainer.style.borderColor = progressBarRight.style.borderColor = progressValue.style.color = color;
            }

            let leftDeg = 0;
            let rightDeg = 0;

            if (valRounded < 50) {
                rightDeg = Math.round((valRounded * 180) / 50);
            } else {
                rightDeg = 180;
                leftDeg = Math.round(((valRounded - 50) * 180) / 50);
            }

            progressBarRight.style.transform = `rotate(${rightDeg}deg)`;
            setTimeout(() => {
                progressBarLeft.style.transform = `rotate(${leftDeg}deg)`;; 
            }, 970); 
        }

        function addTaskToDOM(taskName, checked) {
            const programmingDiv = document.getElementById("list");

            const taskDiv = document.createElement("div");
            taskDiv.classList.add("task");

            const checkboxDiv = document.createElement("div");
            checkboxDiv.classList.add("custom-control", "custom-checkbox", "mr-2");
            
            const checkboxInput = document.createElement("input");
            checkboxInput.type = "checkbox";
            checkboxInput.classList.add("custom-control-input");
            checkboxInput.id = taskName;
            
            const checkboxLabel = document.createElement("label");
            checkboxLabel.classList.add("custom-control-label");
            checkboxLabel.setAttribute("for", taskName);
            
            checkboxDiv.appendChild(checkboxInput);
            checkboxDiv.appendChild(checkboxLabel);

            const taskNameElement = document.createElement("h3");
            taskNameElement.textContent = taskName;

            const deleteIcon = document.createElement("i");
            deleteIcon.classList.add("fa", "fa-solid", "fa-xmark");
            deleteIcon.onclick = () => {
                taskDiv.remove();
                updateLocalStorage();
            };

            taskDiv.appendChild(checkboxDiv);
            taskDiv.appendChild(taskNameElement);
            taskDiv.appendChild(deleteIcon);

            if (checked) {
                checkboxInput.checked = true;
                taskNameElement.style.textDecoration = "line-through";
                taskNameElement.style.opacity = "50%";
            }

            programmingDiv.appendChild(taskDiv);
            updateLocalStorage();
            bindCheckButtons();
        }

        const username = '<?php echo $_SESSION["username"]; ?>';

        function bindCheckButtons() {
            const checkBtns = document.querySelectorAll(".custom-control-input");
            for (let i = 0; i < checkBtns.length; i++){
				checkBtns[i].addEventListener('change', function() {
					
                    const text = this.parentElement.parentElement.querySelector("h3");

                    if (this.checked){
                        text.style.textDecoration = "line-through";
                        text.style.opacity = "50%";
                    } else {
                        text.style.textDecoration = "none";
                        text.style.opacity = "100%";
                    }

                    updateLocalStorage();
                    
				});
			}
        }

        function loadTasksFromLocalStorage() {
            const storedTasks = localStorage.getItem(username + '_todoTasks');
            if (storedTasks) {
                const tasks = JSON.parse(storedTasks);
                tasks.forEach(task => {
                    addTaskToDOM(task.name, task.checked);
                });
                return true; 
            } else {
                return false; 
            }
        }

        function updateLocalStorage() {
            const tasks = [];
            const taskElements = document.querySelectorAll('.task');
            taskElements.forEach(taskElement => {
                const taskName = taskElement.querySelector('h3').textContent;
                const isChecked = taskElement.querySelector('.custom-control-input').checked;
                tasks.push({ name: taskName, checked: isChecked });
            });
            localStorage.removeItem(username + '_todoTasks');
            localStorage.setItem(username + '_todoTasks', JSON.stringify(tasks));
        }

        function bindDeleteButtons() {
            const tasks = document.querySelectorAll(".task");
            tasks.forEach(task => {
                const deleteButton = task.querySelector(".fa-xmark");
                deleteButton.onclick = () => {
                    const text = task.querySelector("h3");
                    const check = task.querySelector(".custom-control-input");
                    text.remove();
                    check.remove();
                    deleteButton.remove();
                    task.remove();

                    updateLocalStorage();
                }
            });
        }

        const originalTasks = [
            "SP Calling",
            "Elementary School Programming",
            "General Meeting",
            "High School Programming",
            "LIT Calling",
            "Middle School Programming",
            "Yearlong Calling",
            "Yearlong"
        ];

        function generateTodoList() {
            const programmingDiv = document.getElementById("list");
            programmingDiv.innerHTML = "";

            originalTasks.forEach(task => {
                addTaskToDOM(task, false);
            });
        }

        document.querySelector(".fa-refresh").onclick = () => {
            generateTodoList();
		}

        document.querySelector(".fa-xmark").onclick = () => {
            const checkBtns = document.querySelectorAll(".custom-control-input");
            for (let i = 0; i < checkBtns.length; i++){					
                const text = checkBtns[i].parentElement.parentElement.querySelector("h3");
                const cross = checkBtns[i].parentElement.parentElement.querySelector(".fa-xmark");
                text.remove();
                checkBtns[i].remove();
                cross.remove();
			};
            localStorage.setItem('todoTasks', JSON.stringify([]));
		}


    </script>
</body>

</html>