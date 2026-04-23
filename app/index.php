<?php
// Start the session
if (session_status() != PHP_SESSION_NONE) {
  // Unset all of the session variables
  session_unset();
  // Destroy the session
  session_destroy();
} else {
  session_start();
}

// Include functions
require_once 'client_functions.php';
require_once 'retrieval_functions.php';
// Connect to the database for data retrieval; use $conn for DB access
require_once 'db_config.php';


$_SESSION['analysis_table'] = array(array("horse" => "Mouse", "date" => "2020-01-01", "type" => "Equitage", "details" => "A paragraph is a series of sentences that are organized and coherent, and are all related to a single topic. Almost every piece of writing you do that is longer than a few sentences should be organized into paragraphs."), array("horse" => "Mouse", "date" => "2020-02-02", "type" => "Radiograph", "details" => "A paragraph is a series of sentences that are organized and coherent, and are all related to a single topic. Almost every piece of writing you do that is longer than a few sentences should be organized into paragraphs."), array("horse" => "Mouse", "date" => "2020-03-03", "type" => "Posture", "details" => "A paragraph is a series of sentences that are organized and coherent, and are all related to a single topic. Almost every piece of writing you do that is longer than a few sentences should be organized into paragraphs."));
$_SESSION['analysis'] = array("horse" => "Mouse", "date" => "2020-01-01", "type" => "Checkup", "details" => "A paragraph is a series of sentences that are organized and coherent, and are all related to a single topic. Almost every piece of writing you do that is longer than a few sentences should be organized into paragraphs.");
// $_SESSION['horses'] = array("Spirit" => "spongebob.squarepants", "Captain" => "squidward.tentacles", "Rain" => "mr.crabs");
// $_SESSION['horse'] = array("name" => "Spirit", "gender" => "Male", "height" => "15", "birthdate" => "2020-01-01", "breed" => "Arabian", "discipline" => "Dressage", "owner" => "spongebob.squarepants", "barn" => "Big Barn", "conf_notes" => "A paragraph is a series of sentences that are organized and coherent, and are all related to a single topic. Almost every piece of writing you do that is longer than a few sentences should be organized into paragraphs. A paragraph is a series of sentences that are organized and coherent, and are all related to a single topic. Almost every piece of writing you do that is longer than a few sentences should be organized into paragraphs. A paragraph is a series of sentences that are organized and coherent, and are all related to a single topic. Almost every piece of writing you do that is longer than a few sentences should be organized into paragraphs. A paragraph is a series of sentences that are organized and coherent, and are all related to a single topic. Almost every piece of writing you do that is longer than a few sentences should be organized into paragraphs.");
// $_SESSION['barns'] = array("Big Barn", "Bombastic Barn", "Baby Barn");
// $_SESSION['customers'] = array("mr.crabs", "squidward.tentacles", "spongebob.squarepants");
// $_SESSION['invoices'] = array(array("number" => "1", "customer" => "mr.crabs", "horse" => "Spirit", "status" => "Paid", "price" => "100", "date" => "2020-01-01", "farrier" => "mr.crabs"), array("number" => "2", "customer" => "mr.crabs", "horse" => "Captain", "status" => "Unpaid", "price" => "200", "date" => "2020-02-02", "farrier" => "mr.crabs"), array("number" => "3", "customer" => "squidward.tentacles", "horse" => "Rain", "status" => "Paid", "price" => "300", "date" => "2020-03-03", "farrier" => "mr.crabs"));
// $_SESSION['invoice_services'] = array("checkup", "shoeing", "trimming");
// $_SESSION['customer'] = array("name" => "SpongeBob Squarepants", "username" => "spongebob.squarepants", "email" => "sponge@gmail.com", "phone_number" => "9021234567");
// $_SESSION['barn'] = array("name" => "Big Barn", "contact" => "John Doe", "email" => "barn@gmail.com", "phone_number" => "9021234567", "street_number" => "123", "street_name" => "Main Street", "city" => "Halifax", "province" => "NS", "postal_code" => "B3H 3H3");
// $_SESSION['dummy_horses'] = array("Spirit" => "Spongebob Squarepants", "Captain" => "Squidward Tentacles", "Rain" => "Mr. Crabs");

// Assuming you have a form with 'email_or_username' and 'password' fields
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email_or_username = $_POST['email_or_username'];
  $password = $_POST['password'];

  //Query the database to find the user
  $user = retrieve_user_login($conn, $email_or_username, $password);

  // This is simply a second (likely redundant) layer of error checking after the method above.
  if ($user['Username'] == $email_or_username || $user['Email'] == $email_or_username) {
    $_SESSION['username'] = $user['Username'];
    $_SESSION['user_type'] = $user['User_type'];
    // Redirect to home page
    header('Location: home.php');
    exit();
  } else {
    // User not found, display error message
    $error = "Invalid username or password.";
  }

  $conn->close();
}
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