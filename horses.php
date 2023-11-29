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
            if (isset($_SESSION['horses']) && count($_SESSION['horses']) > 0) {
              echo "<button class='add-button'>Add Horse +</button>";
              echo "<table class='horse-table'>";
              echo "<tr><th>Name</th><th>Action</th></tr>";
              // Output data of each row
              foreach($_SESSION['horses'] as $horse) {
                echo "<tr>";
                echo "<td>" . $horse . "</td>";
                echo "<td><button class='table-button'>View/Edit</button></td>";
                echo "</tr>";
              }
              echo "</table>";
            } else {
              echo "No horses available";
            }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>