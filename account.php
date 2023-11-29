<?php
  // TODO: must be update the user's info from the database (using their username) instead of using session variables
  if (isset($_POST['save_email'])) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $error = "Invalid email format!";
    } else {
      $_SESSION['email'] = $_POST['email'];
    }
  }
  if (isset($_POST['save_phone_number'])) {
    if (!preg_match("/^[0-9]{10}$/", $_POST['phone_number'])) {
      $error = "Invalid phone number format! Format: 1234567890";
    } else {
      $_SESSION['phone_number'] = $_POST['phone_number'];
    }
  }
  if (isset($_POST['save_password'])) {
    $_SESSION['password'] = $_POST['password'];
    // Update the email in the database
  }
?>

<html>
  <head>
    <link rel="stylesheet" href="style.css">
  <head>
  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <?php include 'navbar.php'; ?>
        <div class="onboarding-overlay-inner returning">
          <h1 class="returning__header">Account Info</h1>
          <br>
          <?php
            // TODO: must be changed to the user's info from the database (using their username)
            if (isset($_SESSION['username'])) {
              echo "<h3 class='returning__text'>Name: " . $_SESSION['name'] . "</h3>";
              echo "<h3 class='returning__text'>Username: " . $_SESSION['username'] . "</h3>";

              if (isset($_POST['edit']) && $_POST['edit'] == 'email') {
                echo "<form method='POST'><h3 class='returning__text'>Email: <input class='edit-input' type='email' name='email' value='" . $_SESSION['email'] . "' required><input type='submit' name='save_email' value='Save' class='green-button'></h3></form>";
              } else {
                echo "<h3 class='returning__text'>Email: " . $_SESSION['email'] . "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='email'><input type='submit' value='Edit' class='red-button'></form></h3>";
              }

              if (isset($_POST['edit']) && $_POST['edit'] == 'phone_number') {
                echo "<form method='POST'><h3 class='returning__text'>Phone Number: <input class='edit-input' type='phone_number' name='phone_number' value='" . $_SESSION['phone_number'] . "' required><input type='submit' name='save_phone_number' value='Save' class='green-button'></h3></form>";
              } else {
                echo "<h3 class='returning__text'>Phone Number: " . $_SESSION['phone_number'] . "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='phone_number'><input type='submit' value='Edit' class='red-button'></form></h3>";
              }
              if (isset($_POST['edit']) && $_POST['edit'] == 'password') {
                echo "<form method='POST'><h3 class='returning__text'>Password: <input class='edit-input' type='text' name='password' value='" . $_SESSION['password'] . "' required><input type='submit' name='save_password' value='Save' class='green-button'></h3></form>";
              } else {
                echo "<h3 class='returning__text'>Password: " . $_SESSION['password'] . "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='password'><input type='submit' value='Edit' class='red-button'></form></h3>";
              }
              if (isset($error)) {
                echo "<p style='color:red; text-align:center; font-size:20px;'>$error</p>";
              }
            }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>