<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
// Connect to the database for data retrieval; use $conn for DB access
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $horse = $_SESSION["horse_name"];
  $date = $_POST["date"];
  $type = $_POST["type"];
  $details = $_POST["details"];
  $analysis_path = "uploads/analysis/" . $horse . "_" . $type . "_" . $date . ".pdf";

  $stmt_insert = $conn->prepare("CALL AddAnalysis(?,?,?,?,?)");
  $stmt_insert->bind_param("sssss", $filepath, $date, $type, $horse, $details);
  $stmt_insert->execute();

  // Redirect to home page
  header('Location: horse.php');
}

$conn->close();
?>
<html>
<!-- Rest of your HTML code -->
<html>

<head>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="onboarding-overlay">
    <div class="onboarding-overlay-outer">
      <div class="onboarding-overlay-inner returning">
        <a href='horse.php'><button class='back-button'>&lt; 
          <?php echo htmlspecialchars($_SESSION['horse_name']); ?>
        </button></a>
        <br>
        <h1 class="returning__header">Add Analysis</h1>
        <form class="signin" method="post">
          <div class="form-group">
            <label for="barn_name">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : '' ?>" required />
          </div>
          <div class="form-group">
            <label for="type">Type</label>
            <select class="form-control rounded" id="type" name="type" value="<?php echo isset($_POST['type']) ? $_POST['type'] : '' ?>" required>
              <option value="">Select Type</option>
              <option value="Hoofbeat">Hoofbeat</option>
              <option value="Radiograph">Radiograph</option>
              <option value="Equigate">Equigate</option>
              <option value="Posture">Posture</option>
            </select>
          </div>
          <div class="form-group">
            <label for="details">Details</label>
            <textarea class="form-control" id="details" name="details" rows="4" required><?php echo isset($_POST['details']) ? $_POST['details'] : '' ?></textarea>
          </div>
          <button class="onboarding-form__btn returning__btn" type="submit">Add Analysis</button>
        </form>
      </div>
      <p class="overlay-copyright">Equiterra &copy;2026</p>
    </div>
  </div>
</body>

</html>