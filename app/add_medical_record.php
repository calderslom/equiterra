<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'retrieval_functions.php';
require_once 'utility.php';
require_once 'db_config.php';

$horse_name = $_SESSION['horse_name'];
$upload_dir = "uploads/medical_records/" . $horse_name . "/";

retrieve_practitioners($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date     = $_POST["date"];
    $status   = $_POST["status"];
    $ailment  = $_POST["ailment"];
    $pname    = $_POST["pname_select"] !== 'other' ? $_POST["pname_select"] : $_POST["pname_other"];
    $filepath = '';

    // If a practitioner was typed in manually, add them to the Practitioner table
    $stmt_prac = $conn->prepare("CALL AddPractitionerIfNotExists(?)");
    $stmt_prac->bind_param("s", $pname);
    $stmt_prac->execute();
    $stmt_prac->close();

    // Handle optional file upload
    if (isset($_FILES['record_file']) && $_FILES['record_file']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['record_file']['name']);
        $file_size = $_FILES['record_file']['size'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['pdf', 'jpg', 'jpeg', 'png'];

        if (!in_array($file_ext, $allowed_exts)) {
            $error = "Invalid file type. Allowed: pdf, jpg, jpeg, png.";
        } else if ($file_size > 20 * 1024 * 1024) {
            $error = "File exceeds 20MB limit.";
        } else {
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $unique_name = $horse_name . "_" . $pname . "_" . $date . "_" . uniqid() . "." . $file_ext;
            $filepath    = $upload_dir . $unique_name;

            if (!move_uploaded_file($_FILES['record_file']['tmp_name'], $filepath)) {
                $error = "Failed to upload file.";
            }
        }
    }

    if (!isset($error)) {
        $stmt = $conn->prepare("CALL AddMedicalRecord(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $horse_name, $date, $status, $filepath, $ailment, $pname);
        $stmt->execute();
        header('Location: horse.php');
        exit();
    }

    $conn->close();
}
?>

<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="onboarding-overlay">
        <div class="onboarding-overlay-outer">
            <div class="onboarding-overlay-inner returning">
                <a href='horse.php'><button class='back-button'>&lt; <?php echo htmlspecialchars($horse_name); ?></button></a>
                <br>
                <h1 class="returning__header">Add Medical Record</h1>
                <form class="signin" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date"
                            value="<?php echo isset($_POST['date']) ? $_POST['date'] : ''; ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="1" <?php echo (isset($_POST['status']) && $_POST['status'] == '1') ? 'selected' : ''; ?>>Ongoing</option>
                            <option value="0" <?php echo (isset($_POST['status']) && $_POST['status'] == '0') ? 'selected' : ''; ?>>Resolved</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pname_select">Practitioner</label>
                        <select class="form-control" id="pname_select" name="pname_select"
                            onchange="toggleOtherInput(this.value)" required>
                            <option value="">Select Practitioner</option>
                            <?php
                            if (isset($_SESSION['practitioners']) && count($_SESSION['practitioners']) > 0) {
                                foreach ($_SESSION['practitioners'] as $practitioner) {
                                    $selected = isset($_POST['pname_select']) && $_POST['pname_select'] == $practitioner['pname'] ? 'selected' : '';
                                    echo "<option value='{$practitioner['pname']}' {$selected}>{$practitioner['pname']}</option>";
                                }
                            }
                            ?>
                            <option value="other">Other (type below)</option>
                        </select>
                    </div>
                    <div class="form-group" id="pname_other_group" style="display: none;">
                        <label for="pname_other">Practitioner Name</label>
                        <input type="text" class="form-control" id="pname_other" name="pname_other"
                            placeholder="Enter practitioner name"
                            value="<?php echo isset($_POST['pname_other']) ? $_POST['pname_other'] : ''; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="ailment">Ailment / Notes</label>
                        <textarea class="form-control" id="ailment" name="ailment" rows="4"
                            required><?php echo isset($_POST['ailment']) ? $_POST['ailment'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="record_file">Attach File (optional — pdf, jpg, jpeg, png, max 20MB)</label>
                        <input type="file" class="form-control" id="record_file" name="record_file"
                            accept=".pdf,.jpg,.jpeg,.png" />
                    </div>
                    <?php if (isset($error)): ?>
                        <p style='color:red; text-align:center; font-size:20px;'><?php echo $error; ?></p>
                    <?php endif; ?>
                    <button class="onboarding-form__btn returning__btn" type="submit">Add Medical Record</button>
                </form>
            </div>
            <p class="overlay-copyright">Equiterra &copy;2026</p>
        </div>
    </div>
</body>
</html>

<script>
    function toggleOtherInput(value) {
        var otherGroup = document.getElementById('pname_other_group');
        var otherInput = document.getElementById('pname_other');
        if (value === 'other') {
            otherGroup.style.display = 'block';
            otherInput.required = true;
        } else {
            otherGroup.style.display = 'none';
            otherInput.required = false;
        }
    }
</script>