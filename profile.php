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
$firstNameValue = "";
$lastNameValue = "";
$notesValue = "";

// ============== Operations ==============

// Make sure there is a session started.
if (!isset($_SESSION)) {
    session_start();
}

// If the user has just clicked submit...
if (isset($_POST['submit'])) {
    $responseMessage = updateProfile($Database);
}
if (isset($_SESSION) && isset($_SESSION['profileNotesRemember']))     $notesValue    = $_SESSION['profileNotesRemember'];
if (isset($_SESSION) && isset($_SESSION['profileFirstNameRemember'])) $firstNameValue = $_SESSION['profileFirstNameRemember'];
if (isset($_SESSION) && isset($_SESSION['profileLastNameRemember']))  $lastNameValue = $_SESSION['profileLastNameRemember'];

# TODO (Future) Add a message notifying the data was saved from an invalid submission and was not updated.
if ($notesValue === "" || $firstNameValue === "" || $lastNameValue === "") # Sames computation time if one of these 3 is not equal. We can then do the individual checks inside.
{
    $returned = $Database->get_user($_SESSION['userId']);
    $user = $returned->fetch_array();
    $_notes = $user['notes'];
    $_fname = $user['fname'];
    $_lname = $user['lname'];
    
    if ($firstNameValue === "") $firstNameValue = $_fname;
    if ($lastNameValue === "")  $lastNameValue  = $_lname;
    if ($notesValue === "")     $notesValue     = $_notes;
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
        <form class="form-signin" method="post">
            <br>
            <!-- Image -->
            <img class="mb-4" src="images/ProfilePicture.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Update Profile</h1>
            <p>Fill out what you want to change! Leave the rest blank.</p>
            <?php 
            if (isset($responseMessage) && $responseMessage !== "Successfully Updated!") {
                echo "<div class=\"alert alert-danger\" role=\"alert\">" . $responseMessage . "</div>";
            }
            else if (isset($responseMessage) && $responseMessage === "Successfully Updated!")
            {
                echo "<div class=\"alert alert-success\" role=\"alert\">" . $responseMessage . "</div>";
            }
            ?>
            <!-- First Name -->
            <div class="row">
                <div class="col">
                <input type="text" name="inputFirstName" class="form-control" placeholder="First name" pattern="<?php echo substr($GLOBALS['FIRST_NAME_VALID'],1,-1);?>" title="<?php echo $GLOBALS['FIRST_NAME_INVALID_ERROR'];?>"
                    <?php
                        echo "value=" . $firstNameValue;
                    ?>
                >
                </div>
                <div class="col">
                <input type="text" name="inputLastName" class="form-control" placeholder="Last name" pattern="<?php echo substr($GLOBALS['LAST_NAME_VALID'],1,-1);?>" title="<?php echo $GLOBALS['LAST_NAME_INVALID_ERROR'];?>"
                    <?php
                        echo "value=" . $lastNameValue;
                    ?>
                >
                </div>
            </div>
            <br>
            <!-- Email 
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" autofocus="">
            <br>-->
            <!-- Password -->
            <label for="inputNewPassword" class="sr-only">New Password</label>
            <input type="password" id="inputNewPassword" name="inputNewPassword" pattern="<?php echo substr($GLOBALS['PASSWORD_VALID'],1,-1);?>" title="<?php echo $GLOBALS['PASSWORD_INVALID_ERROR'];?>" class="form-control" placeholder="New Password">
            <br>
            <!-- Text Area -->
            <input placeholder="Notes" type="text" class="form-control" id="notes" name="notes" data-error="error" data-pattern-error="error_text" pattern="<?php echo substr($GLOBALS['NOTES_VALID'],1,-1);?>" title="<?php echo $GLOBALS['NOTES_INVALID_ERROR'];?>" value="<?php if (isset($_SESSION) && isset($_SESSION['profileNotesRemember'])) echo $_SESSION['profileNotesRemember']; ?>">
            <!-- Current Password -->
            <br>
            <p>We need additional verification to make sure it's you!</p>
            <label for="inputCurrentPassword" class="sr-only">Current Password</label>
            <input type="password" id="inputCurrentPassword" name="inputCurrentPassword" pattern="<?php echo substr($GLOBALS['PASSWORD_VALID'],1,-1);?>" title="<?php echo $GLOBALS['PASSWORD_INVALID_ERROR'];?>" class="form-control" placeholder="Current Password" required=""> <!--This is required!-->
            <br>
            <!-- Submit -->
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Update</button>
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
function updateProfile($Database)
{
    // Define variables.
    $inputFirstName = "";
    $inputLastName = "";
    $inputCurrentPassword = "";
    $inputNewPassword = "";
    $notes = "";
    $isAdmin = 0;
    $results = [];

    // Collect input from the user.
    if (isset($_POST['inputFirstName'])) $inputFirstName = trim($_POST['inputFirstName']);
    if (isset($_POST['inputLastName'])) $inputLastName = trim($_POST['inputLastName']);
    if (isset($_POST['inputCurrentPassword'])) $inputCurrentPassword = trim($_POST['inputCurrentPassword']);
    if (isset($_POST['notes'])) $notes = trim($_POST['notes']);
    if (isset($_POST['inputNewPassword'])) $inputNewPassword = trim($_POST['inputNewPassword']);

    // Input Regex Checking goes here.
    if ($_POST['inputFirstName'] !== "") array_push($results, boolval(preg_match($GLOBALS["FIRST_NAME_VALID"], $inputFirstName)));
    if ($_POST['inputLastName'] !== "") array_push($results, boolval(preg_match($GLOBALS["LAST_NAME_VALID"], $inputLastName)));
    if ($_POST['notes'] !== "") array_push($results, boolval(preg_match($GLOBALS['NOTES_VALID'], $notes)));
    if ($_POST['inputNewPassword'] !== "") array_push($results, boolval(preg_match($GLOBALS['PASSWORD_VALID'], $inputNewPassword)));
    array_push($results, boolval(preg_match($GLOBALS['PASSWORD_VALID'], $inputCurrentPassword))); # This is required!

    function saveInput($results) 
    {
        $_SESSION["profileFirstNameRemember"] = $_POST['inputFirstName'];
        $_SESSION["profileLastNameRemember"] = $_POST['inputLastName'];
        $_SESSION["profileNotesRemember"] = $_POST['notes'];
    }
    if (in_array(false, $results) === true) # in_array returns TRUE if one or more FALSE values are found inside the array.
    {
        saveInput($results);
        return "Invalid input in one or more fields!"; # Client side gives instant feedback, this is to stop bad clients.
    }

    // Query the DB for that user.    
    $result = $Database->update_user_by_id($_SESSION["userId"], $inputCurrentPassword, $inputFirstName, $inputLastName, $inputNewPassword, $notes);
    if ($result === null) 
        return "Password Incorrect!";
    else if ($result)
    {
        unset($_SESSION['profileFirstNameRemember']);
        unset($_SESSION['profileLastNameRemember']);
        unset($_SESSION["profileNotesRemember"]); // No need to remember the user inputs anymore!
        $_SESSION['fname'] = $inputFirstName;
        $_SESSION['lname'] = $inputLastName;
        return "Successfully Updated!";
    }
    else
    {
        saveInput($results);
        return "An unknown error has occurred";
    }
}