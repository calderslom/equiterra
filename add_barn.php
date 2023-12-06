<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $barn_name = $_POST["barn_name"];
  $contact = $_POST["contact"];
  $email = $_POST["email"];
  $phone_number = $_POST["phone_number"];
  $street_number = $_POST["street_number"];
  $street_name = $_POST["street_name"];
  $city = $_POST["city"];
  $province = $_POST["province"];

  // TODO: will need to be added to the horse's info from the database
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format!";
  } elseif (!preg_match("/^[0-9]{10}$/", $phone_number)) {
    $error = "Invalid phone number format! Format: 1234567890";
  } else {
    array_push($_SESSION['barns'], $barn_name);
    // Redirect to home page
    header('Location: barns.php');
  }
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
          <a href='barns.php'><button class='back-button'>< Barns</button></a>
          <br>
          <h1 class="returning__header">Add barn</h1>
          <form class="signin" method="post">
            <div class="form-group">
              <label for="barn_name">Barn Name</label>
              <input type="text" class="form-control" id="barn_name" name="barn_name" value="<?php echo isset($_POST['barn_name']) ? $_POST['barn_name'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="contact">Contact</label>
              <Input type="contact" class="form-control" id="contact" name="contact" value="<?php echo isset($_POST['contact']) ? $_POST['contact'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="text" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="phone_number">Phone Number</label>
              <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?php echo isset($_POST['phone_number']) ? $_POST['phone_number'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="street_number">Street Number</label>
              <input type="text" class="form-control" id="street_number" name="street_number" value="<?php echo isset($_POST['street_number']) ? $_POST['street_number'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="street_name">Street Name</label>
              <input type="text" class="form-control" id="street_name" name="street_name" value="<?php echo isset($_POST['street_name']) ? $_POST['street_name'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="city">City</label>
              <input type="text" class="form-control" id="city" name="city" value="<?php echo isset($_POST['city']) ? $_POST['city'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="province">Province</label>
              <Input type="text" class="form-control" id="province" name="province" value="<?php echo isset($_POST['province']) ? $_POST['province'] : '' ?>" required />
            </div>
            <?php
            if (isset($error)) {
              echo "<p style='color:red; text-align:center; font-size:20px;'>$error</p>";
            }
            ?>
            <button class="onboarding-form__btn returning__btn" type="submit">Add Barn</button>
          </form>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>