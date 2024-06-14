<?php
    
    require '../config/config.php';
    
    if (!isset($_SESSION['username'])){
        header("Location: landing_page.php");
    }

    if ($_SESSION['admin'] == 0){
        header("Location: home_page.php");
    }
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Add a new counselor to our database. Complete the required fields to register a new Troy Camp member.">
    <title>Add A Counselor | Troy Camp Counselor Portal</title>
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
            margin-top: 0px;
            margin-bottom: 15px;
            width: 600px;
            height: 800px;
            overflow-y: scroll;
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

        .required-asterisk {
            color: red;
            padding-right: 5px;
        }

        small {
            color: white;
            opacity: 80%;
            font-size: 0.6em;
            position: relative;
            top: -10px;
            text-align: left;
            display: none;
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
            <li class="breadcrumb-item active" aria-current="page">Add Counselor</li>
        </ol>
    </div>

    <div class="container d-flex flex-column align-items-center justify-content-center">
        <h1>Add a Counselor:</h1>
        <form id="add-form" action="add_confirmation.php" method="POST">
            <div class="form-group">
                <label for="usc-id-add">USC ID: <span class="required-asterisk">*</span></label>
                <input id="usc-id-add" name="usc-id-add" type="text" class="form-control">
            </div>
            <small id="id-small"></small>
            <div class="form-group">
                <label for="first-name-add">First Name: <span class="required-asterisk">*</span></label>
                <input id="first-name-add" name="first-name-add" type="text" class="form-control">
            </div>
            <small id="first-name-small"></small>
            <div class="form-group">
                <label for="last-name-add">Last Name: <span class="required-asterisk">*</span></label>
                <input id="last-name-add" name="last-name-add" type="text" class="form-control">
            </div>
            <small id="last-name-small"></small>
            <div class="form-group">
                <label for="tc-name-add">Troy Camp Name:</label>
                <input id="tc-name-add" name="tc-name-add" type="text" class="form-control">
            </div>
            <small id="tc-small"></small>
            <div class="form-group">
                <label for="color-add">Color Team:</label>
                <select id="color-add" name="color-add" class="form-control">
                    <option selected disabled>--Select One--</option>
                    <option value="1">Red</option>
                    <option value="2">Orange</option>
                    <option value="3">Yellow</option>
                    <option value="4">Green</option>
                    <option value="5">Blue</option>
                </select>
            </div>
            <small id="color-small"></small>
            <div class="form-group">
                <label for="class-add">Class Standing: <span class="required-asterisk">*</span></label>
                <select id="class-add" name="class-add" class="form-control">
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
                <label for="phone-add">Phone: <span class="required-asterisk">*</span> </label>
                <input id="phone-add" name="phone-add" type="text" class="form-control">
            </div>
            <small id="phone-small"></small>
            <div class="form-group">
                <label for="email-add">Email: <span class="required-asterisk">*</span> </label>
                <input id="email-add" name="email-add" type="text" class="form-control">
            </div>
            <small id="email-small"></small>
            <div class="text-center">
                <button id="add-button" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../js/validateEmail.js"></script>
    <script src="../js/logout.js"></script>
    <script>

        const form = document.querySelector("#add-form");
        console.log(form);
        form.onsubmit = (event) => {

                let valid = true;

                const id = document.querySelector("#usc-id-add").value.trim();
                let asterisk = document.querySelector("#usc-id-add").parentElement.querySelector(".required-asterisk");
                let errorMsg = document.querySelector("#id-small");

                if (!(/^\d{10}$/).test(id)) {
                    errorMsg.innerHTML = "USC ID must be 10 digits."
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                    asterisk.style.visibility = "hidden";
                }

                const firstName = document.querySelector("#first-name-add").value.trim();
                asterisk = document.querySelector("#first-name-add").parentElement.querySelector(".required-asterisk");
                errorMsg = document.querySelector("#first-name-small");

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

                const lastName = document.querySelector("#last-name-add").value.trim();
                errorMsg = document.querySelector("#last-name-small");
                asterisk = document.querySelector("#last-name-add").parentElement.querySelector(".required-asterisk");

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

                const tcName = document.querySelector("#tc-name-add").value.trim();
                errorMsg = document.querySelector("#tc-small");

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

                const email = document.querySelector("#email-add").value.trim();
                errorMsg = document.querySelector("#email-small");
                asterisk = document.querySelector("#email-add").parentElement.querySelector(".required-asterisk");

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

                const phone = document.querySelector("#phone-add").value.trim();
                errorMsg = document.querySelector("#phone-small");
                asterisk = document.querySelector("#phone-add").parentElement.querySelector(".required-asterisk");

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

                const classStanding = document.querySelector("#class-add").value;
                errorMsg = document.querySelector("#class-small");
                asterisk = document.querySelector("#class-add").parentElement.querySelector(".required-asterisk");

                if (classStanding === "--Select One--"){
                    errorMsg.innerHTML = "You must select your class standing.";
                    errorMsg.style.display = "block";
                    valid = false;
                } else {
                    errorMsg.innerHTML = "";
                    errorMsg.style.display = "none";
                    asterisk.style.visibility = "hidden";
                }

                if (!valid){
                    event.preventDefault();
                }

        }

    </script>
</body>

</html>