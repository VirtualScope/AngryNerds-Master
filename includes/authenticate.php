<?php

// Fire up a session
if (!isset($_SESSION)) {
    session_start();
}

// Assume all pages require login.
$secured = true;
$isLoggedIn = isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == 'true';
if ($secured && $isLoggedIn == false) {
    // Call the function validate_credentials($secured).
    validate_credentials(true);
}

function validate_credentials($secured)
{
    if ($secured == 'true') {
        if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] != 'true') {
            // Redirect the page to the login.php page.
            header("Location: login_form.php");

            exit;
        }
    }
}
