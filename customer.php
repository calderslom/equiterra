<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_GET['customer_name'])) {
  $_SESSION['customer_name'] = urldecode($_GET['customer_name']);
}
// ...
?>

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
          <h1 class="returning__header">Customer Info</h1>
          <?php
          // TODO: must be changed to the customer's info from the database (using their username)
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
          }
          ?>
        </div>
        <div class="onboarding-overlay-inner table">
          <h1 class="returning__header">Invoices</h1>
          <?php
          // TODO: must be changed to the customer info from the database (using their username)
            if (isset($_SESSION['invoices']) && count($_SESSION['invoices']) > 0) {
              echo "<div class='action-bar'>";
              echo "<div class='search-container'><input class='search-table' type='text' id='searchInput' onkeyup='searchTable()' placeholder='Search invoices..'></div>";
              if ($_SESSION['user_type'] == "Admin") {
                echo "<a href='add_invoice.php'><button class='add-button'>Add Invoice +</button></a>";
              }
              echo "</div>";
              echo "<table class='horse-table'>";
              echo "<tr><th>Invoice Number</th><th>Horse</th><th>Date</th><th>Status</th><th>Action</th></tr>";
              // Output data of each row
              foreach($_SESSION['invoices'] as $invoice) {
                echo "<tr>";
                echo "<td>" . $invoice["number"] . "</td>";
                echo "<td>" . $invoice["horse"] . "</td>";
                echo "<td>" . $invoice["date"] . "</td>";
                echo "<td>" . $invoice["status"] . "</td>";
                echo "<td><a href='invoice.php?invoice_number=" . urlencode($invoice["number"]) . "'><button class='table-button'>View/Edit</button></a></td>";
                // echo "<td><button class='table-button'>View/Edit</button></td>";
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