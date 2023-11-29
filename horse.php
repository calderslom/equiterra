<?php
if (isset($_GET['horse_name'])) {
  $_SESSION['horse_name'] = urldecode($_GET['horse_name']);
}
// ...
?>

<html>
  <head>
    <link rel="stylesheet" href="style.css">
  <head>
  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <?php include 'navbar.php'; ?>
        <?php
        if (isset($_SESSION['horse_name']))
        // do stuff
        ?>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>