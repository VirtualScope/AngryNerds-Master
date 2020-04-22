<?php

// ============== Includes ==============

// Ensure the user is logged in.
$secured = true;

// Include CSS.
echo '<link rel="stylesheet" type="text/css" href="css/styles.css"></script>';

// Include Header
include("includes/header.php");

// Include bootstrap library.
include("includes/bootstrap.php");

// DB setup
include("includes/db_config.php");

// ============== Variables ==============

?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

  <title>Home</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/album/">

  <!-- Bootstrap core CSS -->
  <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="album.css" rel="stylesheet">
</head>

<body>
  <main role="main">

    <section class="jumbotron text-center">
      <div class="container">
        <h1 class="jumbotron-heading">Classes</h1>
        <p class="lead text-muted">This is an announcement. Make it short and sweet, but not too short so folks don't simply skip over it entirely.</p>
      </div>
    </section>

    <div class="album py-5 bg-light">
      <div class="container">
        <div class="row">

          <?php

          // Load class cards based on what user is logged in.
          $userId = $_SESSION['userId'];

          // Query the DB for classes associated with that user.    
          $result = $Database->get_classes($userId);
          if (!$result) {
            echo "There was an error connecting to the database :{";
          }

          // If our query was successful...
          $numRows = $result->num_rows;
          if ($numRows > 0) {

            // For each course the user has...
            for ($i = 0; $i < $numRows; $i++) {
              // Get the DB row.
              $row = $result->fetch_assoc();
              $courseId = $row['course_id'];

              // Query the DB for that course...
              $courseResult = $Database->get_course_database($courseId);
              if (!$courseResult) {
                echo "There was an error loading your class with ID " . $courseId . " :{";
              }

              // If our query was successful...
              $coursesFound = $courseResult->num_rows;
              if ($coursesFound > 0) {
                // Get the DB row.
                $courseRow = $courseResult->fetch_assoc();
                $courseCode = $courseRow['code'];
                $courseDescription = $courseRow['description'];
                $courseImage = $courseRow['image'];

                // Display the course in the list.
                echo '  
                <div class="col-md-4">
                <div class="card mb-4 box-shadow">
                  <img class="card-img-top" src="images/' . $courseImage . '" alt="' . $courseCode . '" data-holder-rendered="true" style="height: 225px; width: 100%; display: block;">
                  <div class="card-body">
                    <p class="card-text"><b>' . $courseCode . '</b>: ' . $courseDescription . '</p>
                    <div class="d-flex justify-content-between align-items-center">
                      <form class="btn-group" action="comments.php" method="post">
                        <input id="courseId" type="hidden" name="courseId" value="' . $courseId . '"/>
                        <button type="submit" class="btn btn-outline-secondary">View Forum</button>
                      </form>
                      <small class="text-muted">Jan 20 - May 20</small>
                    </div>
                  </div>
                </div>
              </div>    
                ';
              }
            }
          }

          ?>




        </div>
      </div>
    </div>

    <?php

    // Load create class button only if an admin user is logged in.
    $userId = $_SESSION['userId'];

    // Query the DB for classes associated with that user.    
    $result = $Database->get_user($userId);
    if (!$result) {
      echo "There was an error connecting to the database :{";
    }

    // If our query was successful...
    $numRows = $result->num_rows;
    if ($numRows > 0) {
      // Get the DB row.
      $row = $result->fetch_assoc();
      $isAdmin = $row['admin'];
    }

    // If the user is an admin, show the button.
    if ($isAdmin){      

      // Create Class
      echo'
      <div class="text-center" style="margin-top:100px">
      <form action="create_class.php">
        <button type="submit" class="btn btn-primary">Create class</button>
      </form>
      </div>
      ';
    }

    ?>
  </main>


  <!-- Bootstrap core JavaScript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script>
    window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')
  </script>
  <script src="../../assets/js/vendor/popper.min.js"></script>
  <script src="../../dist/js/bootstrap.min.js"></script>
  <script src="../../assets/js/vendor/holder.min.js"></script>


  <svg xmlns="http://www.w3.org/2000/svg" width="348" height="225" viewBox="0 0 348 225" preserveAspectRatio="none" style="display: none; visibility: hidden; position: absolute; top: -100%; left: -100%;">
    <defs>
      <style type="text/css"></style>
    </defs><text x="0" y="17" style="font-weight:bold;font-size:17pt;font-family:Arial, Helvetica, Open Sans, sans-serif">Thumbnail</text>
  </svg>
</body>

</html>
<?php
// Include Footer
include("includes/footer.php");
?>