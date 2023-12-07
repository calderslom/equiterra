<?php
// Include functions
require_once 'utility.php';
require_once 'retrieval_functions.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_GET['barn_name'])) {
  $_SESSION['barn_name'] = urldecode($_GET['barn_name']);
  //debug_to_console($_SESSION['barn_name']);
}

// Need to connect to the database for data retrieval. The $conn object will be used to communicate with the SQL database
$conn = new mysqli('sql.freedb.tech', 'freedb_Youssef', 'fp53R5UKVn*M@XW', 'freedb_Equiterra');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

retrieve_barn_details($conn);

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
      td = tr[i].getElementsByTagName("td");
      for (var j = 0; j < td.length; j++) {
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
          <h1 class="returning__header">Barn Info</h1>
          <?php
          // TODO: must be changed to the barn's info from the database (using their username)
          if (isset($_SESSION['barn'])) {
            echo "<div class='user-info'>";
            echo "<div>";
            echo "<h3 class='returning__text'>Name: " . $_SESSION['barn']['name'] . "</h3>";
            echo "<h3 class='returning__text'>Email: " . $_SESSION['barn']['email'] . "</h3>";
            echo "<h3 class='returning__text'>Street Number: " . $_SESSION['barn']['street_number'] . "</h3>";
            echo "<h3 class='returning__text'>City: " . $_SESSION['barn']['city'] . "</h3>";
            echo "<h3 class='returning__text'>Postal Code: " . $_SESSION['barn']['postal_code'] . "</h3>";
            echo "</div>";
            echo "<div>";
            echo "<h3 class='returning__text'>Contact: " . $_SESSION['barn']['contact'] . "</h3>";
            echo "<h3 class='returning__text'>Phone Number: " . $_SESSION['barn']['phone_number'] . "</h3>";
            echo "<h3 class='returning__text'>Street Name: " . $_SESSION['barn']['street_name'] . "</h3>";
            echo "<h3 class='returning__text'>Province: " . $_SESSION['barn']['province'] . "</h3>";
            echo "</div>";
            echo "</div>";
          }
          ?>
        </div>
        <div class="onboarding-overlay-inner table">
          <h1 class="returning__header">Barn Horses</h1>
          <?php
          // TODO: must be changed to the horses info from the database (using the barn_name)
          if (isset($_SESSION['dummy_horses']) && count($_SESSION['dummy_horses']) > 0) {
            echo "<div class='action-bar'>";
            echo "<div class='search-container'><input class='search-table' type='text' id='searchInput' onkeyup='searchTable()' placeholder='Search horses or owners..'></div>";
            echo "</div>";
            echo "<table class='horse-table'>";
            echo "<tr><th>Name</th><th>Owner</th><th>Action</th></tr>";
            // Output data of each row
            foreach ($_SESSION['dummy_horses'] as $horse => $owner) {
              echo "<tr>";
              echo "<td>" . $horse . "</td>";
              echo "<td>" . $owner . "</td>";
              echo "<td><a href='horse.php?horse_name=" . urlencode($horse) . "'><button class='table-button'>View/Edit</button></a></td>";
              echo "</tr>";
            }
            echo "</table>";
          } else {
            echo "<div class='returning__header'>No horses in database <a href='add_horse.php'><button class='add-button'>Add Horse +</button></a></div>";
          }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>