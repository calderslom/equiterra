<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_GET['horse_name'])) {
  $_SESSION['horse_name'] = urldecode($_GET['horse_name']);
}
// ...
?>

<html>

<head>
  <link rel="stylesheet" href="style.css">

  <head>

  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <?php include 'navbar.php'; ?>
        <br><br><br>
        <div class="onboarding-overlay-inner table">
        <?php
          echo "<h1 class='returning__header'>Images for " . $_SESSION['horse_name'] . "</h1>";
          if (isset($_SESSION['images']) && is_array($_SESSION['images'])) {
            echo "<div class='image-container'>";
            foreach ($_SESSION['images'] as $image_url) {
              if (filter_var($image_url, FILTER_VALIDATE_URL)) {
                echo "<a href='" . htmlspecialchars($image_url) . "' target='_blank'>";
                echo "<img class='horse-image' src='" . htmlspecialchars($image_url) . "' alt='Image'>";
                echo "</a>";
              } else {
                echo "<div class='returning__header'>Invalid image URL: " . htmlspecialchars($image_url) . "</div>";
              }
            }
            echo "</div>";
          } else {
            echo "<div class='returning__header'>No images found.</div>";
          }
        ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>

</html>