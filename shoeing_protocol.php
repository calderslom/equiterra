<?php
// Include functions
require_once 'utility.php';
require_once 'horse_functions.php';

// Unset session variable arrays
// unset($_SESSION['shoeing_protocols']); // Temporary fix for session variables not unsetting
// unset($_SESSION['shoeing_protocol']); // Temporary fix for session variables not unsetting


if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (isset($_GET['protocol_horse'])) {
  $_SESSION['protocol_horse'] = urldecode($_GET['protocol_horse']);
}
if (isset($_GET['protocol_date'])) {
  $_SESSION['protocol_date'] = urldecode($_GET['protocol_date']);
}

if (isset($_POST['save_status'])) {
  if ($_POST['status'] == "") {
    $error = "Please select a status!";
  } else {
    $_SESSION['shoeing_protocol']["status"] = $_POST['status'];
  }
}

// Need to connect to the database for data retrieval. The $conn object will be used to communicate with the SQL database
$conn = new mysqli('sql.freedb.tech', 'freedb_Youssef', 'fp53R5UKVn*M@XW', 'freedb_Equiterra');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

retrieve_shoeing_protocol_details($conn);

$conn->close();     // Close connection to the database
// ...
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
</script>

<html>

<head>
  <link rel="stylesheet" href="style.css">

  <head>

  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <?php include 'navbar.php'; ?>
        <div class="onboarding-overlay-inner shoe">
          <a href='horse.php'><button class='back-button'>< Horse</button></a>
          <br><br>
          <h1 class="returning__header">Shoeing Protocol Details</h1>
          <br>
          <?php
          // TODO: must be changed to the protocol's info from the database (using their horse name and date)
          if (isset($_SESSION['protocol_horse']) && isset($_SESSION['protocol_date'])) {
            echo "<h3 class='returning__text'>Horse: " . $_SESSION['shoeing_protocol']['horse_name'] . "</h3>";
            echo "<h3 class='returning__text'>Date: " . $_SESSION['shoeing_protocol']['date']  . "</h3>";
            echo "<h3 class='returning__text'>Left Front: " . $_SESSION['shoeing_protocol']['left_front']  . "</h3>";
            echo "<h3 class='returning__text'>Left Hind: " . $_SESSION['shoeing_protocol']['left_hind']  . "</h3>";
            echo "<h3 class='returning__text'>Right Front: " . $_SESSION['shoeing_protocol']['right_front']  . "</h3>";
            echo "<h3 class='returning__text'>Right Hind: " . $_SESSION['shoeing_protocol']['right_hind']  . "</h3>";

            if ($_SESSION['user_type'] == "Admin") {
              if (isset($_POST['edit']) && $_POST['edit'] == 'status') {
                echo "<form method='POST'><h3 class='returning__text'>Status: <select class='form-control edit' id='status' name='status' style='width: 200px; font-size: 18px;' value=" . $_SESSION['shoeing_protocol']["status"] . "required>
                    <option value=''>Select Status</option>
                    <option value='0'>0</option>
                    <option value='1'>1</option>
                  </select><input type='submit' name='save_status' value='Save' class='save-button'></h3></form>";
              } else {
                echo "<h3 class='returning__text'>Status: " . $_SESSION['shoeing_protocol']["status"] . "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='status'><input type='submit' value='Edit' class='red-button'></form></h3>";
              }
            } else {
              if ($_SESSION['shoeing_protocol']["status"] == 1){
                echo "<h3 class='returning__text'>Status: " . "Current" . "</h3>";
              }
              else { // Past protocol
                echo "<h3 class='returning__text'>Status: " . "Past" . "</h3>";
              }
              
            }

            echo "<h3 class='returning__text'>Notes: ";
            echo "<button class='expand-arrow' onclick='expandNotes()'>▼</button>";
            echo "<div class='conf-notes-short'>" . substr($_SESSION['shoeing_protocol']["notes"], 0, 50) . "</div>";
            echo "<div class='conf-notes-full' style='display: none;'>" . $_SESSION['shoeing_protocol']["notes"] . "</div>";
            echo "</h3>";

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