<?php
// Need to connect to the database for data retrieval. The $conn object will be used to communicate with the SQL database
$conn = new mysqli('sql.freedb.tech', 'freedb_Youssef', 'fp53R5UKVn*M@XW', 'freedb_Equiterra');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Include functions
require_once 'client_functions.php';
require_once 'barn_functions.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

retrieve_client_names($conn);
retrieve_all_barns($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $horse_name = $_POST["horse_name"];
  $gender = $_POST["gender"];
  $discipline = $_POST["discipline"];
  $height = $_POST["height"];
  $birthdate = $_POST["birthdate"];
  $breed = $_POST["breed"];
  $conf_notes = $_POST["conf_notes"];
  $owner = trim($_POST["owner"]);
  $barn = $_POST["barn"];
  $status = 1;
  debug_to_console($owner);
  debug_to_console($gender);
  $stmt_insert = $conn->prepare("CALL AddHorse(?,?,?,?,?,?,?,?,?,?)");
  // Bind parameters and execute the SQL statement
  $stmt_insert->bind_param("sssssssssi", $horse_name, $gender, $discipline, $height, $birthdate, $breed, $conf_notes, $barn, $owner, $status);
  $stmt_insert->execute();


  array_push($_SESSION['horses'], $horse_name);
  // Redirect to home page
  header('Location: horses.php');
  $conn->close();     
}

?>
<html>
<!-- Rest of your HTML code -->
<html>
  <head>
    <link rel="stylesheet" href="style.css">
  <head>
  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <div class="onboarding-overlay-inner returning">
          <a href='horses.php'><button class='back-button'>< Horses</button></a>
          <br>
          <h1 class="returning__header">Add Horse</h1>
          <form class="signin" method="post">
            <div class="form-group">
              <label for="horse_name">Horse Name</label>
              <input type="text" class="form-control" id="horse_name" name="horse_name" value="<?php echo isset($_POST['horse_name']) ? $_POST['horse_name'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="owner">Owner *</label>
              <select class="form-control rounded" id="owner" name="owner" value="<?php echo isset($_POST['owner']) ? $_POST['owner'] : '' ?>" required>
                <option value="">Select Owner</option>
                <?php
                // Check if the session variable exists and is not empty
                if (isset($_SESSION['clients']) && count($_SESSION['clients']) > 0) {
                  // Loop through the array and create the option elements. The options displayed are Client names, but the value they select is the client username
                  foreach ($_SESSION['clients'] as $client) {
                    $selected = isset($_POST['owner']) && $_POST['owner'] == $client['username'] ? 'selected' : ''; // Need the client username to update database
                    echo "<option value='{$client['username']}' {$selected}>{$client['name']}</option>"; // Display name but retrieve username
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="barn">Barn *</label>
              <select class="form-control rounded" id="barn" name="barn" value="<?php echo isset($_POST['barn']) ? $_POST['barn'] : '' ?>" required>
                <option value="">Select Barn</option>
                <?php
                // Check if the session variable exists and is not empty
                if (isset($_SESSION['barns']) && count($_SESSION['barns']) > 0) {
                  // Loop through the array and create the option elements
                  foreach ($_SESSION['barns'] as $barn) {
                    $selected = isset($_POST['barn']) && $_POST['barn'] == $customer ? 'selected' : '';
                    echo "<option value='{$barn}' {$selected}>{$barn}</option>";
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="breed">Breed</label>
              <Input type="text" class="form-control" id="breed" name="breed" value="<?php echo isset($_POST['breed']) ? $_POST['breed'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="gender">Gender</label>
              <select class="form-control rounded" id="gender" name="gender" value="<?php echo isset($_POST['gender']) ? $_POST['gender'] : '' ?>" required>
                <option value="">Select Gender</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
              </select>
            </div>
            <div class="form-group">
              <label for="height">Height</label>
              <input type="text" class="form-control" id="height" name="height" value="<?php echo isset($_POST['height']) ? $_POST['height'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="birthdate">Birthdate</label>
              <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo isset($_POST['birthdate']) ? $_POST['birthdate'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="discipline">Discipline</label>
              <input type="text" class="form-control" id="discipline" name="discipline" value="<?php echo isset($_POST['discipline']) ? $_POST['discipline'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="conf_notes">Conformation Notes</label>
              <textarea class="form-control" id="conf_notes" name="conf_notes" rows="4"><?php echo isset($_POST['conf_notes']) ? $_POST['conf_notes'] : '' ?></textarea>
            </div>
            <?php
            if (isset($error)) {
              echo "<p style='color:red; text-align:center; font-size:20px;'>$error</p>";
            }
            ?>
            <button class="onboarding-form__btn returning__btn" type="submit">Add Horse</button>
            <label class="signup"> *If the horse owner (client) or barn does not already exist, you will need to add them first.</label>
          </form>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>