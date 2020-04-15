<?php

// ============== Includes ==============

// Ensure the user is logged in.
include("includes/authenticate.php");

// Include CSS.
echo '<link rel="stylesheet" type="text/css" href="css/styles.css"></script>';

// Include Header
include("includes/header.php");

// Include bootstrap library.
include("includes/bootstrap.php");

// DB setup
include("includes/db_config.php");

// ============== Variables ==============

$courseId = $_SESSION['courseId'];

// ============== Operations ==============

if(isset($_POST['postTitle']) && isset($_POST['postContent']) && isset($_POST['filename'])){
    $sql = "INSERT INTO `user_post`(`title`, `content`, `user_id`, `course_id`, `image`) 
    VALUES (    
    '" . $_POST['postTitle'] . "',
    '" . $_POST['postContent'] . "',
    " . $_SESSION['userId'] . ", 
    " . $_SESSION['courseId'] . ", 
    '" . $_POST['filename'] . "'
    )";    
  
    // Run query.
    $result = $database->query($sql);
    if (!$result) {
      echo ("<p>Failed to save post :{</p>");
      exit;
    }
  
    // Once the values have been used, clear them to ensure they don't get reposted
    $_POST['postTitle'] = null;
    $_POST['postContent'] = null;
    $_POST['filename'] = null;

    // Return to the forum page.
    header("Location: comments.php");
}

?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Checkout example for Bootstrap</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/checkout/">

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="form-validation.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">
        <div class="py-5 text-center">
            <h2>Create Post</h2>
            <p class="lead">Share your content with the world.</p>
        </div>

        <!-- Input fields -->
        <div class="col-12 order-md-1">
            <form class="needs-validation" novalidate="" method="post">
                <!-- Post Title -->
                <div class="col-md-6 mb-3">
                    <label for="postTitle">Title</label>
                    <input type="text" class="form-control" id="postTitle" name="postTitle" placeholder="" value="" required="">
                    <div class="invalid-feedback">
                        Valid post title is required.
                    </div>
                </div>
                <!-- Post Content -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="postContent">Content</label>
                        <input type="textarea" class="form-control" rows="5" id="postContent" name="postContent" placeholder="" value="" required=""></input>
                        <div class="invalid-feedback">
                            Valid post content is required.
                        </div>
                    </div>
                </div>

                <!-- This happens automatically -->
                <!-- <div class="col-md-6">
                    <label>Associated class</label><br>
                    <select class="custom-select d-block w-100" id="class" required="">
                        <option value="">Choose...</option>
                        <option>ICS 325-A</option>
                        <option>ICS 325-B</option>
                        <option>ICS 325-C</option>
                    </select>
                    <div class="invalid-feedback">
                        Valid associated class is required.
                    </div>
                </div> -->

                <!-- Image -->                
                <div class="col-md-6 mb-3">
                    <label>Image</label>
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="customFile" name="filename" required="true">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                        <div class="invalid-feedback">
                            Valid post image is required.
                        </div>
                    </div>
                </div>

                <!-- Submit button -->
                <hr class="mb-4">
                <div class="text-center">
                    <button class="btn btn-primary btn-lg" type="submit">Post!</button>
                </div>
            </form>
        </div>
    </div>

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
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';

            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');

                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>


</body>

</html>
<?php
// Include Footer
include("includes/footer.php");
?>
