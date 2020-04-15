<?php //deletes all the values in thesession variables https://www.php.net/manual/en/function.session-destroy.php
session_start();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_unset();
session_destroy();

header("Location: ../login_form.php");?>