<?php
// Start the session
session_start();

$_SESSION['horses'] = array("Spirit" => "spongebob.squarepants", "Captain" => "squidward.tentacles", "Rain" => "mr.crabs");
$_SESSION['barns'] = array("Big Barn", "Bombastic Barn", "Baby Barn");
$_SESSION['customers'] = array("mr.crabs", "squidward.tentacles", "spongebob.squarepants");
// Assuming you have a form with 'email_or_username' and 'password' fields
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email_or_username = $_POST['email_or_username'];
  $password = $_POST['password'];

  // for now Check if username or email = "omarragab" and password = "1234"
  // TODO: will need to be changed to the user's info from the database
  if ($email_or_username == "omar.ragab" && $password == "1234") {
    // Set session variables
    $_SESSION['username'] = $email_or_username;
    $_SESSION['name'] = 'Omar Ragab';
    $_SESSION['user_type'] = 'Admin';
    $_SESSION['email'] = 'omarmsragab2003@gmail.com';
    $_SESSION['phone_number'] = '9023290244';
    $_SESSION['password'] = $password;
    // Redirect to home page
    header('Location: home.php');
  } else if ($email_or_username == "aidan.smith" && $password == "1234") {
    // Set session variables
    $_SESSION['username'] = $email_or_username;
    $_SESSION['name'] = 'Aidan Smith';
    $_SESSION['user_type'] = 'Client';
    $_SESSION['email'] = 'aidansmith@gmail.com';
    $_SESSION['phone_number'] = '4031231234';
    $_SESSION['password'] = $password;
    // Redirect to home page
    header('Location: home.php');
  } else {
    // User not found, display error message
    $error = "Invalid username or password!";
  }

  // Connect to your database
  $conn = new mysqli('sql.freedb.tech', 'freedb_Youssef', 'fp53R5UKVn*M@XW', 'freedb_Equiterra');

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 
}
  // Query the database to find the user
//   $sql = "SELECT * FROM Farrier WHERE Fusername = ? AND Fpassword = ?";
//   $stmt = $conn->prepare($sql);
//   $stmt->bind_param('ss', $email_or_username, $password);
//   $stmt->execute();
//   $result = $stmt->get_result();

//   echo "Hello" . $result;

//   if ($result->num_rows > 0) {
//     // User found, set session variables
//     $user = $result->fetch_assoc();
//     //$_SESSION['username'] = $user['username'];
//     $_SESSION['user_type'] = $user['user_type'];
    

//     // Redirect to home page
//     header('Location: home.php');
    
//   } else {
//     // User not found, display error message
//     $error = "Invalid username or password!";
//   }

//   $conn->close();
//}
?>

<html>
  <head>
    <link rel="stylesheet" href="style.css">
  <head>
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
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>