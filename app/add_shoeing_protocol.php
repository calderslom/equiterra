<?php
// Connect to the database for data retrieval; use $conn for DB access
require_once 'db_config.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// horse name is still storesd in $_SESSION['horse_name']

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $horse = $_SESSION['horse_name'];
  $date = $_POST["date"];
  $left_front = $_POST["left_front"];
  $left_hind = $_POST["left_hind"];
  $right_front = $_POST["right_front"];
  $right_hind = $_POST["right_hind"];
  $status = $_POST["status"];
  $notes = $_POST["notes"];
  $stmt_insert = $conn->prepare("CALL AddShoeingProtocol(?,?,?,?,?,?,?,?)");
  // Bind parameters and execute the SQL statement
  $stmt_insert->bind_param("ssssssss", $horse, $date, $left_front, $right_front, $left_hind, $right_hind, $status, $notes);
  $stmt_insert->execute();


  array_push($_SESSION['shoeing_protocols'], array("date" => $date));
  // Redirect to horse page
  header('Location: horse.php');
  $conn->close();     // Close connection to the database
}

?>
<html>
<html>
  <head>
    <link rel="stylesheet" href="style.css">
  <head>
  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <div class="onboarding-overlay-inner returning">
          <a href='horse.php'><button class='back-button'>< Horse</button></a>
          <br>
          <h1 class="returning__header">Add Shoeing Protocol</h1>
          <form class="signin" method="post">
            <div class="form-group">
              <label for="barn_name">Date</label>
              <input type="date" class="form-control" id="date" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="left_front">Left Front</label>
              <Input type="text" class="form-control" id="left_front" name="left_front" value="<?php echo isset($_POST['left_front']) ? $_POST['left_front'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="left_hind">Left Hind</label>
              <input type="text" class="form-control" id="left_hind" name="left_hind" value="<?php echo isset($_POST['left_hind']) ? $_POST['left_hind'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="right_front">Right Front</label>
              <input type="text" class="form-control" id="right_front" name="right_front" value="<?php echo isset($_POST['right_front']) ? $_POST['right_front'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="right_hind">Right Hind</label>
              <input type="text" class="form-control" id="right_hind" name="right_hind" value="<?php echo isset($_POST['right_hind']) ? $_POST['right_hind'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control" id="status" name="status" required>
                <option value="">Select status</option>
                <option value="0" <?php echo (isset($_POST['status']) && $_POST['status'] == '0') ? 'selected' : '' ?>>Past</option>
                <option value="1" <?php echo (isset($_POST['status']) && $_POST['status'] == '1') ? 'selected' : '' ?>>Current</option>
              </select>
            </div>
            <div class="form-group">
              <label for="notes">Notes</label>
              <textarea class="form-control" id="notes" name="notes" rows="4"><?php echo isset($_POST['notes']) ? $_POST['notes'] : '' ?></textarea>
            </div>
            <?php
            if (isset($error)) {
              echo "<p style='color:red; text-align:center; font-size:20px;'>$error</p>";
            }
            ?>
            <button class="onboarding-form__btn returning__btn" type="submit">Add Shoeing Protocol</button>
          </form>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>