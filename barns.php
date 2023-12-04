<html>
  <head>
    <link rel="stylesheet" href="style.css">
  <head>
  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <?php include 'navbar.php'; ?>
        <img class="barns-image" src="images/barns.png" alt="Barns">
        <div class="onboarding-overlay-inner table">
          <?php
          // TODO: must be changed to the barns info from the database (using their username)
            if (isset($_SESSION['barns']) && count($_SESSION['barns']) > 0) {
              if ($_SESSION['user_type'] == "Admin") {
                echo "<a href='add_barn.php'><button class='add-button'>Add Barn +</button></a>";
              }
              echo "<table class='horse-table'>";
              echo "<tr><th>Name</th><th>Action</th></tr>";
              // Output data of each row
              foreach($_SESSION['barns'] as $barn) {
                echo "<tr>";
                echo "<td>" . $barn . "</td>";
                echo "<td><a href='barn.php?barn_name=" . urlencode($barn) . "'><button class='table-button'>View/Edit</button></a></td>";
                echo "</tr>";
              }
              echo "</table>";
            } else {
              echo "<div class='returning__header'>No barns in database <a href='add_barn.php'><button class='add-button'>Add Barn +</button></a></div>";
            }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>