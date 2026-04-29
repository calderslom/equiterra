<?php
// Always destroy any existing session on the login page
if (session_status() == PHP_SESSION_ACTIVE) {
    session_unset();
    session_destroy();
}
session_start();

// Include functions
require_once 'client_functions.php';
require_once 'retrieval_functions.php';
// Connect to the database for data retrieval; use $conn for DB access
require_once 'db_config.php';

// Assuming you have a form with 'email_or_username' and 'password' fields
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email_or_username = $_POST['email_or_username'];
  $password = $_POST['password'];

  //Query the database to find the user
  $user = retrieve_user_login($conn, $email_or_username, $password);

  if ($user !== false) {
        $_SESSION['username'] = $user['Username'];
        $_SESSION['user_type'] = $user['User_type'];
        header('Location: home.php');
        exit();
    } else {
        $error = "Invalid username or password.";
    }

  $conn->close();
}
?>
<html>

<head>
  <link rel="stylesheet" href="style.css">

</head>

<body>
  <div class="onboarding-overlay">
    <div class="onboarding-overlay-outer">
      <div class="onboarding-overlay-inner returning">
        <img class="returning__image" src="images/logo.gif" alt="Horse logo">
        <h1 class="returning__header">Sign in to Equiterra</h1>
        <form class="signin" method="post">
          <div class="form-group">
            <label for="email_or_username">Email or Username</label>
            <Input class="form-control" id="email_or_username" name="email_or_username" aria-describedby="emailHelp" />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <Input type="password" class="form-control" id="password" name="password" aria-describedby="emailHelp" />
          </div>
          <?php
          if (isset($error)) {
            echo "<p style='color:red; text-align:center; font-size:20px;'>$error</p>";
          }
          ?>
          <button class="onboarding-form__btn returning__btn" type="submit">Sign in</button>
          <label class="signup"> Not a member? <a href="signup.php">Sign up</a> now!</label>
        </form>
      </div>
      <p class="overlay-copyright">Equiterra &copy;2026</p>
    </div>
  </div>
</body>

</html>