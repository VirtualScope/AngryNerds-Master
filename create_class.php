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

$classCode = null;
$classDescription = null;
$fileName = null;
$userAssoc = null;

if (isset($_POST['ClassCode'])) $classCode = $_POST['ClassCode'];
if (isset($_POST['ClassDescription'])) $classDescription = $_POST['ClassDescription'];
if (isset($_POST['filename'])) $fileName = $_POST['filename'];
if (isset($_POST['users'])) $userAssoc = $_POST['users'];

// ============== Operations ==============

if (isset($classCode) && isset($classDescription) && isset($fileName) && isset($userAssoc)) {

    // Ensure there are no duplicate courses.
    $result = $Database->get_class($classCode);    
    if (!$result) {
        echo "There was an error saving the class :(";
        exit;
    }

    // If the query succeeded, stop the saving process.
    if ($result->num_rows > 0){
        echo ("<div class='text-center'><h3>A class with that code already exists! There can be no duplicates.</h3></div>");
        exit;
    }

    // Save the course.
    $result = $Database->add_class(
        $classCode,
        $classDescription,
        $fileName
    );

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
                if ($result3->num_rows != 0){
                    // Get the class id.
                    $row = $result3->fetch_assoc();
                    $classId = $row['id'];
                }

                // For each user...
                for ($i = 0; $i < $numRows; $i++) {
                    // Get the user's id.
                    $row = $result->fetch_assoc();
                    $userId = $row['id'];

                    // Create a row in user_course.
                    $Database->add_class_user($classId, $userId);
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

    <title>Create Class</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/checkout/">

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="form-validation.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">
        <div class="py-5 text-center">
            <h2>Create Class</h2>
            <p class="lead">A place for users to create and share content.</p>
        </div>

        <!-- Input fields -->
        <div class="col-12 order-md-1">
            <form class="needs-validation" novalidate="" method="post">
                <!-- Class Code -->
                <div class="col-md-6 mb-3">
                    <label for="ClassCode">Class Code</label>
                    <input type="text" class="form-control" id="ClassCode" name="ClassCode" placeholder="ICS-325-A" value="" required="">
                    <div class="invalid-feedback">
                        Valid class course code is required.
                    </div>
                </div>
                <!-- Class Description -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="ClassDescription">Description</label>
                        <input type="textarea" class="form-control" rows="5" id="ClassDescription" name="ClassDescription" placeholder="" value="" required=""></input>
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
                        <option>All</option>
                    </select>
                    <div class="invalid-feedback">
                        Valid associated users choice is required.
                    </div>
                </div><br>

                <!-- Image -->
                <div class="col-md-6 mb-3">
                    <label>Image</label>
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="customFile" name="filename" required="true">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                        <div class="invalid-feedback">
                            Valid class image is required.
                        </div>
                    </div>
                </div>

                <!-- Submit button -->
                <hr class="mb-4">
                <div class="text-center">
                    <button class="btn btn-primary btn-lg" type="submit">Create!</button>
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
include("includes/footer.php");
?>