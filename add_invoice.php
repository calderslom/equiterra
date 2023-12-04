<?php
// Start the session
if (isset($_GET['invoice_number'])) {
  $_SESSION['invoice_number'] = urldecode($_GET['invoice_number']);
  // TODO: get invoice info from database
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $i_number = $_POST["i_number"];
  $status = $_POST["status"];
  $price = $_POST["price"];
  $date = $_POST["date"];
  $farrier = $_POST["farrier"];

  if (!is_numeric($price)) {
    $error = "Price must be a number!";
  } else {
    array_push($_SESSION['invoices'], array($i_number, $status, $price, $date, $farrier));
    header('Location: customer.php');
  }
}

?>
<html>
<!-- Rest of your HTML code -->
<html>
  <head>
    <link rel="stylesheet" href="style.css">
  <head>
  <body>
    <div class="onboarding-overlay">
      <div class="onboarding-overlay-outer">
        <div class="onboarding-overlay-inner returning">
          <a href='customer.php'><button class='back-button'>< Customer</button></a>
          <br>
          <h1 class="returning__header">Add Invoice</h1>
          <form class="signin" method="post">
            <div class="form-group">
              <label for="i_number">Invoice Number</label>
              <input type="text" class="form-control" id="i_number" name="i_number" value="<?php echo isset($_POST['i_number']) ? $_POST['i_number'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control rounded" id="status" name="status" value="<?php echo isset($_POST['status']) ? $_POST['status'] : '' ?>" required>
                <option value="">Select Status</option>
                <option value="Paid">Paid</option>
                <option value="Unpaid">Unpaid</option>
              </select>
            </div>
            <div class="form-group">
              <label for="price">Price</label>
              <input type="text" class="form-control" id="price" name="price" value="<?php echo isset($_POST['price']) ? $_POST['price'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="date">Date</label>
              <input type="date" class="form-control" id="date" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="owner">Farrier</label>
              <select class="form-control rounded" id="farrier" name="farrier" value="<?php echo isset($_POST['farrier']) ? $_POST['farrier'] : '' ?>" required>
                <option value="">Select Farrier</option>
                <?php
                // TODO: change to actual farriers from database
                if (isset($_SESSION['customers']) && count($_SESSION['customers']) > 0) {
                  // Loop through the array and create the option elements
                  foreach ($_SESSION['customers'] as $customer) {
                    $selected = isset($_POST['farrier']) && $_POST['farrier'] == $customer ? 'selected' : '';
                    echo "<option value='{$customer}' {$selected}>{$customer}</option>";
                  }
                }
                ?>
              </select>
            </div>
            <?php
            if (isset($error)) {
              echo "<p style='color:red; text-align:center; font-size:20px;'>$error</p>";
            }
            ?>
            <button class="onboarding-form__btn returning__btn" type="submit">Add Invoice</button>
          </form>
        </div>
        <p class="overlay-copyright">&copy;2023 Omar, Aidan, Youssef</p>
      </div>
    </div>
  </body>
</html>