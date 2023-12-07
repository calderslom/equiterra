<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_GET['invoice_number'])) {
  $_SESSION['invoice_number'] = urldecode($_GET['invoice_number']);
}

if (isset($_POST['save_status'])) {
  if ($_POST['status'] == "") {
    $error = "Please select a status!";
  } else {
    $_SESSION['invoices'][0]["status"] = $_POST['status'];
  }
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
        <div class="onboarding-overlay-inner returning">
        <?php
        // TODO: must be changed to the user's info from the database (using their username)
          echo "<h1 class='returning__header'>Invoice #" . $_SESSION['invoice_number'] . "</h1>";
          echo "<br>";
          if (isset($_SESSION['invoice_number'])) {
            echo "<div class='user-info'>";
            echo "<div>";
            echo "<h3 class='returning__text'>Customer: " . $_SESSION['invoices'][0]["customer"] . "</h3>";
            echo "<h3 class='returning__text'>Horse: " . $_SESSION['invoices'][0]["horse"] . "</h3>";
            echo "<h3 class='returning__text'>Farrier: " . $_SESSION['invoices'][0]["farrier"] . "</h3>";
            echo "</div>";
            echo "<div>";
            if ($_SESSION['user_type'] == "Admin") {
              if (isset($_POST['edit']) && $_POST['edit'] == 'status') {
                echo "<form method='POST'><h3 class='returning__text'>Status: <select class='form-control edit' id='status' name='status' style='width: 200px; font-size: 18px;' value=" . $_SESSION['invoices'][0]["status"] . "required>
                    <option value=''>Select Status</option>
                    <option value='Paid'>Paid</option>
                    <option value='Unpaid'>Unpaid</option>
                  </select><input type='submit' name='save_status' value='Save' class='save-button'></h3></form>";
              } else {
                echo "<h3 class='returning__text'>Status: " . $_SESSION['invoices'][0]["status"] . "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='status'><input type='submit' value='Edit' class='red-button'></form></h3>";
              }
            } else {
              echo "<h3 class='returning__text'>Status: " . $_SESSION['invoices'][0]["status"] . "</h3>";
            }
            echo "<h3 class='returning__text'>Price: " . $_SESSION['invoices'][0]["price"] . "</h3>";
            echo "<h3 class='returning__text'>Date: " . $_SESSION['invoices'][0]["date"] . "</h3>";
            echo "</div>";
            echo "</div>";
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