<?php
//session_start();
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
        <?php 
        //session_start();
        include 'navbar.php'; ?>
        <img class="welcome-image" src="images/welcome.png" alt="Welcome">
        <div class="button-container">
          <?php
          //session_start();
          // Check if the user type session variable is set
          if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] == 'Admin') {
          ?>
              <a href="horses.php" class="large-button horse">Horses</a>
              <a href="barns.php" class="large-button barn">Barns</a>
              <a href="customers.php" class="large-button customer">Customers</a>
              <a href="account.php" class="large-button account">Account Info</a>
          <?php
            //session_start();
            } else if ($_SESSION['user_type'] == 'Client') {
          ?>
              <a href="horses.php" class="large-button horse">My Horses</a>
              <a href="customer.php" class="large-button customer">Details</a>
              <a href="account.php" class="large-button account">Account Info</a>
          <?php
            }
          }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>