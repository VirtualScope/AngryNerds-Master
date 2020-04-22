<?php
include("includes/authenticate.php"); # This creates a session.bg-cover

$displayName;
if (isset($_SESSION['fname']))
  $displayName = $_SESSION['fname'];
else
  $displayName = "Anonymous";

$homepath = '../AngryNerds-Master/'; //'http://'.$_SERVER['SERVER_NAME'].'/';

?>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="<?php echo $homepath . 'login_form.php' ?>"><img src="<?php echo $homepath ?>images/logo2.png" width=140 height=30></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">

        <!-- Home button -->
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $homepath ?>index.php">Home</a>
        </li>
      </ul>

      <?php
      if ($isLoggedIn) {

        // If the user is an admin, display that information by their name.
        if ($_SESSION['isAdmin']){
          $displayName .= " (admin)";
        }

        // Display user name
        echo '
            <ul class="navbar-nav ml-auto">
              <li class="nav-text"><i class="fas fa-user"></i>
              <a href="profile.php" >' . $displayName . '</a>
              </li>';

        // Logout button
        echo '
              <li class="nav-item">
              <a class="nav-link" href="' . $homepath . 'includes/logout.php">Log Out</a>
              </li>    
            </ul>';
      } else {
        echo '
        <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="' . $homepath . 'login_form.php">Login</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="' . $homepath . 'login_form.php">Sign up</a>
            </li>    
        </ul>';
      }
      ?>
    </div>
  </div>
</nav>
<!-- NavBar code sourced from https://github.com/VirtualScope/MajesticMagicians-Master/blob/master/Web/navbar.php -->