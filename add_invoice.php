<?php
// Start the session
if (isset($_GET['invoice_number'])) {
  $_SESSION['invoice_number'] = urldecode($_GET['invoice_number']);
  // TODO: get invoice info from database
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $i_number = $_POST["i_number"];
  $customer = $_POST["customer"];
  $horse = $_POST["horse"];
  $status = $_POST["status"];
  $price = $_POST["price"];
  $date = $_POST["date"];
  $farrier = $_POST["farrier"];
  $services = $_POST["services"]; // services is an array

  if (!is_numeric($price)) {
    $error = "Price must be a number!";
  } else {
    // TODO: add invoice to database and services to database
    array_push($_SESSION['invoices'], array($i_number, $customer, $horse, $status, $price, $date, $farrier));
    header('Location: customer.php');
  }
}
?>

<script>
  function addService() {
    var services = document.getElementById("services");
    var input = document.createElement("input");
    input.type = "text";
    input.className = "form-control rounded";
    input.id = "services";
    input.name = "services[]";
    input.placeholder = "Enter service";
    services.parentNode.appendChild(input);
  }
</script>

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
              <label for="customer">Customer</label>
              <select class="form-control rounded" id="customer" name="customer" value="<?php echo isset($_POST['customer']) ? $_POST['customer'] : '' ?>" required>
                <option value="">Select Customer</option>
                <?php
                // Check if the session variable exists and is not empty
                if (isset($_SESSION['customers']) && count($_SESSION['customers']) > 0) {
                  // Loop through the array and create the option elements
                  foreach ($_SESSION['customers'] as $customer) {
                    $selected = isset($_POST['customer']) && $_POST['customer'] == $customer ? 'selected' : '';
                    echo "<option value='{$customer}' {$selected}>{$customer}</option>";
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="horse">Horse</label>
              <select class="form-control rounded" id="horse" name="horse" value="<?php echo isset($_POST['horse']) ? $_POST['horse'] : '' ?>" required>
                <option value="">Select Horse</option>
                <?php
                // TODO: make it only horses for that specific customer
                if (isset($_SESSION['horses']) && count($_SESSION['horses']) > 0) {
                  // Loop through the array and create the option elements
                  foreach ($_SESSION['horses'] as $horse) {
                    $selected = isset($_POST['horse']) && $_POST['horse'] == $horse ? 'selected' : '';
                    echo "<option value='{$horse}' {$selected}>{$horse}</option>";
                  }
                }
                ?>
              </select>
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
            <br>
            <div class="form-group">
              <label for="services">Services<button class="right-red-button" type="button" onclick="addService()">Add another service</button></label>
              <input type="text" class="form-control rounded" id="services" name="services[]" placeholder="Enter service" required > 
            </div>
            <?php
            if (isset($services)) {
              foreach ($services as $service) {
                echo "$service";
              }
            }
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