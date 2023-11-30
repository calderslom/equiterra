<div class="topnav">
  <a class="<?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>" href="home.php">Home</a>
  <a class="<?php echo basename($_SERVER['PHP_SELF']) == 'horses.php' ? 'active' : ''; ?>" href="horses.php">Horses</a>
  <?php
  // Assuming you have the user type stored in a session variable
  if ($_SESSION['user_type'] == 'Admin') {
    // Display links for admin
    echo '<a class="'.(basename($_SERVER['PHP_SELF']) == 'barns.php' ? 'active' : '').'" href="barns.php">Barns</a>';
    echo '<a class="'.(basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : '').'" href="customers.php">Customers</a>';
  } else if ($_SESSION['user_type'] == 'Client') {
    // Display links for client
    echo '<a class="'.(basename($_SERVER['PHP_SELF']) == 'customer.php' ? 'active' : '').'" href="customer.php">Details</a>';
  }
  ?>
  <a class="<?php echo basename($_SERVER['PHP_SELF']) == 'account.php' ? 'active' : ''; ?>" href="account.php">Account</a>
  <a class="right" href="signout.php">Sign out</a>
</div>