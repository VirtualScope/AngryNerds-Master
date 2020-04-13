<?php

// ============== Includes ==============

// Include CSS.
echo '<link rel="stylesheet" type="text/css" href="css/styles.css"></script>';

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
    attemptLogin();
}

?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Signin Template for Bootstrap</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
</head>

<body class="text-center">
    <div class="container" style="max-width:500px;">
        <form class="form-signin" method="post">
            <br>
            <!-- Image -->
            <img class="mb-4" src="images/ImageNotFound.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
            <!-- Email -->
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
            <!-- Password -->
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required="">
            <br>
            <!-- Submit -->
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Sign in</button>
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
function attemptLogin()
{
    // Define variables.
    $inputEmail = "";
    $inputPassword = "";

    // Collect input from the user.
    if (isset($_POST['inputEmail'])) $inputEmail = trim($_POST['inputEmail']);
    if (isset($_POST['inputPassword'])) $inputPassword = trim($_POST['inputPassword']);

    // Connect to DB
    $dbcn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
    $dbcn->set_charset("utf8");
    if (mysqli_connect_errno()) {
        echo "<p>Error creating database connection.</p>";
        exit;
    }

    // Query the DB for that user.    
    $sqlQuery = "SELECT * FROM users WHERE email='$inputEmail' AND pass='$inputPassword'";
    $result = $dbcn->query($sqlQuery);
    if (!$result) {
        header("Location: login_form.php");
    }

    // If our query was successful...
    $numRows = $result->num_rows;
    if ($numRows > 0) {
        // Get the DB row.
        $row = $result->fetch_assoc();
        $userId = $row['id'];

        // Set session variables and reroute to the main page.
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userId'] = $userId;
        header("Location: index.php");
    }
    // User input failed, but the query was succesful.
    else
        $errorMessage = "User name or password is incorrect";
}

?>