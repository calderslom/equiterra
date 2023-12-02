<html>
  <head>
    <link rel="stylesheet" href="style.css">
  <head>
  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <?php include 'navbar.php'; ?>
        <img class="horses-image" src="images/horses.png" alt="Horses">
        <div class="onboarding-overlay-inner table">
          <?php
          session_start();
          // TODO: must be changed to the horses info from the database (using their username)
            if (isset($_SESSION['horses']) && count($_SESSION['horses']) > 0) {
              if ($_SESSION['user_type'] == "Admin") {
                echo "<a href='add_horse.php'><button class='add-button'>Add Horse +</button></a>";
              }
              echo "<table class='horse-table'>";
              echo "<tr><th>Name</th><th>Owner</th><th>Action</th></tr>";
              // Output data of each row
              foreach($_SESSION['horses'] as $horse => $owner) {
                echo "<tr>";
                echo "<td>" . $horse . "</td>";
                echo "<td>" . $owner . "</td>";
                echo "<td><a href='horse.php?horse_name=" . urlencode($horse) . "'><button class='table-button'>View/Edit</button></a></td>";
                echo "</tr>";
              }
              echo "</table>";
            } else {
              echo "<div class='returning__header'>No horses in database <button class='add-button'>Add Horse +</button></div>";
            }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>