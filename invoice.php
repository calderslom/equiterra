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
if (isset($_POST['save_price'])) {
  if (!is_numeric($_POST['price'])) {
    $error = "Price must be a number!";
  } else {
    $_SESSION['invoices'][0]["price"] = $_POST['price'];
  }
}
if (isset($_POST['save_date'])) {
  $_SESSION['invoices'][0]["date"] = $_POST['date'];
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
        <div class="onboarding-overlay-inner info">
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
            if (isset($_POST['edit']) && $_POST['edit'] == 'status') {
              echo "<form method='POST'><h3 class='returning__text'>Status: <select class='form-control edit' id='status' name='status' style='width: 200px; font-size: 18px;' value=" . $_SESSION['invoices'][0]["status"] . "required>
                  <option value=''>Select Status</option>
                  <option value='Paid'>Paid</option>
                  <option value='Unpaid'>Unpaid</option>
                </select><input type='submit' name='save_status' value='Save' class='save-button'></h3></form>";
            } else {
              echo "<h3 class='returning__text'>Status: " . $_SESSION['invoices'][0]["status"] . "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='status'><input type='submit' value='Edit' class='red-button'></form></h3>";
            }
            if (isset($_POST['edit']) && $_POST['edit'] == 'price') {
              echo "<form method='POST'><h3 class='returning__text'>Price: <input class='edit-input' type='price' name='price' value='" . $_SESSION['invoices'][0]["price"] . "' required><input type='submit' name='save_price' value='Save' class='save-button'></h3></form>";
            } else {
              echo "<h3 class='returning__text'>Price: " . $_SESSION['invoices'][0]["price"] . "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='price'><input type='submit' value='Edit' class='red-button'></form></h3>";
            }
            if (isset($_POST['edit']) && $_POST['edit'] == 'date') {
              echo "<form method='POST'><h3 class='returning__text'>Date: <input class='edit-input' type='date' name='date' value='" . $_SESSION['invoices'][0]["date"] . "' required><input type='submit' name='save_date' value='Save' class='save-button'></h3></form>";
            } else {
              echo "<h3 class='returning__text'>Date: " . $_SESSION['invoices'][0]["date"] . "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='date'><input type='submit' value='Edit' class='red-button'></form></h3>";
            }
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