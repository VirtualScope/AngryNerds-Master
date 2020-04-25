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

$courseId = $_SESSION['courseId'];

// ============== Operations ==============

if (isset($_POST['postTitle']) && isset($_POST['postContent'])) {

    // Image processing.
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $targetFile = uniqid() . '.' . $imageFileType;
    $fullPath = 'images/' . $targetFile;

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<div class='text-center'>Invalid file format.</div>";
        exit;
    }

    // Try to upload the file.
    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $fullPath)) { # Replace uniqid to have picture name as filename.
        echo "<div class='text-center'>An error was encountered while uploading the file.</div>";
    }

    $result = $Database->add_post($_POST['postTitle'], $_POST['postContent'], $_SESSION['userId'], $_SESSION['courseId'], $targetFile);
    if (!$result) {
        echo ("<p>Failed to save post :{</p>");
        exit;
    }

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

    <title>Create Post</title>

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
            <form class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
                <!-- Post Title -->
                <div class="col-md-6 mb-3">
                    <label for="postTitle">Title</label>
                    <input type="text" class="form-control" id="postTitle" name="postTitle" placeholder="" value="" 
                    required pattern="<?php echo substr($GLOBALS['USER_POST_TITLE_VALID'],1,-1);?>" title="<?php echo $GLOBALS['USER_POST_TITLE_INVALID_ERROR'];?>">
                    <div class="invalid-feedback">
                        Valid post title is required.
                    </div>
                </div>
                <!-- Post Content -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="postContent">Content</label>
                        <input type="textarea" class="form-control" rows="5" id="postContent" name="postContent" placeholder="" value="" 
                        required pattern="<?php echo substr($GLOBALS['USER_POST_CONTENT_VALID'],1,-1);?>" title="<?php echo $GLOBALS['USER_POST_CONTENT_INVALID_ERROR'];?>"></input>
                        <div class="invalid-feedback">
                            Valid post content is required.
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="col-md-6 mb-3">
                    <label>Image</label>
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="fileToUpload" name="fileToUpload" required="true">
                        <label class="custom-file-label" for="customFile">Choose...</label>
                        <div class="invalid-feedback">
                            Valid post image is required.
                        </div>
                    </div>
                </div>

                <script>
                    $('#fileToUpload').on('change', function() {
                        //get the file name
                        var fileName = $(this).val();
                        //replace the "Choose a file" label
                        $(this).next('.custom-file-label').html(fileName);
                    })
                </script>

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