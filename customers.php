<?php

// Include Functions
require_once 'retrieval_functions.php';
require_once 'client_functions.php';
require_once 'update_database.php';

// Need to connect to the database for data retrieval. The $conn object will be used to communicate with the SQL database
$conn = new mysqli('sql.freedb.tech', 'freedb_Youssef', 'fp53R5UKVn*M@XW', 'freedb_Equiterra');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Populate session array "clients"
retrieve_client_names($conn);

$conn->close();     // Close connection to the database
?>

<script>
  function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementsByClassName("horse-table")[0];
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0]; // Change the index to the column you want to search
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
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
        <img class="barns-image" src="images/customers.png" alt="Barns">
        <div class="onboarding-overlay-inner table">
          <?php
          // TODO: must be changed to the barns info from the database (using their username)
            if (isset($_SESSION['clients']) && count($_SESSION['clients']) > 0) {
              echo "<div class='action-bar'>";
              echo "<div class='search-container'><input class='search-table' type='text' id='searchInput' onkeyup='searchTable()' placeholder='Search customers..'></div>";
              if ($_SESSION['user_type'] == "Admin") {
                echo "<a href='signup.php'><button class='add-button'>Add Client +</button></a>";
              }
              echo "</div>";
              echo "<table class='horse-table'>";
              echo "<tr><th>Name</th><th>Action</th></tr>";
              // Output data of each row
              foreach($_SESSION['clients'] as $client) {
                echo "<tr>";
                echo "<td>" . $client['name'] . "</td>";
                // This is where we pass data to the next page via the URL. Whatever we place in urlencode will come after "client_username" - which we can specify ourselves
                echo "<td><a href='customer.php?client_username=" . urlencode($client['username']) . "'><button class='table-button'>View/Edit</button></a></td>";
                echo "</tr>";
              }
              echo "</table>";
            } else {
              echo "<div class='returning__header'>No customers in database <a href='signup.php'><button class='add-button'>Add Customer +</button></a></div>";
            }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>