<?php

// ============== Includes ==============

// Include CSS.
echo '<link rel="stylesheet" type="text/css" href="css/styles.css"></script>';

// Allow anyone to view the login page.
$secured = false;

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
    $errorMessage = attemptLogin($Database);
}

?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Login</title>

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
            <!-- Error Message -->
            <?php if (isset($errorMessage)) {echo "<div class=\"alert alert-danger\" role=\"alert\">" . $errorMessage . "</div>";}?>
            <!-- Email -->
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
            <!-- Password -->
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="inputPassword" pattern="<?php echo substr($GLOBALS['PASSWORD_VALID'],1,-1);?>" title="<?php echo $GLOBALS['PASSWORD_INVALID_ERROR'];?>" class="form-control" placeholder="Password" required="">
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
function attemptLogin($Database)
{
    // Define variables.
    $inputEmail = "";
    $inputPassword = "";
    $results = [];

    // Collect input from the user.
    if (isset($_POST['inputEmail'])) $inputEmail = trim($_POST['inputEmail']);
    if (isset($_POST['inputPassword'])) $inputPassword = trim($_POST['inputPassword']);

   // Input Regex Checking goes here.
   array_push($results, boolval(filter_var($inputEmail, FILTER_VALIDATE_EMAIL)));
   array_push($results, boolval(preg_match($GLOBALS['PASSWORD_VALID'], $inputPassword)));

   if (in_array(false, $results) === true) # in_array returns TRUE if one or more FALSE values are found inside the array.
   {
       return "Invalid email or password format!"; # Client side gives instant feedback, this is to stop bad clients.
   }


    // Query the DB for that user.    
    $result = $Database->check_credentials($inputEmail, $inputPassword);
    if (!$result) {
        header("Location: login_form.php");
    }

    // If our query was successful...
    $numRows = $result->num_rows;
    if ($numRows > 0) {
        // Get the DB row.
        session_regenerate_id();
        $row = $result->fetch_assoc();
        $userId = $row['id'];
        $fname = $row['fname'];
        $lname = $row['lname'];
        $email = $row['email'];
        $isAdmin = $row['admin'];

        // Set session variables and reroute to the main page.
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userId'] = $userId;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['email'] = $email;
        $_SESSION['isAdmin'] = $isAdmin;

        header("Location: index.php");
    }
    // User input failed, but the query was succesful.
    else
        return "Username or Password is incorrect";
}

?>