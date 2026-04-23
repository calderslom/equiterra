<?php
// Include Functions
require_once 'retrieval_functions.php';
require_once 'client_functions.php';
require_once 'update_database.php';
// Connect to the database for data retrieval; use $conn for DB access
require_once 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}


if (isset($_GET['client_username'])) {  // This will be set if the admin is using the page
  $_SESSION['Cusername'] = urldecode($_GET['client_username']);
}
else { // Client is logged in
  $_SESSION['Cusername'] = $_SESSION['username'];
}

// Retrieving a tuple from the Client table based on the client username passed in via the URL
if ($user = retrieve_client($conn)) {
  // Setting session variables for the user
  $_SESSION['customer']['username'] = $user['Cusername'];
  $_SESSION['customer']['name'] = $user['Cname'];
  $_SESSION['customer']['phone_number'] = $user['Phone_num'];
  $_SESSION['customer']['email'] = $user['Email'];
} else debug_to_console("User retrieval failed.");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invoiceNumber']) && isset($_POST['invoiceStatus'])) {
  $invoiceNumber = $_POST['invoiceNumber'];
  $invoiceStatus = $_POST['invoiceStatus'];
  $newStatus = $invoiceStatus == 1 ? 0 : 1;
  $stmt_update = $conn->prepare("CALL ChangeStatus(?,?)");
  // Bind parameters and execute the SQL statement
  $stmt_update->bind_param("ii", $newStatus, $invoiceNumber);
  $stmt_update->execute();
}

retrieve_invoices_client($conn);


$conn->close();     // Close connection to the database
?> // End of PHP


<script>
  function searchTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementsByClassName("horse-table")[0];
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td");
      for (j = 0; j < td.length; j++) {
        if (td[j]) {
          txtValue = td[j].textContent || td[j].innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
            break;
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }
  }
</script>

<html>

<head>
  <link rel="stylesheet" href="style.css">

  <head>

  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <?php include 'navbar.php'; ?>
        <div class="onboarding-overlay-inner info">
          <h1 class="returning__header">Client Information</h1>
          <?php
          if (isset($_SESSION['customer'])) {
            echo "<div class='user-info'>";
            echo "<div>";
            echo "<h3 class='returning__text'>Name: " . $_SESSION['customer']['name'] . "</h3>";
            echo "<h3 class='returning__text'>Email: " . $_SESSION['customer']['email'] . "</h3>";
            echo "</div>";
            echo "<div>";
            echo "<h3 class='returning__text'>Username: " . $_SESSION['customer']['username'] . "</h3>";
            echo "<h3 class='returning__text'>Phone Number: " . $_SESSION['customer']['phone_number'] . "</h3>";
            echo "</div>";
            echo "</div>";
          } else {
            echo "<div class='returning__header'>Administrator has not added client details</div>";
          }
          ?>
        </div>
        <div class="onboarding-overlay-inner table">
          <h1 class="returning__header">Invoices</h1>
          <?php
          if (isset($_SESSION['invoices']) && count($_SESSION['invoices']) > 0) {
            echo "<div class='action-bar'>";
            echo "<div class='search-container'><input class='search-table' type='text' id='searchInput' onkeyup='searchTable()' placeholder='Search invoices..'></div>";
            if ($_SESSION['user_type'] == "Admin") {
              echo "<a href='add_invoice.php'><button class='add-button'>Add Invoice +</button></a>";
            }
            echo "</div>";
            echo "<table class='horse-table'>";
            if ($_SESSION['user_type'] == "Admin") {
              echo "<tr><th>Invoice Number</th><th>Price</th><th>Status</th><th>Action</th></tr>";
            } else {
              echo "<tr><th>Invoice Number</th><th>Price</th><th>Status</th></tr>";
            }
            // Output data of each row
            foreach ($_SESSION['invoices'] as $invoice) {
              echo "<tr>";
              echo "<td>" . $invoice["number"] . "</td>";
              echo "<td>" . $invoice["price"] . "</td>";
              if ($invoice["status"] == 1) echo "<td>" . "Paid" . "</td>";
              else echo "<td>" . "Unpaid" . "</td>";
              $invoiceNumber = $invoice["number"];
              $invoiceStatus = $invoice["status"];
              if ($_SESSION['user_type'] == "Admin") {
                echo "<td><form method='post' style='margin: 0; padding: 0;'><input type='hidden' name='invoiceNumber' value=$invoiceNumber><input type='hidden' name='invoiceStatus' value=$invoiceStatus><input class='table-button' type='submit' value='Change Status'></form></td>";
              }

              echo "</tr>";
            }
            echo "</table>";
          } else {
            if ($_SESSION['user_type'] == "Admin") {
              echo "<div class='returning__header'>No invoices in database <a href='add_invoice.php'><button class='add-button'>Add Invoice +</button></a></div>";
            } else {
              echo "<div class='returning__header'>No invoices in database</div>";
            }
          }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>

</html>