<?php 

// Include functions
require_once 'utility.php';
require_once 'retrieval_functions.php';
require_once 'barn_functions.php';
// Connect to the database for data retrieval; use $conn for DB access
require_once 'db_config.php';

retrieve_all_barns($conn);

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
        <img class="barns-image" src="images/barns.png" alt="Barns">
        <div class="onboarding-overlay-inner table">
          <?php
            if (isset($_SESSION['barns']) && count($_SESSION['barns']) > 0) {
              echo "<div class='action-bar'>";
              echo "<div class='search-container'><input class='search-table' type='text' id='searchInput' onkeyup='searchTable()' placeholder='Search barns..'></div>";
              if ($_SESSION['user_type'] == "Admin") {
                echo "<a href='add_barn.php'><button class='add-button'>Add Barn +</button></a>";
              }
              echo "</div>";
              echo "<table class='horse-table'>";
              echo "<tr><th>Name</th><th>Action</th></tr>";
              // Output data of each row
              foreach($_SESSION['barns'] as $barn) {
                echo "<tr>";
                echo "<td>" . $barn . "</td>";
                echo "<td><a href='barn.php?barn_name=" . urlencode($barn) . "'><button class='table-button'>View/Edit</button></a></td>";
                echo "</tr>";
              }
              echo "</table>";
            } else {
              echo "<div class='returning__header'>No barns in database <a href='add_barn.php'><button class='add-button'>Add Barn +</button></a></div>";
            }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>