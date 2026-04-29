<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
// Include functions
require_once 'utility.php';
require_once 'client_functions.php';
require_once 'horse_functions.php';
require_once 'update_database.php';
// Connect to the database for data retrieval; use $conn for DB access
require_once 'db_config.php';




if (isset($_GET['horse_name'])) { // Session 'horse_name' now contains the name of the horse. This is the key for the Horse table
  $_SESSION['horse_name'] = urldecode($_GET['horse_name']);
}
if (isset($_POST['save_conf_notes'])) {
  $_SESSION['horse']['conf_notes'] = $_POST['conf_notes'];
  update_conformation_notes($conn);
}

// Handle record deletion (Admin only - Client can only view/add record)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_medical_record_date']) && $_SESSION['user_type'] == "Admin") {
  $del_hname = $_POST['delete_medical_record_horse'];
  $del_date  = $_POST['delete_medical_record_date'];
  $stmt_del  = $conn->prepare("CALL DeleteMedicalRecord(?, ?)");
  $stmt_del->bind_param("ss", $del_hname, $del_date);
  $stmt_del->execute();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_protocol_date']) && $_SESSION['user_type'] == "Admin") {
  $del_hname = $_SESSION['horse_name'];
  $del_date  = $_POST['delete_protocol_date'];
  delete_shoeing_protocol($conn, $del_hname, $del_date);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_analysis_path']) && $_SESSION['user_type'] == "Admin") {
  $del_path = $_POST['delete_analysis_path'];
  delete_analysis($conn, $del_path);
}

retrieve_medical_records($conn);
retrieve_horse_details($conn);
retrieve_shoeing_protocol_dates($conn);
retrieve_analysis_dates_types($conn);



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

  function searchAnalysisTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchAnalysisInput");
    filter = input.value.toUpperCase();
    table = document.getElementsByClassName("horse-table")[1];
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

  /**
   * Renders the Medical Records table section on the horse detail page.
   * 
   * Displays a searchable table of all medical records associated with the
   * current horse, retrieved from $_SESSION['medical_records'].
   * 
   * Each row shows the date, a truncated ailment description, and the
   * attending practitioner. A 'View' button links to medical_record.php
   * passing the horse name and date as URL parameters.
   * 
   * Admins see an additional 'Delete' button per row with a confirmation
   * prompt before deletion. Deletion is handled via POST to horse.php
   * which calls the DeleteMedicalRecord stored procedure.
   * 
   * Both Admins and Clients see the 'Add Record +' button which links
   * to add_medical_record.php.
   * 
   * Search is handled by searchMedicalTable() in the script block, which
   * targets the third .horse-table element on the page (index 2).
   * 
   * Session variables read:
   * - $_SESSION['medical_records'] - array of medical record tuples
   * - $_SESSION['user_type']       - 'Admin' or 'Client'
   * 
   * @see searchMedicalTable() in the script block
   * @see DeleteMedicalRecord stored procedure in init.sql
   * @see add_medical_record.php
   * @see medical_record.php
   */
  function searchMedicalTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchMedicalInput");
    filter = input.value.toUpperCase();
    table = document.getElementsByClassName("horse-table")[2];
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
</head>

<body>
  <div class="onboarding-overlay">
    <div class="onboarding-overlay-outer">
      <?php include 'navbar.php'; ?>
      <!-- Horse Details Card -->
      <div class="onboarding-overlay-inner info">
        <?php if (isset($_SESSION['horse'])): ?>
          <?php
          echo "<div style='display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; width: 100%;'>";
          echo "<div></div>"; // empty left column for balance
          echo "<h1 class='returning__header'>" . htmlspecialchars($_SESSION['horse']['name']) . "</h1>";
          echo "<div style='display: flex; justify-content: flex-end;'><a href='images.php'><button class='add-button'>View Photos</button></a></div>";
          echo "</div>";
          ?>
          <?php echo "<div class='user-info'>";
          echo "<div class='left-column'>";
          echo "<h3 class='returning__text'><span class='detail-label'>Owner:</span> " . $_SESSION['horse']['owner'] . "</h3>";
          echo "<h3 class='returning__text'><span class='detail-label'>Barn:</span> " . $_SESSION['horse']['barn'] . "</h3>";
          echo "<h3 class='returning__text'><span class='detail-label'>Breed:</span> " . $_SESSION['horse']['breed'] . "</h3>";
          if ($_SESSION['user_type'] == "Admin") {
            if (isset($_POST['edit']) && $_POST['edit'] == 'conf_notes') {
              echo "<form method='POST'><h3 class='returning__text'><span class='detail-label'>Conformation Notes:</span>";
              echo "<input type='submit' name='save_conf_notes' value='Save' class='conf-save'>";
              echo "<div><textarea class='edit-input' type='conf_notes' name='conf_notes' style='height: 100px; width: 400px;'>" . $_SESSION['horse']['conf_notes'] . "</textarea></div>";
              echo "</h3></form>";
            } else {
              echo "<h3 class='returning__text'><span class='detail-label'>Conformation Notes:</span> ";
              echo "<button class='expand-arrow' onclick='expandNotes()'>▼</button>";
              echo "<form method='POST' style='display:inline;'><input type='hidden' name='edit' value='conf_notes'><input type='submit' value='Edit' class='conf-button'></form>";
              $first_line = explode("\n", nl2br($_SESSION['horse']['conf_notes']))[0];
              echo "<div class='conf-notes-short'>" . substr($first_line, 0, 50) . "...</div>";
              echo "<div class='conf-notes-full' style='display: none;'>" . nl2br($_SESSION['horse']['conf_notes']) . "</div>";
              echo "</h3>";
            }
          } else {
            echo "<h3 class='returning__text'><span class='detail-label'>Conformation Notes:</span> ";
            echo "<button class='expand-arrow' onclick='expandNotes()'>▼</button>";
            $first_line = explode("\n", nl2br($_SESSION['horse']['conf_notes']))[0];
            echo "<div class='conf-notes-short'>" . substr($first_line, 0, 50) . "...</div>";
            echo "<div class='conf-notes-full' style='display: none;'>" . nl2br($_SESSION['horse']['conf_notes']) . "</div>";
            echo "</h3>";
          }
          echo "</div>";
          echo "<div>";
          echo "<h3 class='returning__text'><span class='detail-label'>Gender:</span> " . $_SESSION['horse']['gender'] . "</h3>";
          echo "<h3 class='returning__text'><span class='detail-label'>Discipline:</span> " . $_SESSION['horse']['discipline'] . "</h3>";
          echo "<h3 class='returning__text'><span class='detail-label'>Height:</span> " .
            $_SESSION['horse']['height'] . "<span class='unit'> hh</span></h3>";
          echo "<h3 class='returning__text'><span class='detail-label'>Birthdate:</span> " . $_SESSION['horse']['birthdate'] . "</h3>";
          echo "</div>";
          echo "</div>";
          ?>
        <?php else: ?>
          <h1 class="returning__header">Horse Details</h1>
          <div class='returning__header'>Horse record could not be found. Please go back and try again.</div>
        <?php endif; ?>
      </div>
      <!-- Shoeing Protocol Card -->
      <div class="onboarding-overlay-inner table narrower">
        <div class='horse-header' style='display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; width: 100%;'>
          <div></div>
          <h1 class="returning__header">Shoeing Protocols</h1>
          <div class='photo-btn'>
            <?php if ($_SESSION['user_type'] == "Admin"): ?>
              <a href='add_shoeing_protocol.php'><button class='add-button'>Add Protocol +</button></a>
            <?php endif; ?>
          </div>
        </div>
        <?php
        if (isset($_SESSION['shoeing_protocols']) && count($_SESSION['shoeing_protocols']) > 0) {
          echo "<div class='action-bar'>";
          echo "<div class='search-container'><input class='search-table' type='text' id='searchInput' onkeyup='searchTable()' placeholder='Search shoeing protocols..'></div>";
          echo "</div>";
          echo "<table class='horse-table narrow'>";
          echo "<tr><th>Date</th><th>Action</th></tr>";
          foreach ($_SESSION['shoeing_protocols'] as $protocol) {
            echo "<tr>";
            echo "<td>" . $protocol['date'] . "</td>";
            echo "<td>";
            echo "<a href='shoeing_protocol.php?protocol_date=" . urlencode($protocol['date']) . "&protocol_horse=" . urlencode($protocol['horse_name']) . "'>";
            echo "<button class='table-button'>" . ($_SESSION['user_type'] == "Admin" ? "View/Edit" : "View") . "</button></a>";
            if ($_SESSION['user_type'] == "Admin") {
              echo "<form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this protocol?\");'>";
              echo "<input type='hidden' name='delete_protocol_date' value='" . $protocol['date'] . "'>";
              echo "<input type='submit' class='table-button' style='background-color: darkred; margin-left: 5px;' value='Delete'>";
              echo "</form>";
            }
            echo "</td>";
            echo "</tr>";
          }
          echo "</table>";
        } else {
          if ($_SESSION['user_type'] == "Admin") {
            echo "<div class='returning__header'>No shoeing protocols in database</div>";
          } else {
            echo "<div class='returning__header'>No shoeing protocols in database</div>";
          }
        }
        ?>
      </div>
      <!-- Analysis Card -->
      <div class="onboarding-overlay-inner table narrower">
        <div class='horse-header' style='display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; width: 100%;'>
          <div></div>
          <h1 class="returning__header">Analysis</h1>
          <div class='photo-btn'>
            <?php if ($_SESSION['user_type'] == "Admin"): ?>
              <a href='add_analysis.php'><button class='add-button'>Add Analysis +</button></a>
            <?php endif; ?>
          </div>
        </div>
        <?php
        if (isset($_SESSION['analysis_table']) && count($_SESSION['analysis_table']) > 0) {
          echo "<div class='action-bar'>";
          echo "<div class='search-container'><input class='search-table' type='text' id='searchAnalysisInput' onkeyup='searchAnalysisTable()' placeholder='Search analysis..'></div>";
          echo "</div>";
          echo "<table class='horse-table narrow'>";
          echo "<tr><th>Date</th><th>Type</th><th>Action</th></tr>";
          foreach ($_SESSION['analysis_table'] as $analysis) {
            echo "<tr>";
            echo "<td>" . $analysis['date'] . "</td>";
            echo "<td>" . $analysis['type'] . "</td>";
            echo "<td>";
            echo "<a href='analysis.php?analysis_date=" . urlencode($analysis['date']) . "&analysis_type=" . urlencode($analysis['type']) . "&analysis_horse=" . urlencode($_SESSION['horse_name']) . "'>";
            echo "<button class='table-button'>View</button></a>";
            if ($_SESSION['user_type'] == "Admin") {
              echo "<form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this analysis record?\");'>";
              echo "<input type='hidden' name='delete_analysis_path' value='" . htmlspecialchars($analysis['analysis_path']) . "'>";
              echo "<input type='submit' class='table-button' style='background-color: darkred; margin-left: 5px;' value='Delete'>";
              echo "</form>";
            }
            echo "</td>";
            echo "</tr>";
          }
          echo "</table>";
        } else {
          if ($_SESSION['user_type'] == "Admin") {
            echo "<div class='returning__header'>No analysis in database</div>";
          } else {
            echo "<div class='returning__header'>No analysis in database</div>";
          }
        }
        ?>
      </div>
      <!-- Medical Records Card -->
      <div class="onboarding-overlay-inner table narrower">
        <div class='horse-header' style='display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; width: 100%;'>
          <div></div>
          <h1 class="returning__header">Medical Records</h1>
          <div class='photo-btn'>
            <?php if ($_SESSION['user_type'] == "Admin" || $_SESSION['user_type'] == "Client"): ?>
              <a href='add_medical_record.php'><button class='add-button'>Add Record +</button></a>
            <?php endif; ?>
          </div>
        </div>
        <?php
        if (isset($_SESSION['medical_records']) && count($_SESSION['medical_records']) > 0) {
          echo "<div class='action-bar'>";
          echo "<div class='search-container'><input class='search-table' type='text' id='searchMedicalInput' onkeyup='searchMedicalTable()' placeholder='Search medical records..'></div>";
          echo "</div>";
          echo "<table class='horse-table narrow'>";
          echo "<tr><th>Date</th><th>Practitioner</th><th>Action</th></tr>";
          foreach ($_SESSION['medical_records'] as $record) {
            echo "<tr>";
            echo "<td>" . $record['date'] . "</td>";
            echo "<td>" . $record['pname'] . "</td>";
            echo "<td>";
            echo "<a href='medical_record.php?medical_record_date=" . urlencode($record['date']) . "&medical_record_horse=" . urlencode($record['hname']) . "'>";
            echo "<button class='table-button'>View</button></a>";
            if ($_SESSION['user_type'] == "Admin") {
              echo "<form method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this record?\");'>";
              echo "<input type='hidden' name='delete_medical_record_date' value='" . $record['date'] . "'>";
              echo "<input type='hidden' name='delete_medical_record_horse' value='" . $record['hname'] . "'>";
              echo "<input type='submit' class='table-button' style='background-color: darkred; margin-left: 5px;' value='Delete'>";
              echo "</form>";
            }
            echo "</td>";
            echo "</tr>";
          }
          echo "</table>";
        } else {
          echo "<div class='returning__header'>No medical records in database</div>";
        }
        ?>
      </div>
      <p class="overlay-copyright">Equiterra &copy;2026</p>
    </div>
  </div>
</body>

</html>