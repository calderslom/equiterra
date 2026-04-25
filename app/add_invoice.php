<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'retrieval_functions.php';
require_once 'client_functions.php';
require_once 'utility.php';
require_once 'db_config.php';

retrieve_farriers($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer = $_SESSION['customer']['username'];
    $status   = $_POST["status"];
    $farrier  = $_POST["farrier"];
    $date     = $_POST["date"];
    $services = $_POST["services"];
    $prices   = $_POST["prices"];

    // Validate that all prices are numeric
    $prices_valid = true;
    foreach ($prices as $price) {
        if (!is_numeric($price)) {
            $prices_valid = false;
            break;
        }
    }

    if (!$prices_valid) {
        $error = "All prices must be numbers!";
    } else {
        // Insert the invoice — Number is AUTO_INCREMENT, no need to pass it
        $stmt_insert = $conn->prepare("CALL AddInvoice(?, ?, ?)");
        $stmt_insert->bind_param("iss", $status, $farrier, $customer);
        $stmt_insert->execute();

        // Retrieve the auto-generated invoice number
        $i_number = $conn->insert_id;

        // Insert each service as an Invoice_Item with its own price
        if (count($services) > 0) {
            foreach ($services as $index => $service) {
                $item_price = intval($prices[$index]);
                $stmt_insert_item = $conn->prepare("CALL AddInvoiceItem(?, ?, ?, ?)");
                $stmt_insert_item->bind_param("isis", $i_number, $service, $item_price, $date);
                $stmt_insert_item->execute();
            }
        }
        header('Location: customer.php');
        exit();
    }
}

$conn->close();
?>

<script>
    function addService() {
        var container = document.getElementById("services-container");

        var wrapper = document.createElement("div");
        wrapper.className = "form-group service-row";

        var descInput = document.createElement("input");
        descInput.type = "text";
        descInput.className = "form-control rounded";
        descInput.name = "services[]";
        descInput.placeholder = "Enter service description";
        descInput.required = true;

        var priceInput = document.createElement("input");
        priceInput.type = "number";
        priceInput.className = "form-control rounded";
        priceInput.name = "prices[]";
        priceInput.placeholder = "Enter price";
        priceInput.required = true;

        wrapper.appendChild(descInput);
        wrapper.appendChild(priceInput);
        container.appendChild(wrapper);
    }
</script>

<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="onboarding-overlay">
        <div class="onboarding-overlay-outer">
            <div class="onboarding-overlay-inner returning">
                <a href='customer.php'><button class='back-button'>&lt; Client</button></a>
                <br>
                <h1 class="returning__header">Add Invoice</h1>
                <form class="signin" method="post">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="1" <?php echo (isset($_POST['status']) && $_POST['status'] == '1') ? 'selected' : ''; ?>>Paid</option>
                            <option value="0" <?php echo (isset($_POST['status']) && $_POST['status'] == '0') ? 'selected' : ''; ?>>Unpaid</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date"
                            value="<?php echo isset($_POST['date']) ? $_POST['date'] : ''; ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="farrier">Farrier</label>
                        <select class="form-control rounded" id="farrier" name="farrier" required>
                            <option value="">Select Farrier</option>
                            <?php
                            if (isset($_SESSION['farriers']) && count($_SESSION['farriers']) > 0) {
                                foreach ($_SESSION['farriers'] as $farrier) {
                                    $selected = isset($_POST['farrier']) && $_POST['farrier'] == $farrier['fname'] ? 'selected' : '';
                                    echo "<option value='{$farrier['fname']}' {$selected}>{$farrier['fname']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Services
                            <button class="right-red-button" type="button" onclick="addService()">Add another service</button>
                        </label>
                        <div id="services-container">
                            <div class="form-group service-row">
                                <input type="text" class="form-control rounded" name="services[]"
                                    placeholder="Enter service description" required />
                                <input type="number" class="form-control rounded" name="prices[]"
                                    placeholder="Enter price" required />
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($error)) {
                        echo "<p style='color:red; text-align:center; font-size:20px;'>$error</p>";
                    }
                    ?>
                    <button class="onboarding-form__btn returning__btn" type="submit">Add Invoice</button>
                </form>
            </div>
            <p class="overlay-copyright">Equiterra &copy;2026</p>
        </div>
    </div>
</body>
</html>