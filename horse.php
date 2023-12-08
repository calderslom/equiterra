<?php
// Include functions
require_once 'utility.php';
require_once 'client_functions.php';
require_once 'horse_functions.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_GET['horse_name'])) { // Session 'horse_name' now contains the name of the horse. This is the key for the Horse table
  $_SESSION['horse_name'] = urldecode($_GET['horse_name']);
}
if (isset($_POST['save_conf_notes'])) {
  $_SESSION['horse']['conf_notes'] = $_POST['conf_notes'];
}

// Need to connect to the database for data retrieval. The $conn object will be used to communicate with the SQL database
$conn = new mysqli('sql.freedb.tech', 'freedb_Youssef', 'fp53R5UKVn*M@XW', 'freedb_Equiterra');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

retrieve_horse_details($conn);
retrieve_shoeing_protocol_dates($conn);

$conn->close();     // Close connection to the database
?>

<script>
  function expandNotes() {
    var shortNotes = document.querySelector('.conf-notes-short');
    var fullNotes = document.querySelector('.conf-notes-full');
    var arrow = document.querySelector('.expand-arrow');
    if (fullNotes.style.display === 'none') {
      fullNotes.style.display = 'block';
      shortNotes.style.display = 'none';
      arrow.innerHTML = '▲';
    } else {
      fullNotes.style.display = 'none';
      shortNotes.style.display = 'block';
      arrow.innerHTML = '▼';
    }
  }

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
          <h1 class="returning__header">Horse Details</h1>
          <?php
          // TODO: must be changed to the horse's info from the database (using their username)
          if (isset($_SESSION['horse'])) {
            echo "<div class='user-info'>";
            echo "<div class='left-column'>";
            echo "<h3 class='returning__text'>Name: " . $_SESSION['horse']['name'] . "</h3>";
            echo "<h3 class='returning__text'>Owner: " . $_SESSION['horse']['owner'] . "</h3>";
            echo "<h3 class='returning__text'>Barn: " . $_SESSION['horse']['barn'] . "</h3>";
            echo "<h3 class='returning__text'>Breed: " . $_SESSION['horse']['breed'] . "</h3>";
            if ($_SESSION['user_type'] == "Admin") {
              if (isset($_POST['edit']) && $_POST['edit'] == 'conf_notes') {
                echo "<form method='POST'><h3 class='returning__text'>Confirmation Notes:";
                echo "<input type='submit' name='save_conf_notes' value='Save' class='conf-save'>";
                echo "<div><textarea class='edit-input' type='conf_notes' name='conf_notes' style='height: 100px; width: 400px;'>" . $_SESSION['horse']['conf_notes'] . "</textarea></div>";
                echo "</h3></form>";
              } else {
                echo "<h3 class='returning__text'>Confirmation Notes: ";
                echo "<button class='expand-arrow' onclick='expandNotes()'>▼</button>";
                echo "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='conf_notes'><input type='submit' value='Edit' class='conf-button'></form>";
                echo "<div class='conf-notes-short'>" . substr($_SESSION['horse']['conf_notes'], 0, 50) . "</div>";
                echo "<div class='conf-notes-full' style='display: none;'>" . $_SESSION['horse']['conf_notes'] . "</div>";
                echo "</h3>";
              }
            } else {
              echo "<h3 class='returning__text'>Confirmation Notes: ";
              echo "<button class='expand-arrow' onclick='expandNotes()'>▼</button>";
              echo "<div class='conf-notes-short'>" . substr($_SESSION['horse']['conf_notes'], 0, 50) . "</div>";
              echo "<div class='conf-notes-full' style='display: none;'>" . $_SESSION['horse']['conf_notes'] . "</div>";
              echo "</h3>";
            }
            echo "</div>";
            echo "<div>";
            echo "<h3 class='returning__text'>Gender: " . $_SESSION['horse']['gender'] . "</h3>";
            echo "<h3 class='returning__text'>Discipline: " . $_SESSION['horse']['discipline'] . "</h3>";
            echo "<h3 class='returning__text'>Height: " . $_SESSION['horse']['height'] . "</h3>";
            echo "<h3 class='returning__text'>Birthdate: " . $_SESSION['horse']['birthdate'] . "</h3>";
            echo "<h3 class='returning__text'><a href='images.php'>Images</a></h3>";
            echo "</div>";
            echo "</div>";
          }
          ?>
        </div>
        <div class="onboarding-overlay-inner table">
          <h1 class="returning__header">Shoeing Protocols</h1>
          <?php
          // TODO: must be changed to the customer info from the database (using their username)
          if (isset($_SESSION['shoeing_protocols']) && count($_SESSION['shoeing_protocols']) > 0) {
            echo "<div class='action-bar'>";
            echo "<div class='search-container'><input class='search-table' type='text' id='searchInput' onkeyup='searchTable()' placeholder='Search shoeing protocols..'></div>";
            if ($_SESSION['user_type'] == "Admin") {
              echo "<a href='add_shoeing_protocol.php?horse_name=" . urlencode($_SESSION['horse_name']) . "'><button class='add-button'>Add Shoeing Protocol +</button></a>";
            }
            echo "</div>";
            echo "<table class='horse-table'>";
            echo "<tr><th>Date</th><th>Action</th></tr>";
            // Output data of each row
            foreach ($_SESSION['shoeing_protocols'] as $protocol) {
              echo "<tr>";
              echo "<td>" . $protocol['date'] . "</td>";
              if ($_SESSION['user_type'] == "Admin") {
                echo "<td><a href='shoeing_protocol.php?protocol_date=" . urlencode($protocol['date']) . "&protocol_horse=" . urlencode($protocol['horse_name']) . "'><button class='table-button'>View/Edit</button></a></td>";
              } else {
                echo "<td><a href='shoeing_protocol.php?protocol_date=" . urlencode($protocol['date']) . "&protocol_horse=" . urlencode($protocol['horse_name']) . "'><button class='table-button'>View</button></a></td>";
              }
              
              echo "</tr>";
            }
            echo "</table>";
          } else {
            if ($_SESSION['user_type'] == "Admin") {
              echo "<div class='returning__header'>No shoeing protocols in database <a href='add_shoeing_protocol.php'><button class='add-button'>Add Shoeing Protocol +</button></a></div>";
            } else {
              echo "<div class='returning__header'>No shoeing protocols in database</div>";
            }
          }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>