<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
unset($_SESSION['horses']); // Temporary fix for session variables not unsetting

// Include functions
require_once 'utility.php';
require_once 'client_functions.php';
// Connect to the database for data retrieval; use $conn for DB access
require_once 'db_config.php';

// Ensuring that a user has been succesfully logged in and session variables were assigned
if (isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
  // This condidition will pass when an Admin is logged in
  if ($_SESSION['user_type'] == 'Admin') {
    // This Query retrieves horse names and client names from the Database
    $stmt = $conn->prepare("CALL GetHorses()");
    // Execute the statement
    $stmt->execute();
    // Get the result
    $result = $stmt->get_result();
  }
  //Admin must be logged in if Client conditional above fails
  else {
    // Ensuring there is a stored username
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
      debug_to_console($_SESSION['username']);
      $client_username = $_SESSION['username'];
      // GetClientHorses is a procedure which returns Horse names and the Client name associated with the current user (client)
      $stmt = $conn->prepare("CALL GetClientHorses(?)");
      // Bind the parameter
      $stmt->bind_param("s", $client_username);
      // Execute the statement
      $stmt->execute();
      // Get the result
      $result = $stmt->get_result();
    }
  }
  while ($tuple = $result->fetch_assoc()) {
    $_SESSION['horses'][$tuple['Hname']] = $tuple['Cname'];
  }
}
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

</head>

<body>
  <div class="onboarding-overlay">
    <div class="onboarding-overlay-outer">
      <?php include 'navbar.php'; ?>
      <img class="horses-image" src="images/horses.png" alt="Horses">
      <div class="onboarding-overlay-inner table">
        <?php
        if (isset($_SESSION['horses']) && count($_SESSION['horses']) > 0) {
          echo "<div class='action-bar'>";
          echo "<div class='search-container'><input class='search-table' type='text' id='searchInput' onkeyup='searchTable()' placeholder='Search horses or owners..'></div>";
          if ($_SESSION['user_type'] == "Admin") {
            echo "<a href='add_horse.php'><button class='add-button'>Add Horse +</button></a>";
          }
          echo "</div>";
          echo "<table class='horse-table'>";
          echo "<tr><th>Name</th><th>Owner</th><th>Action</th></tr>";
          // Output data of each row
          foreach ($_SESSION['horses'] as $horse => $owner) {
            echo "<tr>";
            echo "<td>" . $horse . "</td>";
            echo "<td>" . $owner . "</td>";
            echo "<td><a href='horse.php?horse_name=" . urlencode($horse) . "'><button class='table-button'>View/Edit</button></a></td>";
            echo "</tr>";
          }
          echo "</table>";
        } else {
          if ($_SESSION['user_type'] == "Admin") {
            echo "<div class='returning__header'>No horses in database <a href='add_horse.php'><button class='add-button'>Add Horse +</button></a></div>";
          } else {
            echo "<div class='returning__header'>No horses in database</div>";
          }
        }
        ?>
      </div>
      <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
    </div>
  </div>
</body>

</html>