<?php
$GLOBALS['FIRST_NAME_VALID'] = "/^[A-Z][A-z]+$/";
$GLOBALS['LAST_NAME_VALID'] = "/^[A-Z][A-z]+$/";
$GLOBALS['PASSWORD_VALID'] = "/^(\w|\d|[#%^&?.!,()[]]){8,99}$/";  # Please don't change this on a live site without validating the current SQL passwords match the new format.
$GLOBALS['PASSWORD_INVALID_ERROR'] = "Acceptable Characters: Letters, Numbers, and common symbols.";
$GLOBALS['NOTES_VALID'] = "/^(\s|\w|\d|[#%^&?.!,()]){0,999}$/";
?>