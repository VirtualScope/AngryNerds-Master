<?php

// ============== Includes ==============

// Ensure the user is logged in.
include("includes/authenticate.php");

// Include CSS.
echo '<link rel="stylesheet" type="text/css" href="css/styles.css"></script>';
include("includes/header.php");

// Include bootstrap library.
include("includes/bootstrap.php");

// DB setup
include("includes/db_config.php");

// ============== Variables ==============

// ============== Operations ==============

// Find out what course we need to load user posts for.
if (isset($_POST['courseId'])) $_SESSION['courseId'] = $_POST['courseId'];

// Insert new comment entry.
if (isset($_POST['new_comment_content']) && isset($_POST['new_comment_post_id']) && $_POST['new_comment_content'] != "") {

  $sql = "INSERT INTO `user_post_comment`(`user_id`, `user_post_id`, `content`) 
  VALUES (" . $_SESSION['userId'] . ", " . ($_POST['new_comment_post_id']) . ", '" . ($_POST['new_comment_content']) . "')";

  // Run query.
  $result = $dbcn->query($sql);
  if (!$result) {
    echo ("<p>Failed to save comment :{</p>");
    exit;
  }

  // Once the values have been used, clear them to ensure they don't get reposted
  $_POST['new_comment_post_id'] = null;
  $_POST['new_comment_content'] = null;
}

?>

<!DOCTYPE html>
<html>

<body>                 

  <!-- Scrollable Region -->
  <div class="center myRegion scrollable align-middle border rounded col-sm-12 col-md-10 col-lg-8 col-xl-6" style="max-width:800px;">

    <div class='container'>

      <?php

      // ============== Load posts from DB ==============

      // Generate SQL
      $sql = "SELECT * FROM user_post WHERE course_id=" . $_SESSION['courseId']  . "";

      // Run query.
      $result = $dbcn->query($sql);
      if (!$result) {
        echo ("<p>Failed to load user posts :{</p>");
        exit;
      }

      // How many rows were retrieved?
      $numRows = $result->num_rows;

      // If any were found...
      if ($numRows > 0) {
        // Loop over each row.
        for ($i = 0; $i < $numRows; $i++) {
          $row = $result->fetch_array();
          $userPostId = $row['id'];
          $title = $row['title'];
          $content = $row['content'];
          $created_date = $row['created_date'];
          $user_id = $row['user_id'];
          $image = $row['image'];

          // Output.
          displayUserPost($dbcn, $userPostId, $title, $content, $created_date, $user_id, $image);
        }
      }

      ?>

    </div>

  </div>

</body>

</html>

<?php

// Prints a user post on the screen using the information stored in the DB.
function displayUserPost($dbcn, $userPostId, $title, $content, $created_date, $user_id, $image)
{
  // Load associated user
  $query = "SELECT fname, lname FROM users WHERE id=$user_id";

  // Run query.
  $result = $dbcn->query($query);
  if (!$result) {
    echo ("<p>Failed to load user :{</p>");
    exit;
  }

  // If any were found...
  $fname = "Anonymous";
  $lname = "User";
  if ($result->num_rows > 0) {
    $userRow = $result->fetch_array();
    $fname = $userRow['fname'];
    $lname = $userRow['lname'];
  }

  echo '
    
    <!-- User post card -->
    <div class="myCard card center">
      <div class="row no-gutters">
    
        <!-- Text Content -->
        <div class="col-sm-8">
          <div class="card-body">
            <h5 class="card-title">' . $title . '</h5>
            <p class="card-text"><i>' . $fname . ' ' . $lname . '</i></p><br>
            <p class="card-text">' . $content . '</p>
            <p class="card-text"><small class="text-muted">' . $created_date . '</small></p>
          </div>
        </div>
    
        <!-- Picture -->
        <div class="col-sm-4" style="margin: auto;">
          <img src="images/' . $image . '" class="card-img" alt="...">
        </div>
      </div>
    
      <!-- Reveal Comments -->
      <p>
        <a class="btn btn-outline-secondary" data-toggle="collapse" href="#commentSection'.$userPostId.'" role="button"  aria-controls="commentSection">
          Comments
        </a>
      </p>
    
      <!-- Comment -->
      <div class="collapse in" id="commentSection'.$userPostId.'">';

  // ================= START Comments Section =================

  // Load associated comments
  $query = "SELECT * FROM user_post_comment WHERE user_post_id=$userPostId";

  // Run query.
  $result = $dbcn->query($query);
  if (!$result) {
    echo ("<p>Failed to load comments on post ID " . $userPostId . " :{</p>");
    exit;
  }

  // If any were found...
  $commentsFound = $result->num_rows;
  if ($commentsFound > 0) {

    // Loop over the comments and print them.
    $commentUserId = 0;
    $commentContent = "";
    $commentCreatedDate = "";
    for ($i = 0; $i < $commentsFound; $i++) {
      $commentRow = $result->fetch_array();
      $commentUserId = $commentRow["user_id"];
      $commentContent = $commentRow["content"];
      $commentCreatedDate = $commentRow["created_date"];

      // Load associated user
      $query = "SELECT fname, lname FROM users WHERE id=$commentUserId";

      // Run query.
      $userResult = $dbcn->query($query);
      if (!$userResult) {
        echo ("<p>Failed to load user :{</p>");
        exit;
      }

      // If any were found...
      $commentUserFname = "Anonymous";
      $commentUserLname = "User";
      if ($userResult->num_rows > 0) {
        $userRow = $userResult->fetch_array();
        $commentUserFname = $userRow['fname'];
        $commentUserLname = $userRow['lname'];
      }

      echo '        
      <div class="card card-body">      
      <i>' . $commentUserFname . ' ' . $commentUserLname . ' - <small class="text-muted">' . $commentCreatedDate . '</small></i>
      <p>' . $commentContent . '</p>      
      </div>
      ';
    }
  }

  echo '        
  <div class="card card-body">
    <form class="row" method="post">
      <div class="form-group col-9 mb-2">
        <input type="text" class="form-control" name="new_comment_content" placeholder="Comment...">
        <input type="hidden" class="form-control" name="new_comment_post_id" value="' . $userPostId . '">
      </div>
      <button type="submit" class="col-3 mb-2 btn btn-outline-primary">Post</button>
    </form>
  </div>
  ';

?>



<?php

  // ================= END Comments Section =================

  echo '
      </div>
    
    </div>
    
    ';
}
include("includes/footer.php");
?>