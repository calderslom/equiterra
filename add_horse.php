<?php
// Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $horse_name = $_POST["horse_name"];
  $gender = $_POST["gender"];
  $discipline = $_POST["discipline"];
  $height = $_POST["height"];
  $birthdate = $_POST["birthdate"];
  $breed = $_POST["breed"];
  $conf_notes = $_POST["conf_notes"];
  $owner = $_POST["owner"];
  $barn = $_POST["barn"];

  // TODO: will need to be added to the horse's info from the database
  array_push($_SESSION['horses'], $horse_name);
  // Redirect to home page
  header('Location: home.php');
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
                if (isset($_SESSION['customers']) && count($_SESSION['customers']) > 0) {
                  // Loop through the array and create the option elements
                  foreach ($_SESSION['customers'] as $customer) {
                    $selected = isset($_POST['owner']) && $_POST['owner'] == $customer ? 'selected' : '';
                    echo "<option value='{$customer}' {$selected}>{$customer}</option>";
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
                <option value="Admin">Male</option>
                <option value="Client">Female</option>
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
              <label for="conf_notes">Conf Notes</label>
              <Input type="text" class="form-control" id="conf_notes" name="conf_notes" value="<?php echo isset($_POST['conf_notes']) ? $_POST['conf_notes'] : '' ?>" required />
            </div>
            <?php
            if (isset($error)) {
              echo "<p style='color:red; text-align:center; font-size:20px;'>$error</p>";
            }
            ?>
            <button class="onboarding-form__btn returning__btn" type="submit">Add Horse</button>
            <label class="signup"> *If the horse owner (customer) or barn does not exist yet, you might need to add them first.</label>
          </form>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>