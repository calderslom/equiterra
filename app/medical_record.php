<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'utility.php';
require_once 'horse_functions.php';
require_once 'db_config.php';

if (isset($_GET['medical_record_horse'])) {
    $_SESSION['medical_record_horse'] = urldecode($_GET['medical_record_horse']);
}
if (isset($_GET['medical_record_date'])) {
    $_SESSION['medical_record_date'] = urldecode($_GET['medical_record_date']);
}

retrieve_medical_record_details($conn);

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
            <div class="onboarding-overlay-inner returning">
                <a href='horse.php'>
                    <button class='back-button'>&lt; <?php echo htmlspecialchars($_SESSION['medical_record_horse']); ?></button>
                </a>
                <br><br>
                <h1 class="returning__header">Medical Record</h1>
                <br>
                <?php if (isset($_SESSION['medical_record'])): ?>
                    <?php $record = $_SESSION['medical_record']; ?>
                    <h3 class='returning__text'><span class='detail-label'>Horse:</span> <?php echo htmlspecialchars($record['hname']); ?></h3>
                    <h3 class='returning__text'><span class='detail-label'>Date:</span> <?php echo htmlspecialchars($record['date']); ?></h3>
                    <h3 class='returning__text'><span class='detail-label'>Status:</span> <?php echo $record['status'] == 1 ? 'Ongoing' : 'Resolved'; ?></h3>
                    <h3 class='returning__text'><span class='detail-label'>Ailment / Notes:</span> <?php echo nl2br(htmlspecialchars($record['ailment'] ?? '')); ?></h3>

                    <!-- Practitioner Details -->
                    <br>
                    <h3 class='returning__text'><span class='detail-label'>Practitioner:</span> <?php echo htmlspecialchars($record['pname'] ?? 'Not recorded'); ?></h3>
                    <?php if (!empty($record['practitioner_phone'])): ?>
                        <h3 class='returning__text'><span class='detail-label'>Phone:</span> <?php echo htmlspecialchars($record['practitioner_phone']); ?></h3>
                    <?php endif; ?>
                    <?php if (!empty($record['practitioner_email'])): ?>
                        <h3 class='returning__text'><span class='detail-label'>Email:</span> <?php echo htmlspecialchars($record['practitioner_email']); ?></h3>
                    <?php endif; ?>
                    <?php if (!empty($record['practitioner_type'])): ?>
                        <h3 class='returning__text'><span class='detail-label'>Type:</span> <?php echo htmlspecialchars($record['practitioner_type']); ?></h3>
                    <?php endif; ?>

                    <!-- File download link if a file is attached -->
                    <?php if (!empty($record['filepath'])): ?>
                        <br>
                        <h3 class='returning__text'>
                            <span class='detail-label'>Attached File:</span>
                            <a href='<?php echo htmlspecialchars($record['filepath']); ?>' target='_blank'>
                                <button class='table-button'>Download</button>
                            </a>
                        </h3>
                    <?php endif; ?>

                <?php else: ?>
                    <div class='returning__header'>Medical record could not be found. Please go back and try again.</div>
                <?php endif; ?>
            </div>
            <p class="overlay-copyright">Equiterra &copy;2026</p>
        </div>
    </div>
</body>
</html>