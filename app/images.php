<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'utility.php';
require_once 'horse_functions.php';
require_once 'db_config.php';

$horse_name = $_SESSION['horse_name'];
$upload_dir = "uploads/images/horses_by_name/" . $horse_name . "/";

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['horse_image'])) {
    $files = $_FILES['horse_image'];
    $upload_errors = [];

    // Create the horse's upload directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Loop through each uploaded file
    for ($i = 0; $i < count($files['name']); $i++) {
        $file_name     = basename($files['name'][$i]);
        $file_tmp      = $files['tmp_name'][$i];
        $file_error    = $files['error'][$i];
        $file_size     = $files['size'][$i];
        $file_ext      = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allowed extensions
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if ($file_error !== UPLOAD_ERR_OK) {
            $upload_errors[] = "Error uploading $file_name.";
            continue;
        }

        if (!in_array($file_ext, $allowed_exts)) {
            $upload_errors[] = "$file_name is not an allowed file type. Use jpg, jpeg, png, gif, or webp.";
            continue;
        }

        if ($file_size > 10 * 1024 * 1024) { // 10MB limit
            $upload_errors[] = "$file_name exceeds the 10MB size limit.";
            continue;
        }

        // Generate a unique filename to avoid collisions
        $unique_name = $horse_name . "_" . date('Y-m-d') . "_" . uniqid() . "." . $file_ext;
        $destination   = $upload_dir . $unique_name;

        if (move_uploaded_file($file_tmp, $destination)) {
            // Store the path and context in the database
            $context = isset($_POST['context']) ? $_POST['context'] : '';
            $date    = date('Y-m-d');
            $stmt    = $conn->prepare("CALL AddImage(?, ?, ?, ?)");
            $stmt->bind_param("ssss", $horse_name, $date, $context, $destination);
            $stmt->execute();
        } else {
            $upload_errors[] = "Failed to move $file_name to destination.";
        }
    }

    if (!empty($upload_errors)) {
        $error = implode("<br>", $upload_errors);
    }
}

// Handle image deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_path'])) {
    $path_to_delete = $_POST['delete_path'];

    // Delete the file from disk
    if (file_exists($path_to_delete)) {
        unlink($path_to_delete);
    }

    // Delete the record from the database
    $stmt_delete = $conn->prepare("DELETE FROM Image WHERE Image_path = ? AND Hname = ?");
    $stmt_delete->bind_param("ss", $path_to_delete, $horse_name);
    $stmt_delete->execute();
}

retrieve_horse_images($conn);
$conn->close();
?>

<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="onboarding-overlay">
        <div class="onboarding-overlay-outer">
            <?php include 'navbar.php'; ?>
            <br><br><br>
            <div class="onboarding-overlay-inner table">
                <a href='horse.php'><button class='back-button'>&lt; Horse</button></a>
                <br>
                <h1 class='returning__header'>Images for <?php echo htmlspecialchars($horse_name); ?></h1>

                <?php if ($_SESSION['user_type'] === 'Admin'): ?>
                <div style='text-align: right; margin-bottom: 10px;'>
                    <form method='POST' enctype='multipart/form-data' style='display: inline-block;'>
                        <input type='text' name='context' placeholder='Context (e.g. Left front hoof)'
                            style='height: 32px; border-radius: 5px; border: 1px solid #ccc; padding: 0 8px; margin-right: 6px;'>
                        <input type='file' name='horse_image[]' accept='image/*' multiple required
                            style='margin-right: 6px;'>
                        <input type='submit' class='add-button' value='Upload Image(s)'>
                    </form>
                </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <p style='color:red; text-align:center; font-size:16px;'><?php echo $error; ?></p>
                <?php endif; ?>

                <?php if (isset($_SESSION['images']) && count($_SESSION['images']) > 0): ?>
                    <div class='image-container'>
                        <?php foreach ($_SESSION['images'] as $image_path): ?>
                            <div class='image-wrapper'>
                                <a href='<?php echo htmlspecialchars($image_path); ?>' target='_blank'>
                                    <img class='horse-image' src='<?php echo htmlspecialchars($image_path); ?>'
                                        alt='Horse Image'>
                                </a>
                                <?php if ($_SESSION['user_type'] === 'Admin'): ?>
                                    <form method='POST'
                                        onsubmit='return confirm("Are you sure you want to delete this image?");'>
                                        <input type='hidden' name='delete_path'
                                            value='<?php echo htmlspecialchars($image_path); ?>'>
                                        <input type='submit' class='delete-button' value='X'>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class='returning__header'>No images found</div>
                <?php endif; ?>

            </div>
            <p class="overlay-copyright">Equiterra &copy;2026</p>
        </div>
    </div>
</body>
</html>