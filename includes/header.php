<?php
include("includes/authenticate.php"); # This creates a session.bg-cover

$displayName;
if (isset($_SESSION['fname']))
  $displayName = $_SESSION['fname'];
else
  $displayName = "Anonymous";

$homepath = 'http://'.$_SERVER['SERVER_NAME'].'/';

?>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
<div class="container">
  <a class="navbar-brand" href="<?php echo $homepath . 'login_form.php'?>"><img src="<?php echo $homepath?>images/logo2.png" width=140 height=30></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $homepath?>index.php">Home</a>
      </li> <!--
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $homepath?>homepage.php">Experiments</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $homepath?>social.php">Social</a>
      TODO
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $homepath?>viewphoto2.php">Archive</a>
      </li>
      -->
    </ul>

    <?php 
        if($isLoggedIn){
            echo'
            <ul class="navbar-nav ml-auto">
              <li class="nav-text"><i class="fas fa-user"></i>
              <a href="profile.php" >'. $displayName .'</a>
              </li>';
              /*if($userType == "admin"){
                echo'
                <li class="nav-item">
                <a class="nav-link" href="'.$homepath.'adminpanel.php">AdminPanel</a>
                </li>';
              }*/
              echo'
              <li class="nav-item">
              <a class="nav-link" href="'.$homepath.'includes/logout.php">Log Out</a>
              </li>    
            </ul>';
        } else{
            echo'
        <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="'.$homepath.'login_form.php">Login</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="'.$homepath.'login_form.php">Sign up</a>
            </li>    
        </ul>';
        }
    ?>
  </div>
</div>  
</nav>
<!-- NavBar code sourced from https://github.com/VirtualScope/MajesticMagicians-Master/blob/master/Web/navbar.php -->
