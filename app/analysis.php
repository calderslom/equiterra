<?php
// Check if a session is already ongoing - start one if not.
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
// Include functions
require_once 'retrieval_functions.php';
require_once 'horse_functions.php';
require_once 'update_database.php';
require_once 'utility.php';
// Connect to the database for data retrieval; use $conn for DB access
require_once 'db_config.php';


if (isset($_GET['horse_name'])) {
  $_SESSION['horse_name'] = urldecode($_GET['horse_name']);
}

if (isset($_GET['analysis_date'])) {
  $_SESSION['analysis_date'] = urldecode($_GET['analysis_date']);
}

if (isset($_GET['analysis_type'])) {
  $_SESSION['analysis_type'] = urldecode($_GET['analysis_type']);
}

if (isset($_POST['save_conf_notes'])) {
  update_analysis_details($conn, $_POST['details']);
}

retrieve_analysis_details($conn);

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
</script>

<html>

<head>
  <link rel="stylesheet" href="style.css">

</head>

<body>
  <div class="onboarding-overlay">
    <div class="onboarding-overlay-outer">
      <?php include 'navbar.php'; ?>
      <div class="onboarding-overlay-inner returning">
        <a href='horse.php'><button class='back-button'>
            < Horse</button></a>
        <br>
        <h1 class="returning__header">Analysis Details</h1>
        <br>
        <?php
        if (isset($_SESSION['username'])) {
          echo "<h3 class='returning__text'>Horse: " . $_SESSION['horse_name'] . "</h3>";
          echo "<h3 class='returning__text'>Date: " . $_SESSION['analysis_date'] . "</h3>";
          echo "<h3 class='returning__text'>Type: " . $_SESSION['analysis_type'] . "</h3>";

          if ($_SESSION['user_type'] == "Admin") {
            if (isset($_POST['edit']) && $_POST['edit'] == 'details') {
              echo "<form method='POST'><h3 class='returning__text'>Details:";
              echo "<input type='submit' name='save_conf_notes' value='Save' class='conf-save'>";
              echo "<div><textarea class='edit-input' type='details' name='details' style='height: 100px; width: 400px;'>" . $_SESSION['analysis']['details'] . "</textarea></div>";
              echo "</h3></form>";
            } else {
              echo "<h3 class='returning__text'>Details: ";
              echo "<button class='expand-arrow' onclick='expandNotes()'>▼</button>";
              echo "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='details'><input type='submit' value='Edit' class='conf-button'></form>";
              $first_line = explode("\n", nl2br($_SESSION['analysis']['details']))[0];
              echo "<div class='conf-notes-short'>" . substr($first_line, 0, 50) . "...</div>";
              echo "<div class='conf-notes-full' style='display: none;'>" . nl2br($_SESSION['analysis']['details']) . "</div>";
              echo "</h3>";
            }
          } else {
            echo "<h3 class='returning__text'>Details: ";
            echo "<button class='expand-arrow' onclick='expandNotes()'>▼</button>";
            $first_line = explode("\n", nl2br($_SESSION['analysis']['details']))[0];
            echo "<div class='conf-notes-short'>" . substr($first_line, 0, 50) . "...</div>";
            echo "<div class='conf-notes-full' style='display: none;'>" . nl2br($_SESSION['analysis']['details']) . "</div>";
            echo "</h3>";
          }
        }
        ?>
      </div>
      <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
    </div>
  </div>
</body>

</html>