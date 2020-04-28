<?php

// ============== Includes ==============

// Include CSS.
echo '<link rel="stylesheet" type="text/css" href="css/styles.css"></script>';

// Secure the web page
$secured = true;

include("includes/header.php");

// Include bootstrap library.
include("includes/bootstrap.php");

// ============== DB setup ==============

// DB setup.
include("includes/db_config.php");

// ============== Variables ==============

// What course is being modified?
if (isset($_POST['courseId'])) $_SESSION['courseId'] = $_POST['courseId'];


// ============== Operations (arrival) ==============

// Load information about the course to populate the input fields.
$result = $Database->get_class_by_id($_SESSION['courseId']);
if (!$result) {
    echo ("<p>Failed to load coures information :{</p>");
    exit;
}

// ============== Operations (delete) ==============

if (array_key_exists('delete', $_POST)) {

    // Delete Forum posts    
    $Database->remove_class_forum_posts($_SESSION['courseId']);

    // Delete class user relationships
    $Database->remove_class_user_relationships($_SESSION['courseId']);

    // Delete the course
    $Database->remove_class($_SESSION['courseId']);

    // Return to the home page.
    header("Location: index.php");
}

// Load information about the course to populate the input fields.
$row = $result->fetch_array();
$code = $row['code'];
$description = $row['description'];
$image = $row['image'];

// ============== Operations (modify) ==============

if (isset($_POST['ClassCode'])) $classCode = $_POST['ClassCode'];
if (isset($_POST['ClassDescription'])) $classDescription = $_POST['ClassDescription'];
if (isset($_POST['users'])) $userAssoc = $_POST['users'];


if (isset($classCode) && isset($classDescription) && isset($userAssoc)) {

    // If the new code doesn't match the old, make sure there isn't a collision with
    // another class code in the DB
    if ($code != $classCode) {
        // Ensure there are no duplicate courses.
        $result = $Database->get_class($classCode);
        if (!$result) {
            echo "There was an error saving the class :(";
            exit;
        }

        // If the query succeeded, stop the saving process.
        if ($result->num_rows > 0) {
            echo ("<div class='text-center'><h3>A class with that code already exists! There can be no duplicates.</h3></div>");
            exit;
        }
    }

    // Image processing.
    $fileName = basename($_FILES["fileToUpload"]["name"]);

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<div class='text-center'>Invalid file format.</div>";
        exit;
    }

    // Try to upload the file.
    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $fullPath)) { # Replace uniqid to have picture name as filename.
        echo "<div class='text-center'>An error was encountered while uploading the file.</div>";
    }

    // If the user doesn't choose an image, keep the old image.
    if ($targetFile != "") {
        $fullPath = $webserver_root . 'images/' . $targetFile;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $targetFile = uniqid() . '.' . $imageFileType;


        // Save the course (with the image).
        $result = $Database->modify_class_with_image(
            $_SESSION['courseId'],
            $classCode,
            $classDescription,
            $targetFile
        );
    } else {
        // Save the course.
        $result = $Database->modify_class(
            $_SESSION['courseId'],
            $classCode,
            $classDescription
        );
    }

    // If the query failed...
    if (!$result) {
        echo ("<p>Failed to save class :{</p>");
        exit;
    }
    // If the query was successful...
    else {
        // Give all users in the system access to this course.
        if ($userAssoc == "All") {
            $result = $Database->get_users();
            if (!$result) {
                echo "There was an error connecting to the database :{";
            }

            // If our query was successful...
            $numRows = $result->num_rows;
            if ($numRows > 0) {

                // Get the appropriate class id
                $result3 = $Database->get_class($classCode);
                if (!$result3) {
                    echo "There was an error saving the class :(";
                    exit;
                }

                // If the class is found, use its id.
                if ($result3->num_rows != 0) {
                    // Get the class id.
                    $row = $result3->fetch_assoc();
                    $classId = $row['id'];
                }

                // For each user...
                for ($i = 0; $i < $numRows; $i++) {
                    // Get the user's id.
                    $row = $result->fetch_assoc();
                    $userId = $row['id'];

                    // Ensure the user/class combination doesn't exist already.
                    $result4 = $Database->get_user_course($userId, $classId);

                    // If no results returned, go ahead with the operation
                    if ($result4->num_rows == 0) {
                        // Create a row in user_course.
                        $Database->add_class_user($classId, $userId);
                    }
                }
            }
        }
    }

    // Return to the home page.
    header("Location: index.php");
}

?>


<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Modify Class</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/checkout/">

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="form-validation.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">
        <div class="py-5 text-center">
            <h2>Modify Class</h2>
            <p class="lead">Those who cannot change their minds cannot change anything ~ George Shaw</p>
        </div>

        <!-- Input fields -->
        <div class="col-12 order-md-1">
            <form class="needs-validation" novalidate="" method="post" id="myForm" enctype="multipart/form-data">
                <!-- Class Code -->
                <div class="col-md-6 mb-3">
                    <label for="ClassCode">Class Code</label>
                    <input type="text" class="form-control" id="ClassCode" name="ClassCode" placeholder="ICS-325-A" <?php echo "value='" . $code . "'" ?>
                    required pattern="<?php echo substr($GLOBALS['COURSE_CODE_VALID'],1,-1);?>" title="<?php echo $GLOBALS['COURSE_CODE_INVALID_ERROR'];?>">
                    <div class="invalid-feedback">
                        Valid class course code is required.
                    </div>
                </div>
                <!-- Class Description -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="ClassDescription">Description</label>
                        <input type="textarea" class="form-control" rows="5" id="ClassDescription" name="ClassDescription" placeholder="" <?php echo "value='" . $description . "'" ?> 
                        required pattern="<?php echo substr($GLOBALS['COURSE_DESCRIPTION_VALID'],1,-1);?>" title="<?php echo $GLOBALS['COURSE_DESCRIPTION_INVALID_ERROR'];?>"></input>
                        <div class="invalid-feedback">
                            Valid class description is required.
                        </div>
                    </div>
                </div>

                <!-- User -->
                <div class="col-md-6">
                    <label>Assign users</label><br>
                    <select class="custom-select d-block w-100" id="class" name="users" required="">
                        <option value="">Choose...</option>
                        <option selected>All</option>
                    </select>
                    <div class="invalid-feedback">
                        Valid associated users choice is required.
                    </div>
                </div><br>

                <!-- Image -->
                <div class="col-md-6 mb-3">
                    <label>Image (optional)</label>
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="fileToUpload" name="fileToUpload">
                        <label class="custom-file-label" for="customFile">Choose...</label>
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
                <hr class="mb-4">
            </form>
            <form method="post" id="deleteForm"></form>

            <div class="center">
                <!-- Submit button -->
                <div class="row">
                    <button class="btn btn-primary btn-lg leftButton" type="submit" form="myForm">Modify</button>
                    <button class="btn btn-danger btn-lg rightButton" type="submit" form="deleteForm" name="delete" value="delete">Delete</button>
                </div>
            </div>
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
include("includes/footer.php");
?>