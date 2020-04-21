<?php

class DatabaseAccessLayer
{
    // Properties
    private $__dbcn;
    private $__db_connection_status;

    function __construct($db_host, $db_user, $db_pass, $db_name)
    {
        // Connect to DB
        $this->__dbcn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $this->__dbcn->set_charset("utf8");
        if (mysqli_connect_errno()) {
            throw new Exception("Database Connection Error");
            $this->__db_connection_status = false;
            return;
        } else
            $this->__db_connection_status = true;
    }
    function get_user_posts($courseId)
    {
        $sql = "SELECT * FROM user_post WHERE course_id=$courseId ORDER BY created_date DESC";
        return $this->query($sql);
    }
    function add_post($postTitle, $postContent, $userId, $courseId, $imageFileName)
    {
        $sql = "INSERT INTO `user_post`(`title`, `content`, `user_id`, `course_id`, `image`) 
        VALUES (    
        '" . $postTitle . "',
        '" . $postContent . "',
        " . $userId . ", 
        " . $courseId . ", 
        '" . $imageFileName . "'
        )";
        return $this->query($sql);
    }
    function update_email($newEmail, $userId, $currentPassword)
    {
        $sql = "UPDATE users SET email='$newEmail' WHERE id='$userId' AND pass='$currentPassword' ";
        return $this->query($sql);
    }
    function check_credentials($inputEmail, $inputPassword)
    {
        $sql = "SELECT * FROM users WHERE email='$inputEmail' AND pass='$inputPassword'";
        return $this->query($sql);
    }
    function get_classes($userId)
    {        
        $sql = "SELECT course_id FROM user_course WHERE user_id=$userId";        
        return $this->__dbcn->query($sql);
    }
    function get_course_database($courseId)
    {
        $sql = "SELECT * FROM courses WHERE id=$courseId";
        return $this->query($sql);
    }
    function get_names_from_user_id($userId)
    {
        $sql = "SELECT fname, lname FROM users WHERE id=$userId";
        return $this->query($sql);
    }
    function load_comments($userPostId)
    {
        $sql = "SELECT * FROM user_post_comment WHERE user_post_id=$userPostId";
        return $this->query($sql);
    }
    function add_comment($userId, $new_comment_post_id, $new_comment_post_content)
    {
        $sql = "INSERT INTO `user_post_comment`(`user_id`, `user_post_id`, `content`) 
        VALUES (" . $userId . ", " . ($new_comment_post_id) . ", '" . ($new_comment_post_content) . "')";
        return $this->query($sql);
    }
    private function query($sql)
    {        
        $result = $this->__dbcn->query($sql);
        return $result;
    }
    function get_database_connection_status()
    {
        return $this->__db_connection_status;
    }
}
