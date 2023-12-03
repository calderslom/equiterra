<?php
  // Function for outputting data to the console
  function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
  }

  // Resuming a session to access session variables
  //session_start();

  // Need to connect to the database for data retrieval. The $conn object will be used to communicate with the SQL database
  $conn = new mysqli('sql.freedb.tech', 'freedb_Youssef', 'fp53R5UKVn*M@XW', 'freedb_Equiterra');
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 

  if (isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) { // Ensuring that a user has been succesfully logged in and session variables were assigned

    if ($_SESSION['user_type'] == 'Client') {  // Client is logged in 
      if (isset($_SESSION['username']) && !empty($_SESSION['username'])) { // Ensuring there is a stored username
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
        // Prepare SQL statement for Client name retrieval
        $stmtClient = $conn->prepare("SELECT Cname FROM Client WHERE Cusername = ?");
        $stmtClient->bind_param("s", $username);
        $stmtClient->execute();
        $clientNameResult = $stmtClient->get_result();
        // Check if the clientNameResult was successful
        if ($clientNameResult) {
            $clientRow = $clientNameResult->fetch_assoc();
            $ownerName = $clientRow['Cname'];
            debug_to_console($ownerName);

            // GetClientHorses is a procedure which returns all horse tuples related to a Cusername which it takes as argument
            $stmt = $conn->prepare("CALL GetClientHorses(?)");
            // Bind the parameter
            $stmt->bind_param("s", $username);
            // Execute the statement
            $stmt->execute();
            // Get the result
            $result = $stmt->get_result();
            //debug_to_console($username);
            // Initializing the $horses array
            $horses = []; 
            if ($result->num_rows > 0) {
              
              while ($row = $result->fetch_assoc()) {
              // Access columns using associative names
              $horseName = $row['Hname'];
              debug_to_console($horseName);
              // Add horse information to the array
              $horses[$horseName] = $ownerName;
              
              }
            // Store the $horses array in the session variable 'horses'
            $_SESSION['horses'] = $horses;
        } else {
            // Handle the error if necessary
            debug_to_console("Error fetching client name: " . $stmtClient->error);
        }

        
        }
      }
      else {
        
      }
    }
  }
$conn->close();     // Close connection to the database
?>

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
          //session_start();
          // TODO: must be changed to the horses info from the database (using their username)
            if (isset($_SESSION['horses']) && count($_SESSION['horses']) > 0) {
              if ($_SESSION['user_type'] == "Admin") {
                echo "<a href='add_horse.php'><button class='add-button'>Add Horse +</button></a>";
              }
              echo "<table class='horse-table'>";
              echo "<tr><th>Name</th><th>Owner</th><th>Action</th></tr>";
              // Output data of each row
              foreach($_SESSION['horses'] as $horse => $owner) {
                echo "<tr>";
                echo "<td>" . $horse . "</td>";
                echo "<td>" . $owner . "</td>";
                echo "<td><a href='horse.php?horse_name=" . urlencode($horse) . "'><button class='table-button'>View/Edit</button></a></td>";
                echo "</tr>";
              }
              echo "</table>";
            } else {
              echo "<div class='returning__header'>No horses in database <button class='add-button'>Add Horse +</button></div>";
            }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>