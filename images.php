<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_GET['horse_name'])) {
  $_SESSION['horse_name'] = urldecode($_GET['horse_name']);
}

if (isset($_POST['url'])) {
  if (($key = array_search($_POST['url'], $_SESSION['images'])) !== false) {
    unset($_SESSION['images'][$key]);
  }
}

if (isset($_POST['add_url'])) {
  if (filter_var($_POST['add_url'], FILTER_VALIDATE_URL)) {
    array_push($_SESSION['images'], $_POST['add_url']);
  } else {
    $error = "Invalid Image URL!";
  }
}
// ...
?>

<script>
function addImageInput() {
  var addImageDiv = document.getElementById('add-image-div');
  addImageDiv.innerHTML = "<form method='POST'>" +
                          "<input type='text' name='add_url' placeholder='Insert image url...' style='width: 1070px; height: 32px; border-radius: 5px; border: 1px solid #ccc; margin-right: 10px;'>" +
                          "<input type='submit' class='add-button' value='Add'>" +
                          "</form>";
}
</script>

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
            if ($_SESSION['user_type'] == "Admin") {
              echo "<div id='add-image-div' style='text-align: right; display: flex; align-items: center; justify-content: flex-end;'>";
              if (isset($error)) {
                echo "<p style='color:red; text-align:center; font-size:20px; margin-right: 380px;'>$error</p>";
              }
              echo "<button id='add-image-button' class='add-button' onclick='addImageInput()'>Add Image +</button>";
              echo "</div>";
            }
            echo "<div class='image-container'>";
            foreach ($_SESSION['images'] as $image_url) {
              if (filter_var($image_url, FILTER_VALIDATE_URL)) {
                echo "<div class='image-wrapper'>";
                echo "<a href='" . htmlspecialchars($image_url) . "' target='_blank'>";
                echo "<img class='horse-image' src='" . htmlspecialchars($image_url) . "' alt='Image'>";
                echo "</a>";
                if ($_SESSION['user_type'] == "Admin") {
                  echo "<form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this image?\");'>";
                  echo "<input type='hidden' name='url' value='" . htmlspecialchars($image_url) . "'>";
                  echo "<input type='submit' class='delete-button' value='X'>";
                  echo "</form>";
                }
                echo "</div>";
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