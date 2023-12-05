<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (isset($_GET['horse_name'])) {
  $_SESSION['horse_name'] = urldecode($_GET['horse_name']);
}
// ...
?>

<script>
  function expandNotes() {
    var shortNotes = document.querySelector('.conf-notes-short');
    var fullNotes = document.querySelector('.conf-notes-full');
    var arrow = document.querySelector('.expand-arrow');
    if (fullNotes.style.display === 'none') {
      fullNotes.style.display = 'block';
      shortNotes.style.display = 'none';
      arrow.innerHTML = '▲';
    } else {
      fullNotes.style.display = 'none';
      shortNotes.style.display = 'block';
      arrow.innerHTML = '▼';
    }
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
        <div class="onboarding-overlay-inner info">
          <h1 class="returning__header">Horse Info</h1>
          <?php
          // TODO: must be changed to the horse's info from the database (using their username)
          if (isset($_SESSION['horse'])) {
            echo "<div class='user-info'>";
            echo "<div class='left-column'>";
            echo "<h3 class='returning__text'>Name: " . $_SESSION['horse']['name'] . "</h3>";
            echo "<h3 class='returning__text'>Owner: " . $_SESSION['horse']['owner'] . "</h3>";
            echo "<h3 class='returning__text'>Barn: " . $_SESSION['horse']['barn'] . "</h3>";
            echo "<h3 class='returning__text'>Breed: " . $_SESSION['horse']['breed'] . "</h3>";
            echo "<h3 class='returning__text'>Confirmation Notes: ";
            echo "<button class='expand-arrow' onclick='expandNotes()'>▼</button>";
            echo "<div class='conf-notes-short'>" . substr($_SESSION['horse']['conf_notes'], 0, 50) . "... </div>";
            echo "<div class='conf-notes-full' style='display: none;'>" . $_SESSION['horse']['conf_notes'] . "</div>";
            echo "</h3>";
            echo "</div>";
            echo "<div>";
            echo "<h3 class='returning__text'>Gender: " . $_SESSION['horse']['gender'] . "</h3>";
            echo "<h3 class='returning__text'>Discipline: " . $_SESSION['horse']['discipline'] . "</h3>";
            echo "<h3 class='returning__text'>Height: " . $_SESSION['horse']['height'] . "</h3>";
            echo "<h3 class='returning__text'>Birthdate: " . $_SESSION['horse']['birthdate'] . "</h3>";
            echo "</div>";
            echo "</div>";
          }
          ?>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>