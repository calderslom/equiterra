<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $phone_number = $_POST["phone_number"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format.";
  } elseif (!preg_match("/^[0-9]{10}$/", $phone_number)) {
    $error = "Invalid phone number format. Format: 1234567890";
  } elseif ($password !== $confirm_password) {
    $error = "Passwords do not match.";
  } else {
    // Continue with signup process
  }
}
?>
<html>
<!-- Rest of your HTML code -->
<html>
  <head>
    <link rel="stylesheet" href="style.php">
  <head>
  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <div class="onboarding-overlay-inner returning">
          <img class="returning__image" src="images/logo.png" alt="Horse logo">
          <h1 class="returning__header">Sign up to Farrier&nbsp;Site</h1>
          <form class="signin" method="post">
            <div class="form-group">
              <label for="first_name">User Type</label>
              <select class="form-control rounded" id="user_type" name="user_type" value="<?php echo isset($_POST['user_type']) ? $_POST['user_type'] : '' ?>" required>
                  <option value="">Select User Type</option>
                  <option value="Admin">Admin</option>
                  <option value="Client">Client</option>
              </select>
            </div>
            <div class="form-group">
              <label for="first_name">Email Address</label>
              <input type="text" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="last_name">Last Name</label>
              <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="phone_number">Phone Number</label>
              <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?php echo isset($_POST['phone_number']) ? $_POST['phone_number'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <Input type="password" class="form-control" id="password" name="password" aria-describedby="emailHelp" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm Password</label>
              <Input type="password" class="form-control" id="confirm_password" name="confirm_password" aria-describedby="emailHelp" value="<?php echo isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '' ?>" required />
            </div>
            <?php
            if (isset($error)) {
              echo "<p style='color:red; text-align:center; font-size:20px;'>$error</p>";
            }
            ?>
            <button class="onboarding-form__btn returning__btn" type="submit">Sign up</button>
            <label class="signup"> Already a member? <a href="index.php">Sign in</a> now!</label>
          </form>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>