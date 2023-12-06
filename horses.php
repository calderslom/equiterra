<?php

// Include functions
require_once 'utility.php';
require_once 'client_functions.php';


session_start();

// Need to connect to the database for data retrieval. The $conn object will be used to communicate with the SQL database
$conn = new mysqli('sql.freedb.tech', 'freedb_Youssef', 'fp53R5UKVn*M@XW', 'freedb_Equiterra');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// Ensuring that a user has been succesfully logged in and session variables were assigned
if (isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {

  // This condidition will pass when an Admin is logged in
  if ($_SESSION['user_type'] == 'Admin') {
    $stmt = $conn->prepare("SELECT * FROM Horse");
    // Execute the statement
    $stmt->execute();
    // Get the result
    $result = $stmt->get_result();
    // Initializing the $horses array
    $horses = [];
    // Ensuring tuples have been returned by the query
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        // Access columns using associative names
        $horseName = $row['Hname'];
        // Get the Client username from the Horse tuple
        $client_username = $row['Cusername'];
        // Prepare SQL statement for Client name retrieval
        $stmtClient = $conn->prepare("SELECT Cname FROM Client WHERE Cusername = ?");
        $stmtClient->bind_param("s", $client_username);
        $stmtClient->execute();
        $clientNameResult = $stmtClient->get_result();
        // Check if the clientNameResult was successful
        if ($clientNameResult) {
          // Get the Client Tuple returned by the SQL Query
          $clientRow = $clientNameResult->fetch_assoc();
          // Retrieve the Cname attribute from the clientRow tuple and assign it to ownerName (we need to associate each horse with an owner)
          $owner_name = $clientRow['Cname'];
          // Add horse information to the array
          $horses[$horseName] = $owner_name;
        }
        // Store the $horses array in the session variable 'horses'
        $_SESSION['horses'] = $horses;
      }
    }
  }
  // Admin must be logged in if Client conditional above fails
  else {
    // Ensuring there is a stored username
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
      if ($client = retrieve_client($conn)) {
        //Retrieve the client tuple and use it to find the owner_name (we need to associate each horse with an owner)
        $owner_name = $client['Cname'];
        $username = $client['Cusername'];
        // GetClientHorses is a procedure which returns all horse tuples related to a Cusername which it takes as argument
        $stmt = $conn->prepare("CALL GetClientHorses(?)");
        // Bind the parameter
        $stmt->bind_param("s", $username);
        // Execute the statement
        $stmt->execute();
        // Get the result
        $result = $stmt->get_result();
        // Initializing the $horses array
        $horses = [];
        // Ensuring tuples were returned by the SQL query
        if ($result->num_rows > 0) {
          // Loop until all tuples have been accessed
          while ($row = $result->fetch_assoc()) {
            // Access columns using associative names
            $horseName = $row['Hname'];
            // Add horse information to the array - Horse names are keys and ownernames are the elements they index
            $horses[$horseName] = $owner_name;
          }
          // Store the $horses array in the session variable 'horses'
          $_SESSION['horses'] = $horses;
        }
      }
    } else {
      // Handle the error if necessary
      debug_to_console("Error fetching client name: " . $stmtClient->error);
    }
    // // Get the username from the session and sanitize it
    // $username = $conn->real_escape_string($_SESSION['username']);
    // // Prepare SQL statement for Client name retrieval
    // $stmtClient = $conn->prepare("SELECT Cname FROM Client WHERE Cusername = ?");
    // $stmtClient->bind_param("s", $username);
    // $stmtClient->execute();
    // $clientNameResult = $stmtClient->get_result();
    // // Check if the clientNameResult was successful
    // if ($clientNameResult) {
    //   // Get the Client Tuple returned by the SQL Query
    //   $clientRow = $clientNameResult->fetch_assoc();
    //   // Retrieve the Cname attribute from the clientRow tuple and assign it to ownerName (we need to associate each horse with an owner)
    //   $owner_name = $clientRow['Cname'];
    //   // GetClientHorses is a procedure which returns all horse tuples related to a Cusername which it takes as argument
    //   $stmt = $conn->prepare("CALL GetClientHorses(?)");
    //   // Bind the parameter
    //   $stmt->bind_param("s", $username);
    //   // Execute the statement
    //   $stmt->execute();
    //   // Get the result
    //   $result = $stmt->get_result();
    //   // Initializing the $horses array
    //   $horses = [];
    //   // Ensuring tuples were returned by the SQL query
    //   if ($result->num_rows > 0) {
    //     // Loop until all tuples have been accessed
    //     while ($row = $result->fetch_assoc()) {
    //       // Access columns using associative names
    //       $horseName = $row['Hname'];
    //       // Add horse information to the array - Horse names are keys and ownernames are the elements they index
    //       $horses[$horseName] = $owner_name;
    //     }
    //     // Store the $horses array in the session variable 'horses'
    //     $_SESSION['horses'] = $horses;
    //   } else {
    //     // Handle the error if necessary
    //     debug_to_console("Error fetching client name: " . $stmtClient->error);
    //   }
    // }

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

  <head>

  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <?php include 'navbar.php'; ?>
        <img class="horses-image" src="images/horses.png" alt="Horses">
        <div class="onboarding-overlay-inner table">
          <?php
          // TODO: must be changed to the horses info from the database (using their username)
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
            echo "<div class='returning__header'>No horses in database <a href='add_horse.php'><button class='add-button'>Add Horse +</button></a></div>";
          }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>

</html>