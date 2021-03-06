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
    function add_class($classCode, $classDescription, $imageFileName)
    {
        $sql = "INSERT INTO `courses`(`code`, `description`, `image`) 
        VALUES (    
        '" . $classCode . "',
        '" . $classDescription . "',
        '" . $imageFileName . "'
        )";
        return $this->query($sql);
    }
    function modify_class($classId, $classCode, $classDescription){
        $sql = "UPDATE `courses` SET `code` = '". $classCode ."', `description` = '". $classDescription ."' WHERE `courses`.`id` = $classId";
        return $this->query($sql);
    }
    function modify_class_with_image($classId, $classCode, $classDescription, $imageFileName){
        $sql = "UPDATE `courses` SET `code` = '". $classCode ."', `description` = '". $classDescription ."', `image` = '". $imageFileName ."' WHERE `courses`.`id` = $classId";
        return $this->query($sql);
    }
    function remove_class_forum_posts($classId){

        $sql = "DELETE FROM `user_post_comment` WHERE `user_post_comment`.`user_post_id` = 
        (SELECT id FROM user_post WHERE course_id = $classId)";
        $this->query($sql);

        $sql = "DELETE FROM `user_post` WHERE `user_post`.`course_id` = $classId";
        $this->query($sql);
    }
    function remove_class_user_relationships($classId){       
        $sql = "DELETE FROM `user_course` WHERE `user_course`.`course_id` = $classId";
        return $this->query($sql);
    }
    function remove_class($classId){
        $sql = "DELETE FROM `courses` WHERE `courses`.`id` = $classId";
        return $this->query($sql);
    }
    function add_class_user($classCode, $userId)
    {
        $sql = "INSERT INTO `user_course`(`course_id`, `user_id`) 
        VALUES (    
        '" . $classCode . "',
        '" . $userId . "'        
        )";
        return $this->query($sql);
    }
    function check_credentials($inputEmail, $inputPassword) # Future TODO (outside of this class scope): Add additional checks to verify no two accounts use the same email!
    {
        $sql = "SELECT * FROM `users` WHERE email='$inputEmail'"; # We only need one row, lets just hope there is never more than one row!
        $result = $this->query($sql);
        $row = $result->fetch_assoc();
        $hash = $row['pass'];
        if (password_verify($inputPassword, $hash))
        {
            $row['success'] = true;
            return $row;
        }
        else
        {
            return;
        }
    }
    function check_credentials_by_id($id, $inputPassword) # Future TODO (outside of this class scope): Add additional checks to verify no two accounts use the same email!
    {
        $sql = "SELECT * FROM `users` WHERE id='$id'"; # We only need one row, lets just hope there is never more than one row!
        $result = $this->query($sql);
        $row = $result->fetch_assoc();
        $hash = $row['pass'];
        if (password_verify($inputPassword, $hash))
        {
            $row['success'] = true;
            return $row;
        }
        else
        {
            return;
        }
    }
    function get_user_course($userId, $courseId){
        $sql = "SELECT * FROM user_course WHERE course_id=$courseId AND user_id=$userId";
        return $this->query($sql);
    }
    function get_users()
    {
        $sql = "SELECT * FROM users";
        return $this->query($sql);
    }
    function get_user($id)
    {
        $sql = "SELECT * FROM users WHERE id = $id";
        return $this->query($sql);
    }
    function get_class($classCode){
        $sql = "SELECT * FROM courses WHERE code = '$classCode'";
        return $this->query($sql);
    }
    function get_class_by_id($courseId){
        $sql = "SELECT * FROM courses WHERE id = $courseId";
        return $this->query($sql);
    }
    function get_classes($userId)
    {        
        $sql = "SELECT course_id FROM user_course WHERE user_id=$userId";        
        return $this->query($sql);
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
    function count_users_by_identity($user_email) # Returns INT
    {
        $sql = "SELECT * FROM `users` WHERE email='$user_email'";
        return $this->query($sql);
    }
    function set_current_timestamp_for_user($userId){
        $sql ="UPDATE `users` SET last_log_in=CURRENT_TIMESTAMP WHERE id='$userId'";
        return $this->query($sql);
    }
    function create_user($fname, $lname, $email, $pass, $admin, $notes)
    {
        $now = 0; # They have not logged in yet! Set time to epoch 0 (1970)
        $active = "yes";
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`fname`, `lname`, `email`, `pass`, `admin`, `last_log_in`, `active`, `notes`) 
        VALUES ('" . $fname . "', '" . ($lname) . "', '" . ($email) . "', '" . ($hash) . "', " . ($admin) . ", " . ($now) . ", '" . ($active) . "', '" . ($notes) . "')";
        return $this->query($sql);
    }
    # Update functions
    function update_email($newEmail, $userId)
    {
        $sql = "UPDATE `users` SET email='$newEmail' WHERE id='$userId'";
        return $this->query($sql);
    }
    function update_user_by_id($id, $current_password, $fname, $lname, $pass, $notes)
    {
        $response = $this->check_credentials_by_id($id, $current_password);
        if ($response["success"])
        {
            $active = "yes";            
            $_fname = "";
            $_lname = "";
            $_pass  = "";
            $_notes = "";
    
            $returned = $this->get_user($id);
            $result = $returned->fetch_assoc();

            $old_fname = $result["fname"];
            $old_lname = $result["lname"];
            $old_pass  = $result["pass"];
            $old_notes = $result["notes"];
    
            $_fname = ($fname === "")? $old_fname : $fname;
            $_lname = ($lname === "")? $old_lname : $lname;
    
            $_pass = ($pass === "")? $old_pass : password_hash($pass, PASSWORD_DEFAULT);
    
            $_notes = ($notes === "")? $old_notes : $notes;
    
            $sql = "UPDATE `users` SET `fname`='$_fname', `lname`='$_lname', `pass`='$_pass', `active`='$active', `notes`='$_notes' WHERE `id`=$id";
            return $this->query($sql);
        }
        else
        {
            return null; # Null response means the current password failed.
        }
    }
    # Utility and Status Functions
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
