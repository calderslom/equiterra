<?php

// Include functions
require_once 'retrieval_functions.php';
require_once 'client_functions.php';
require_once 'utility.php';

// Need to connect to the database for data retrieval. The $conn object will be used to communicate with the SQL database
$conn = new mysqli('sql.freedb.tech', 'freedb_Youssef', 'fp53R5UKVn*M@XW', 'freedb_Equiterra');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// customer name is stored in $_SESSION['customer']['username']
retrieve_farriers($conn);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  debug_to_console($_POST["farrier"]);
  debug_to_console($_SESSION['customer']['username']);
  // Need to check if the invoice number already exists. Can't have duplicate keys
  $i_number = $_POST["i_number"];
  $customer = $_SESSION['customer']['username'];
  $status = $_POST["status"];
  $price = $_POST["price"];
  $date = $_POST["date"];
  $farrier = $_POST["farrier"];
  // CHECKING TO SEE IF INVOICE NUMBER ALREADY EXISTS IN THE DATABASE. PK cannot be duplicated
  if (!is_numeric($price)) {
    $error = "Price must be a number!";
  } else if (check_invoice_number($conn, $i_number)) {
    // Insert invoice
    $stmt_insert = $conn->prepare("CALL AddInvoice(?,?,?,?,?)");
    // Bind parameters and execute the SQL statement
    $stmt_insert->bind_param("iiiss", $i_number, $status, $price, $farrier, $customer);
    $stmt_insert->execute();
    $services = $_POST["services"]; // services is an array
    if (count($services) > 0) {
      foreach ($services as $service) {
        // Bind parameters and execute the SQL statement
        debug_to_console($service);
        debug_to_console($i_number);
        $stmt_insert_invoice = $conn->prepare("CALL AddInvoiceService(?,?,?)");
        $stmt_insert_invoice->bind_param("iss", $i_number, $service, $date);
        $stmt_insert_invoice->execute();
      }
    }
    header('Location: customer.php');
  } else {
    $error = "Invoice number already exists!";
  }
}

$conn->close();
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
          <a href='customer.php'><button class='back-button'>
              < Client</button></a>
          <br>
          <h1 class="returning__header">Add Invoice</h1>
          <form class="signin" method="post">
            <div class="form-group">
              <label for="i_number">Invoice Number</label>
              <input type="text" class="form-control" id="i_number" name="i_number" value="<?php echo isset($_POST['i_number']) ? $_POST['i_number'] : '' ?>" required />
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control" id="status" name="status" value="<?php echo isset($_POST['status']) ? $_POST['status'] : '' ?>" required>
                <option value="">Select Status</option>
                <option value=1>Paid</option>
                <option value=0>Unpaid</option>
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
                if (isset($_SESSION['farriers']) && count($_SESSION['farriers']) > 0) {
                  // Loop through the array and create the option elements
                  foreach ($_SESSION['farriers'] as $farrier) {
                    $selected = isset($_POST['farrier']) && $_POST['farrier'] == $farrier['fusername'] ? 'selected' : '';
                    echo "<option value='{$farrier['fname']}' {$selected}>{$farrier['fname']}</option>";
                  }
                }
                ?>
              </select>
            </div>
            <br>
            <div class="form-group">
              <label for="services">Services<button class="right-red-button" type="button" onclick="addService()">Add another service</button></label>
              <input type="text" class="form-control rounded" id="services" name="services[]" placeholder="Enter service" required>
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