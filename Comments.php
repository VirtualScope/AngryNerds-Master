<?php

// ============== Includes ==============

// Ensure the user is logged in.
$secured = true;

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
  $userId_ = $_SESSION['userId'];
  $newCommentPostId = $_POST['new_comment_post_id'];
  $newCommentContent = $_POST['new_comment_content'];
  $result = $Database->add_comment($userId_, $newCommentPostId, $newCommentContent);
  if (!$result) {
    echo ("<p>Failed to save comment :{</p>");
    exit;
  }

  // Once the values have been used, clear them to ensure they don't get reposted

}

?>

<!DOCTYPE html>
<html>
<title>Forum</title>

<body>

  <!-- Create post -->
  <div class="text-center" style="margin-top:100px">
    <hr class="mb-4">
    <form action="create_user_post.php">
      <button type="submit" class="btn btn-primary">Create Post</button>
    </form>
  </div>
  <!-- Scrollable Region -->
  <div class="center myRegion scrollable align-middle rounded col-sm-12 col-md-10 col-lg-8 col-xl-6" style="max-width:800px;">
    <div class='container'>
      <?php

      // ============== Load posts from DB ==============

      $result = $Database->get_user_posts($_SESSION['courseId']);
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
          displayUserPost($Database, $userPostId, $title, $content, $created_date, $user_id, $image);
        }
      } else {
        echo "No posts yet :(";
      }

      ?>

    </div>
  </div>

</body>

</html>

<?php

// Prints a user post on the screen using the information stored in the DB.
function displayUserPost($Database, $userPostId, $title, $content, $created_date, $user_id, $image)
{
  // Load associated user
  $result = $Database->get_names_from_user_id($user_id);
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

  // If the image is somehow missing, use the ImageNotFound placeholder.
  if (!file_exists('images/'.$image)) {
    $image = 'ImageNotFound.png';
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
        <a class="btn btn-outline-secondary" data-toggle="collapse" href="#commentSection' . $userPostId . '" role="button" aria-expanded="true" aria-controls="commentSection">
          Comments
        </a>
      </p>
    
      <!-- Comment -->
      <div class="collapse show in" id="commentSection' . $userPostId . '">';

  // ================= START Comments Section =================

  // Load associated comments

  // Run query.
  $result = $Database->load_comments($userPostId);
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

      $userResult = $Database->get_names_from_user_id($commentUserId);
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
      <button type="submit" class="col-3 mb-2 btn btn-outline-primary">Comment</button>
    </form>
  </div>
  ';

  // ================= END Comments Section =================

  echo '
      </div>
    
    </div>
    
    ';
}
include("includes/footer.php");
?>