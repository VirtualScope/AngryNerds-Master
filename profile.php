<?php

// ============== Includes ==============

// Include CSS.
echo '<link rel="stylesheet" type="text/css" href="css/styles.css"></script>';

// Allow anyone to view the login page.
$secured = true;

// Include Header
include("includes/header.php");

// Include bootstrap library.
include("includes/bootstrap.php");

// DB setup
require("includes/db_config.php");

// ============== Variables ==============


// ============== Operations ==============

// Make sure there is a session started.
if (!isset($_SESSION)) {
    session_start();
}

// If the user has just clicked submit...
if (isset($_POST['submit'])) {
    updateProfile($database);
}

?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Profile</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
</head>

<body class="text-center">
    <div class="container" style="max-width:500px;">
        <form class="form-profile" method="post">
            <br>
            <!-- Image -->
            <img class="mb-4" src="images/ImageNotFound.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Change Email Address</h1>
            <!-- Email -->
            <label for="inputEmail" class="sr-only">New Email address</label>
            <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
            <!-- Password -->
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required="">
            <br>
            <!-- Submit -->
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Update</button>
            <p class="mt-5 mb-3 text-muted">© 2020</p>
        </form>
    </div>

</body>

</html>
<?php
// Include Footer
include("includes/footer.php");
?>

<!-- PHP functions -->
<?php

// Attempt to log in the user
function updateProfile($database)
{
    // Define variables.
    $inputEmail = "";
    $inputPassword = "";
    $userId = $_SESSION['userId'];

    // Collect input from the user.
    if (isset($_POST['inputEmail'])) $inputEmail = trim($_POST['inputEmail']);
    if (isset($_POST['inputPassword'])) $inputPassword = trim($_POST['inputPassword']);

    // Query the DB for that user.    
    $sqlQuery = "UPDATE users SET email='$inputEmail' WHERE id='$userId' AND pass='$inputPassword' ";
    $result = $database->query($sqlQuery);
}

?>