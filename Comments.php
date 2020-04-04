<?php

// ============== Includes ==============

// Include CSS.
echo '<link rel="stylesheet" type="text/css" href="css/styles.css"></script>';

// Include bootstrap library.
include("includes/bootstrap.php");

// ============== DB setup ==============

// DB config.
DEFINE('DATABASE_HOST', 'localhost');
DEFINE('DATABASE_DATABASE', 'angrynerdsmaster');
DEFINE('DATABASE_USER', 'root');
DEFINE('DATABASE_PASSWORD', '');

// ============== Variables ==============

?>

<!DOCTYPE html>
<html>

<body>

    <!-- Scrollable Region -->
    <div class="center myRegion scrollable align-middle border rounded col-sm-12 col-md-10 col-lg-8 col-xl-6">

        <div class='container'>

            <?php

            // ============== Load posts from DB ==============

            // Connect to DB
            $dbcn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
            $dbcn->set_charset("utf8");
            if (mysqli_connect_errno()) {
                echo "<p>Error creating database connection.</p>";
                exit;
            }

            // Generate SQL
            $sql = "SELECT * FROM user_post";

            // Run query, retrieving unfiltered set of words.
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
                    $content = $row['content'];
                    $title = $row['title'];
                    $created_date = $row['created_date'];
                    $created_by = $row['created_by'];
                    $image = $row['image'];
                    displayUserPost($content, $title, $created_date, $created_by, $image);
                }
            }

            ?>

        </div>

    </div>

</body>

</html>

<?php

// Prints a user post on the screen using the information stored in the DB.
function displayUserPost($content, $title, $created_date, $created_by, $image){
    echo'
    
    <!-- User post card -->
    <div class="myCard card center">
      <div class="row no-gutters">
    
        <!-- Text Content -->
        <div class="col-sm-8">
          <div class="card-body">
            <h5 class="card-title">'. $title .'</h5>
            <p class="card-text">'. $content .'</p>
            <p class="card-text"><small class="text-muted">'. $created_date .'</small></p>
          </div>
        </div>
    
        <!-- Picture -->
        <div class="col-sm-4" style="margin: auto;">
          <img src="images/ImageNotFound.png" class="card-img" alt="...">
        </div>
      </div>
    
      <!-- Reveal Comments -->
      <p>
        <a class="btn btn-outline-secondary" data-toggle="collapse" href="#commentSection" role="button" aria-expanded="false" aria-controls="commentSection">
          Comments
        </a>
      </p>
    
      <!-- Comment -->
      <div class="collapse" id="commentSection">
        <div class="card card-body">
          Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
        </div>
      </div>
    
    </div>
    
    ';
}

?>