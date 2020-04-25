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
    $errorMessage = signUp($Database);
}

?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Create Account</title>

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
            <img class="mb-4" src="images/ProfilePicture.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Create an account</h1>
            <?php if (isset($errorMessage)) {echo "<div class=\"alert alert-danger\" role=\"alert\">" . $errorMessage . "</div>";}?>
            <!-- First Name -->
            <div class="row">
                <div class="col">
                <input type="text" name="inputFirstName" class="form-control" placeholder="First name" required pattern="<?php echo substr($GLOBALS['FIRST_NAME_VALID'],1,-1);?>" title="<?php echo $GLOBALS['FIRST_NAME_INVALID_ERROR'];?>"
                    <?php
                        if (isset($_SESSION) && isset($_SESSION['firstNameRemember']))
                        {
                            echo "value=" . $_SESSION['firstNameRemember'];
                        }
                    ?>
                >
                </div>
                <div class="col">
                <input type="text" name="inputLastName" class="form-control" placeholder="Last name" required pattern="<?php echo substr($GLOBALS['LAST_NAME_VALID'],1,-1);?>" title="<?php echo $GLOBALS['LAST_NAME_INVALID_ERROR'];?>"
                    <?php
                        if (isset($_SESSION) && isset($_SESSION['lastNameRemember']))
                        {
                            echo "value=" . $_SESSION['lastNameRemember'];
                        }
                    ?>
                >
                </div>
            </div>
            <br>
            <!-- Email -->
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
            <br>
            <!-- Password -->
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="inputPassword" required pattern="<?php echo substr($GLOBALS['PASSWORD_VALID'],1,-1);?>" title="<?php echo $GLOBALS['PASSWORD_INVALID_ERROR'];?>" class="form-control" placeholder="Password" required="">
            <br>
            <!-- Text Area -->
            <label for="notes">About Me</label>
            <textarea class="form-control" id="notes" name="notes" rows="3" required pattern="<?php echo substr($GLOBALS['NOTES_VALID'],1,-1);?>" title="<?php echo $GLOBALS['NOTES_INVALID_ERROR'];?>"><?php if (isset($_SESSION) && isset($_SESSION['notesRemember'])) echo $_SESSION['notesRemember']; ?></textarea>
            <br>
            <!-- Submit -->
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Sign Up</button>
            <!--<p class="mt-5 mb-3 text-muted">© 2020</p>-->
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

// Create an account
function signUp($Database)
{
    // Define variables.
    $inputFirstName = "";
    $inputLastName = "";
    $inputEmail = "";
    $inputPassword = "";
    $notes = "";
    $isAdmin = 0;
    $results = [];

    // Collect input from the user.
    if (isset($_POST['inputFirstName'])) $inputFirstName = trim($_POST['inputFirstName']);
    if (isset($_POST['inputLastName'])) $inputLastName = trim($_POST['inputLastName']);
    if (isset($_POST['inputEmail'])) $inputEmail = trim($_POST['inputEmail']);
    if (isset($_POST['inputPassword'])) $inputPassword = trim($_POST['inputPassword']);
    if (isset($_POST['notes'])) $notes = trim($_POST['notes']);

    // Input Regex Checking goes here.
    array_push($results, boolval(preg_match($GLOBALS["FIRST_NAME_VALID"], $inputFirstName)));
    array_push($results, boolval(preg_match($GLOBALS["LAST_NAME_VALID"], $inputLastName)));
    array_push($results, boolval(filter_var($inputEmail, FILTER_VALIDATE_EMAIL)));
    array_push($results, boolval(preg_match($GLOBALS['PASSWORD_VALID'], $inputPassword)));
    array_push($results, boolval(preg_match($GLOBALS['NOTES_VALID'], $notes)));

    function saveInput($results) 
    {
        if ($results[0] === true) // Only remember the input IF it was valid!
        {
            $_SESSION["firstNameRemember"] = $_POST['inputFirstName'];
        }
        if ($results[1] === true)
        {
            $_SESSION["lastNameRemember"] = $_POST['inputLastName'];
        }
        if ($results[4] === true) // If the notes area is valid, remember it. This might same some pain for someone in the future...
        {
            $_SESSION["notesRemember"] = $_POST['notes'];
        }
    }
    if (in_array(false, $results) === true) # in_array returns TRUE if one or more FALSE values are found inside the array.
    {
        saveInput($results);
        return "Invalid input in one or more fields!"; # Client side gives instant feedback, this is to stop bad clients.
    }

    // Query the DB for that user.    
    $result = $Database->count_users_by_identity($inputEmail);
    if ($result->num_rows === 0) {
        $Database->create_user($inputFirstName, $inputLastName, $inputEmail, $inputPassword, $isAdmin, $notes);
        $_SESSION["account_creation"] = "success";
        unset($_SESSION['firstNameRemember']);
        unset($_SESSION['lastNameRemember']);
        unset($_SESSION["notesRemember"]); // No need to remember the user inputs anymore!
        header("Location: login_form.php");

    // User input failed, but the query was succesful.
    }
    else
    {
        saveInput($results);
        return "User already exists";
    }
}

?>