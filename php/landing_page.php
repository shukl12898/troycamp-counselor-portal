<?php

    require '../config/config.php';

    if ( isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true ) {
		header('Location: home_page.php');
        exit();
	} else {

        if ( isset($_POST['action'])){

            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	        if ($mysqli->connect_errno){
		        echo $mysqli->connect_error;
		        exit();
	        }

            $action = $_POST['action'];

            if ($action == 'login'){

                unset($error);
            
                // User is NOT logged in.
                if ( isset($_POST['username-login']) && isset($_POST['password-login']) ) {
                    // The form was submitted.

                    $username = $mysqli->escape_string($_POST['username-login']);
                    $password = $_POST['password-login'];
                    $password_hash = hash('sha256', $password);

                    $sql = "SELECT *
                            FROM users
                            WHERE username = '$username'
                            AND password = '$password_hash';";	
                            
                    $results = $mysqli->query($sql);

                    if (!$results){
                    	echo $mysqli->error;
                    	$mysqli->close();
                    	exit();
                    }

                    if ($results->num_rows > 0){
                        $row = $results->fetch_assoc(); 
                        $id = $row['usc_id'];
                        $admin = $row['admin'];

                        if (empty($username) || empty($password)) {
                            $error = "Please enter username and password.";
                            unset($username, $password);
                        } elseif ($results->num_rows > 0) {
                            // Valid credentials.
                            $_SESSION['logged_in'] = true;
                            $_SESSION['username'] = $username;
                            $_SESSION['usc_id'] = $id;
                            $_SESSION['admin'] = $admin;

                            header('Location: home_page.php');
                            exit();

                        }
                    }

                }

            } else if ($action == 'register'){

                $id = $_POST['usc-id'];
                $first_name = $_POST['first-name-register'];
                $last_name = $_POST['last-name-register'];
                $email = $_POST['email-register'];
                $phone = $_POST['phone-register'];
                $class = $_POST['class-register'];
                $username = $mysqli->escape_string($_POST['username-register']);
                $password = hash('sha256', $_POST['password-register']);
                
                if (isset($_POST['color-register'])){
                    $color = $_POST['color-register'];
                } else {
                    $color = 'NULL';
                }

                if (empty(trim($_POST['tc-name-register']))){
                    $tc_name = null;
                } else {
                    $tc_name = $_POST['tc-name-register'];
                }
                
                $sql_update = "UPDATE counselors 
                               SET 
                                first_name = '$first_name', 
                                last_name = '$last_name', 
                                tc_name = '$tc_name', 
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

                $sql_eboard = "SELECT *
                               FROM eboard
                               WHERE usc_id = $id;";

                $results_eboard = $mysqli->query($sql_eboard);

                if (!$results_eboard){
                    echo $mysqli->error;
                    $mysqli->close();
                    exit();
                }

                if ($results_eboard->num_rows != 0){
                    $admin = 1;
                } else {
                    $admin = 0;
                }
    
                $sql_new_user = "INSERT INTO users (username, password, usc_id, admin)
                                 VALUES ('$username', '$password', $id, $admin);";
    
                $results_insert = $mysqli->query($sql_new_user);
    
                if (!$results_insert){
                    echo $mysqli->error;
                    $mysqli->close();
                    exit();
                }

                }

                $mysqli->close();

            }

        }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Troy Camp Counselor Portal</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Join the Troy Camp counselor community! Log in or create an account to manage tasks and track consistency scores.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        body {
            background-color: #ac3c3c;
        }

        #logo-container {
            margin-top: 200px;
            width: 300px;
        }

        #logo-container img {
            width: 300px;
        }

        #sign-in-form {
            max-width: 500px;
            margin-top: -100px;
        }

        h1 {
            color: white;
            margin-bottom: 30px;
        }

        h6 {
            color: #612222;
        }

        #sign-in-form .form-group {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-bottom: 1rem;
        }

        #sign-in-form label {
            color: white;
            font-size: 1.5em;
            padding-top: 15px;
            width: 150px;
            margin-left: 5px;
        }

        #sign-in-form input[type="text"],
        #sign-in-form input[type="password"] {
            font-size: 1.2em;
            height: auto;
            padding: .5em 1em;
            margin-top: .5em;
        }

        .btn-primary {
            background-color: #612222;
            border: none;
            font-size: 1.3em;
            margin: 10px;
            width: 500px;
        }

        .btn-primary:hover {
            background-color: #800020;
        }

        #register h6 {
            cursor: pointer;
        }

        #sign-in-form {
            margin-top: 10px;
        }

        #register-popup {
            height: 980px;
            width: 600px;
            position: fixed;
            background-color: #FFFAF0;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            visibility: hidden;
            border: black solid 1px;
            box-shadow: 1px 1px 10px black;
        }

        h4 {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .input-group-text {
            background-color: white;
            border: none;
            font-size: 1.3em;
            height: 83%;
            position: relative;
            bottom: -10px;
        }

        #register-popup .form-group {
            display: flex;
        }

        #register-popup label {
            width: 150px;
            padding-top: 6px;
            text-align: right;
        }

        #register-popup .form-control {
            width: 60%;
            margin-left: 5px;
            margin-right: auto;
        }

        #usc-verify {
            background-color: #ac3c3c;
        }

        #usc-verify:hover {
            background-color: #612222;
        }

        #register-form .form-group {
            margin: 15px;
            margin-bottom: 0px;
        }

        #register-form .input-group-text {
            bottom: -0.5px;
            height: 95%;
            background-color: #ececec;
        }

        #register-form .password-popup {
            width: 61%;
        }

        #id-confirmation-msg {
            color: #800020;
            margin-bottom: 30px;
        }

        small {
            color: #800020;
            font-size: 0.6em;
            padding-top: 5px;
            position: relative;
            left: 157px;
            text-align: left;
            display: none;
        }

        #register-form small {
            left: 172px;
        }

        #register-button {
            margin-top: 20px;
        }

        #password-small {
            color: black;
            display: block;
            width: 360px;
        }

        .required-asterisk {
            color: #800020;
            padding-right: 5px;
            visibility: hidden;
        }
    </style>
</head>

<body>

    <div class="container text-center">
        <div class="row d-flex justify-content-center">
            <div id="logo-container">
                <img id="logo" src="../img/logo.png" alt="Troy Camp Logo">
            </div> <!-- #logo-container -->
        </div> <!-- .row -->
        <div class="row text-center">
            <div id="landing-msg">
                <h1>Welcome to Troy Camp's counselor portal!</h1>
            </div>
        </div> <!-- .row -->
    </div> <!-- .container -->
    <div class="container text-center">
        <form id="sign-in-form" class="mx-auto" action="landing_page.php" method="POST">

            <input type="hidden" name="action" value="login">

            <div class="form-group">
                <label for="username-login">Username:</label>
                <input id="username-login" name="username-login" type="text" class="form-control" value="<?php
                    if (isset($username)) {
                        echo $username;
                    }
                ?>">
            </div>
            <div class="form-group">
                <label for="password-login">Password:</label>
                <div class="input-group">
                    <input id="password-login" name="password-login" type="password" class="form-control" value="<?php
                        if (isset($password)) {
                            echo $password;
                        }
                    ?>">
                    <span class="input-group-text">
                        <i class="fa-regular fa-eye togglePasswordIcon"></i>
                    </span>
                </div>
            </div>
            <div class="text-center">
                <?php
                    if (isset($username) && isset($password)) {
                        echo "Invalid credentials.";
                    } else if (!empty($error)){
                        echo $error;
                    }
                ?>
                <button class="btn btn-primary">Sign In</button>
            </div>
            <div>
                <h6 id="popup-button">Don't have an account yet? Click here to register.</h6>
            </div>
        </form>
    </div> <!-- .container -->
    <div id="register-popup" class="text-center">
        <h4>Confirm that you are a TC Counselor:</h4>
        <form id="id-form">
            <div class="form-group">
                <label for="usc-id">USC ID:</label>
                <input id="usc-id" name="usc-id" type="text" class="form-control">
            </div>
            <small id="usc-id-small"></small>
            <div class="text-center">
                <button id="usc-verify" class="btn btn-primary">Verify</button>
            </div>
        </form>
        <h5 id="id-confirmation-msg">You cannot register until you have confirmed your USC ID.</h5>
        <form id="register-form" action="landing_page.php" method="POST">
            
            <input type="hidden" name="action" value="register">
            <input type="hidden" id="usc-id-hidden" name="usc-id" value="">

            <div class="form-group">
                <label for="first-name-register"><span class="required-asterisk">*</span>First Name:</label>
                <input id="first-name-register" name="first-name-register" type="text" class="form-control" disabled>
            </div>
            <small id="first-name-small"></small>
            <div class="form-group">
                <label for="last-name-register"><span class="required-asterisk">*</span>Last Name:</label>
                <input id="last-name-register" name="last-name-register" type="text" class="form-control" disabled>
            </div>
            <small id="last-name-small"></small>
            <div class="form-group">
                <label for="tc-name-register">Troy Camp Name:</label>
                <input id="tc-name-register" name="tc-name-register" type="text" class="form-control" disabled>
            </div>
            <div class="form-group">
                <label for="color-register">Color Team:</label>
                <select id="color-register" name="color-register" class="form-control" disabled>
                    <option selected disabled>--Select One--</option>
                    <option value="1">Red</option>
                    <option value="2">Orange</option>
                    <option value="3">Yellow</option>
                    <option value="4">Green</option>
                    <option value="5">Blue</option>
                </select>
            </div>
            <small id="tc-name-small"></small>
            <div class="form-group">
                <label for="email-register"><span class="required-asterisk">*</span>Email:</label>
                <input id="email-register" name="email-register" type="text" class="form-control" disabled>
            </div>
            <small id="email-small"></small>
            <div class="form-group">
                <label for="phone-register"><span class="required-asterisk">*</span>Phone:</label>
                <input id="phone-register" name="phone-register" type="text" class="form-control" disabled>
            </div>
            <small id="phone-small"></small>
            <div class="form-group">
                <label for="class-register"><span class="required-asterisk">*</span>Class Standing:</label>
                <select id="class-register" name="class-register" class="form-control" disabled>
                    <option selected disabled>--Select One--</option>
                    <option value="1">Freshman</option>
                    <option value="2">Sophomore</option>
                    <option value="3">Junior</option>
                    <option value="4">Senior</option>
                    <option value="5">Super Senior</option>
                </select>
            </div>
            <small id="class-small"></small>
            <div class="form-group">
                <label for="username-register"><span class="required-asterisk">*</span>Username:</label>
                <input id="username-register" name="username-register" type="text" class="form-control" disabled>
            </div>
            <small id="username-small">
                <?php 
                    if (isset($username_error)) {
                        echo $username_error;
                    } else {
                        echo "";
                    }
                ?>
            </small>
            <div class="form-group">
                <label for="password-register"><span class="required-asterisk">*</span>Password:</label>
                <div class="input-group password-popup">
                    <input id="password-register" name="password-register" type="password" class="form-control"
                        disabled>
                    <span class="input-group-text">
                        <i class="fa-regular fa-eye togglePasswordIcon"></i>
                    </span>
                </div>
            </div>
            <small id="password-small">Password must be 8 characters minimum, with at least 1 uppercase, 1 number and 1
                special character.</small>
            <small id="password-small-error"></small>
            <div class="form-group">
                <label for="password-confirm"><span class="required-asterisk">*</span>Confirm Password:</label>
                <div class="input-group password-popup">
                    <input id="password-confirm" name="password-confirm" type="password" class="form-control" disabled>
                    <span class="input-group-text">
                        <i class="fa-regular fa-eye togglePasswordIcon"></i>
                    </span>
                </div>
            </div>
            <small id="password-confirm-small"></small>
            <div class="text-center">
                <button id="register-button" class="btn btn-primary">Register</button>
                <small id="register-small">
                </small>
            </div>
        </form>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../js/validateEmail.js"></script>
    <script>

        function togglePassword() {
            const passwords = document.querySelectorAll(".input-group");
            for (pw of passwords) {
                const icon = pw.querySelector(".fa-eye");
                icon.addEventListener('click', function (event) {
                    const input = event.target.parentElement.parentElement.querySelector("input");
                    if (input.disabled === false) {
                        if (icon.classList.contains("fa-regular")) {
                            icon.classList.replace("fa-regular", "fa-solid");
                            input.type = "text";

                        } else {
                            icon.classList.replace("fa-solid", "fa-regular");
                            input.type = "password";
                        }
                    }
                });
            }
        }

        togglePassword();

        document.querySelector("body").addEventListener('click', function (event) {
            let popup = document.querySelector("#register-popup");
            if (event.target.id === "popup-button") {
                popup.style.visibility = 'visible';
                const required = document.querySelectorAll(".required-asterisk");
                for (asterisk of required) {
                    asterisk.style.visibility = "visible";
                }
            }
            else if (!popup.contains(event.target) && event.target.id !== "popup-button") {
                popup.style.visibility = 'hidden';
                const required = document.querySelectorAll(".required-asterisk");
                for (asterisk of required) {
                    asterisk.style.visibility = "hidden";
                }
            }
        });

        document.querySelector("#id-form").onsubmit = function () {
            return false;
        }

        const verifyBtn = document.querySelector("#usc-verify");
        verifyBtn.onclick = () => {

            const id = document.querySelector("#usc-id").value.trim();
            const errorMsg = document.querySelector("#usc-id-small");
            const confirmMsg = document.querySelector("#id-confirmation-msg");

            let valid = true;

            if (id.length != 10) {
                errorMsg.innerHTML = "Your id must have 10 digits."
                errorMsg.style.display = "block";
                valid = false;
            } else if (/\d{10}/.test(id) == false) {
                errorMsg.innerHTML = "Your id can only contain numbers."
                errorMsg.style.display = "block";
                valid = false;
            }

            if (valid) {
                
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "verify_id.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE){
                        if (xhr.status === 200){
                            const response = xhr.responseText;
                            if (response === "valid"){
                                errorMsg.innerHTML = "";
                                document.querySelector("#usc-id").disabled = "true";
                                confirmMsg.innerHTML = "Thank you for confirming your USC ID!"
                                confirmMsg.style.color = "#00A896"
                                const inputs = document.querySelector("#register-form").querySelectorAll("input");
                                const selects = document.querySelector("#register-form").querySelectorAll("select");
                                for (input of inputs) {
                                    input.disabled = false;
                                }
                                for (select of selects){
                                    select.disabled = false;
                                }
                                let visible = document.querySelector("#register-form").querySelectorAll(".input-group-text");
                                for (eye of visible) {
                                    eye.style.backgroundColor = "white";
                                }
                                document.querySelector("#usc-id-hidden").value = id;
                            } else {
                                confirmMsg.innerHTML = "This portal is only for Troy Camp Counselors!"
                            }
                        } else {
                            console.error('Error:', xhr.statusText);
                        }
                    }
                }
                xhr.send("usc_id=" + id);
            }
        }

        const registerBtn = document.querySelector("#register-button");
        document.querySelector("#register-form").onsubmit = function(event) {

            event.preventDefault();

            if (document.querySelector("#first-name-register").disabled == false) {

                let valid = true;

                const firstName = document.querySelector("#first-name-register").value.trim();
                let asterisk = document.querySelector("#first-name-register").parentElement.querySelector(".required-asterisk");
                let errorMsg = document.querySelector("#first-name-small");

                if (firstName.length == 0) {
                    errorMsg.innerHTML = "First name cannot be empty."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (/^[A-Z][a-z]+$/.test(firstName) == false) {
                    errorMsg.innerHTML = "First name must start with a capital and only contain letters."
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                    asterisk.style.visibility = "hidden";
                }

                const lastName = document.querySelector("#last-name-register").value.trim();
                errorMsg = document.querySelector("#last-name-small");
                asterisk = document.querySelector("#last-name-register").parentElement.querySelector(".required-asterisk");

                if (lastName.length == 0) {
                    errorMsg.innerHTML = "Last name cannot be empty."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (/^[A-Z][a-z]+$/.test(lastName) == false) {
                    errorMsg.innerHTML = "Last name must start with a capital and only contain letters."
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                    asterisk.style.visibility = "hidden";
                }

                const tcName = document.querySelector("#tc-name-register").value.trim();
                errorMsg = document.querySelector("#tc-name-small");

                const words = tcName.split(/[\s-]+/);

                if (tcName.length == 0) {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                } else if (words.every(word => /^[A-Z0-9]/.test(word)) == false) {
                    errorMsg.innerHTML = "Every word in TC name must start with a capital."
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                }

                const email = document.querySelector("#email-register").value.trim();
                errorMsg = document.querySelector("#email-small");
                asterisk = document.querySelector("#email-register").parentElement.querySelector(".required-asterisk");

                if (email.length == 0) {
                    errorMsg.innerHTML = "Email cannot be empty."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (isValidEmail(email) == false) {
                    errorMsg.innerHTML = "Email must be a valid USC email."
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                    asterisk.style.visibility = "hidden";
                }

                const phone = document.querySelector("#phone-register").value.trim();
                errorMsg = document.querySelector("#phone-small");
                asterisk = document.querySelector("#phone-register").parentElement.querySelector(".required-asterisk");

                if (phone.length == 0) {
                    errorMsg.innerHTML = "Phone number cannot be empty."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (/(\d{3}-\d{3}-\d{4})/.test(phone) == false) {
                    errorMsg.innerHTML = "Phone number must be in format xxx-xxx-xxxx."
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                    asterisk.style.visibility = "hidden";
                }

                const classStanding = document.querySelector("#class-register").value;
                errorMsg = document.querySelector("#class-small");
                asterisk = document.querySelector("#class-register").parentElement.querySelector(".required-asterisk");

                if (classStanding === "--Select One--"){
                    errorMsg.innerHTML = "You must select your class standing.";
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                    asterisk.style.visibility = "hidden";
                }

                const username = document.querySelector("#username-register").value.trim();
                errorMsg = document.querySelector("#username-small");
                asterisk = document.querySelector("#username-register").parentElement.querySelector(".required-asterisk");

                if (username.length == 0) {
                    errorMsg.innerHTML = "Username cannot be empty."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (/^[a-zA-Z]/.test(username) == false) {
                    errorMsg.innerHTML = "Username must start with a letter."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (/[a-zA-Z0-9]$/.test(username) == false) {
                    errorMsg.innerHTML = "Username must end with a letter or number."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (/\.{2,}/.test(username)) {
                    errorMsg.innerHTML = "Username cannot contain two consecutive periods."
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                    asterisk.style.visibility = "hidden";
                }

                const password = document.querySelector("#password-register").value.trim();
                errorMsg = document.querySelector("#password-small-error");
                asterisk = document.querySelector("#password-register").parentElement.previousElementSibling.querySelector(".required-asterisk");

                if (password.length == 0) {
                    errorMsg.innerHTML = "Password cannot be empty."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (/^.{8,}$/.test(password) == false) {
                    errorMsg.innerHTML = "Username must contain at least 8 characters."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (/[A-Z]/.test(password) == false) {
                    errorMsg.innerHTML = "Password must contain at least one uppercase letter."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (/[0-9]/.test(password) == false) {
                    errorMsg.innerHTML = "Password must contain at least one number."
                    errorMsg.style.display = "block";
                    valid = false;
                } else if (/[!@#$%^&*()]/.test(password) == false) {
                    errorMsg.innerHTML = "Password must contain at least one special character."
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                    asterisk.style.visibility = "hidden";
                }

                const passwordConfirm = document.querySelector("#password-confirm").value.trim();
                errorMsg = document.querySelector("#password-confirm-small");
                asterisk = document.querySelector("#password-register").parentElement.previousElementSibling.querySelector(".required-asterisk");

                if (password !== passwordConfirm) {
                    errorMsg.innerHTML = "Your password and confirmation password must match."
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                    asterisk.style.visibility = "hidden";
                }

                const usernameSmall = document.querySelector("#username-small");
                console.log(usernameSmall);
                const registerSmall = document.querySelector("#register-small");
                console.log(registerSmall);

                if (usernameSmall.length != 0 || registerSmall.length != 0){
                    usernameSmall.style.display = "block";
                    registerSmall.style.display = "block";
                }

                if (valid) {

                    const id = document.querySelector("#usc-id").value;

                    const xhrCheck = new XMLHttpRequest();
                    xhrCheck.open("POST", "existing.php", true);
                    xhrCheck.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhrCheck.onreadystatechange = function() {
                        if (xhrCheck.readyState === XMLHttpRequest.DONE){
                            if (xhrCheck.status === 200){
                                const response = xhrCheck.responseText;
                                console.log(response);
                                if (response === "This username is already taken! Try again."){
                                    document.querySelector("#username-small").innerHTML = response;
                                    return false;
                                } else if (response === "You already have an account! Try logging in!") {
                                    document.querySelector("#register-small").innerHTML = response;
                                    return false;
                                } else {
                                    document.querySelector("#register-form").submit();
                                }

                            } else {
                                console.error('Error:', xhrCheck.statusText);
                            }
                        }
                    }
                    xhrCheck.send("usc_id=" + id + "&username=" + username);

                    document.querySelector("#username-login").value = username;
                    document.querySelector("#password-login").value = password;

                } else {
                    return false;
                }

            }
        }

    </script>
</body>

</html>