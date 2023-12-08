<?php

// Start the session
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Include functions
require_once 'client_functions.php';

// Need to connect to the database for data retrieval. The $conn object will be used to communicate with the SQL database
$conn = new mysqli('sql.freedb.tech', 'freedb_Youssef', 'fp53R5UKVn*M@XW', 'freedb_Equiterra');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_type = 'Client';
  $name = $_POST["first_name"] . " " . $_POST["last_name"];
  $username = strtolower($_POST["first_name"] . "." . $_POST["last_name"]);
  $email = $_POST["email"];
  $phone_number = $_POST["phone_number"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];
  
  // debug_to_console($username);
  // $client_name = retrieve_client_name($conn, $username);
  // debug_to_console($client_name['Cname']);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format!";
  } elseif (!preg_match("/^\(\d{3}\) \d{3}-\d{4}$/", $phone_number)) {
    $error = "Invalid phone number! Proper format: (123) 456-7890";
  } elseif ($password != $confirm_password) {
    $error = "Passwords do not match!";
  } elseif (!retrieve_client_name($conn, $username)) { // This function will only return a name if the user ALREADY exists in the database
    $error = "A user with these credentials already exists. Please contact the administrator for further assistance.";
  } else {
    // TODO: will need to be added to the user's info from the database
    // $_SESSION['username'] = $username;
    // $_SESSION['name'] = $name;
    // $_SESSION['user_type'] = $user_type;
    // $_SESSION['email'] = $email;
    // $_SESSION['phone_number'] = $phone_number;
    // $_SESSION['password'] = $password;

    //array_push($_SESSION['customers'], $username);

    // Redirect to home page
    if ($user_type == 'Admin') {
      header('Location: customers.php');
    } else {
      header('Location: home.php');
    }
  }
}

$conn->close();
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
          <?php
          if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin') {
            echo "<a href='customers.php'><button class='back-button'>< Clients</button></a><br>";
            echo"<h1 class='returning__header'>Add Client</h1>";
          } else {
            echo"<img class='returning__image' src='images/logo.gif' alt='Horse logo'>";
            echo"<h1 class='returning__header'>Sign up to Equiterra</h1>";
          }
          ?>
          <form class="signin" method="post">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="last_name">Last Name</label>
              <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : '' ?>" required />
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
            <?php
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin') {
              echo "<button class='onboarding-form__btn returning__btn' type='submit'>Add Customer</button>";
            } else {
              echo "<button class='onboarding-form__btn returning__btn' type='submit'>Sign up</button>";
              echo "<label class='signup'> Already a member? <a href='index.php'>Sign in</a> now!</label>";
            }
            ?>
          </form>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>