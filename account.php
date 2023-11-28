<html>
  <head>
    <link rel="stylesheet" href="style.css">
  <head>
  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <?php include 'navbar.php'; ?>
        <div class="onboarding-overlay-inner returning">
          <h1 class="returning__header">Account Info</h1>
          <?php
            // TODO: must be changed to the user's info from the database (using their username)
            if (isset($_SESSION['username'])) {
              echo "<h3 class='returning__text'>Name: " . $_SESSION['name'] . "</h3>";
              echo "<h3 class='returning__text'>Username: " . $_SESSION['username'] . "</h3>";
              echo "<h3 class='returning__text'>Email: " . $_SESSION['email'] . "</h3>";
              echo "<h3 class='returning__text'>Phone Number: " . $_SESSION['phone_number'] . "</h3>";
              echo "<h3 class='returning__text'>Password: " . $_SESSION['password'] . "</h3>";
            }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>