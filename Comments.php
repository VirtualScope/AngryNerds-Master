<?php

// Include CSS.
echo '<link rel="stylesheet" type="text/css" href="css/styles.css"></script>';

// Include bootstrap library.
include("includes/bootstrap.php");

// Include Header/Footer.
include("includes/header.php");
include("includes/footer.php");

?>

<!DOCTYPE html>
<html>

<body>

    <!-- Scrollable Region -->
    <div class="col-8 myRegion center scrollable align-middle">

            <?php

            echo "<div class='container'>";

            include("user_post.php");            
            include("user_post.php");
            include("user_post.php");
            include("user_post.php");
            include("user_post.php");

            echo "</div>";

            ?>

    </div>

</body>

</html>