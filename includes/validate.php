<?php
# Note: this does not support the global flag. The underlying code would need modification.

$GLOBALS['FIRST_NAME_VALID'] = "/^[A-Z][A-z]+$/";
$GLOBALS['LAST_NAME_VALID'] = "/^[A-Z][A-z]+$/";
$GLOBALS['FIRST_NAME_INVALID_ERROR'] = # Same as below.
$GLOBALS['LAST_NAME_INVALID_ERROR'] = "Acceptable Characters: Capital or lowercase with the first letter capitalized.";

$GLOBALS['PASSWORD_VALID'] = "/^(\w|\d|[#%^&?.!,()'$`]){8,100}$/";  
# Please don't change this on a live site without validating the current SQL passwords still match the new format.

$GLOBALS['PASSWORD_INVALID_ERROR'] = "Acceptable Characters: Letters, Numbers, and common symbols.";

$GLOBALS['NOTES_VALID'] = "/^(\s|\w|\d|[#%^&?.!,()'$`]){0,1000}$/";
$GLOBALS['NOTES_INVALID_ERROR'] = "Can only contain letters, numbers, spaces, and a few common symbols. Maximum 1000 characters.";

// ============= user_post_comment table =============

// Comment
$GLOBALS['USER_COMMENT_VALID'] = "/^(\s|\w|\d|[#%^&?.!,()'$`]){0,250}$/";
$GLOBALS['USER_COMMENT_INVALID_ERROR'] = "Can only contain letters, numbers, spaces, and a few common symbols. Maximum 250 characters.";

// ============= user_post table =============

// Title
$GLOBALS['USER_POST_TITLE_VALID'] = "/^(\s|\w|\d|[#%^&?.!,()'$`]){0,150}$/";
$GLOBALS['USER_POST_TITLE_INVALID_ERROR'] = "Can only contain letters, numbers, spaces, and a few common symbols. Maximum 150 characters.";

// Content
$GLOBALS['USER_POST_CONTENT_VALID'] = "/^(\s|\w|\d|[#%^&?.!,()'$`]){0,1000}$/";
$GLOBALS['USER_POST_CONTENT_INVALID_ERROR'] = "Can only contain letters, numbers, spaces, and a few common symbols. Maximum 1000 characters.";

// ============= courses table =============

// Code
$GLOBALS['COURSE_CODE_VALID'] = "/^(\s|\w|\d|[#%^&?.!,()-'$`]){0,20}$/";
$GLOBALS['COURSE_CODE_INVALID_ERROR'] = "Can only contain letters, numbers, spaces, and a few common symbols. Maximum 20 characters.";

// Description
$GLOBALS['COURSE_DESCRIPTION_VALID'] = "/^(\s|\w|\d|[#%^&?.!,()'$`]){0,200}$/";
$GLOBALS['COURSE_DESCRIPTION_INVALID_ERROR'] = "Can only contain letters, numbers, spaces, and a few common symbols. Maximum 200 characters.";

?>