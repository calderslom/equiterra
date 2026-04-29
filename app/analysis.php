<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'retrieval_functions.php';
require_once 'horse_functions.php';
require_once 'update_database.php';
require_once 'utility.php';
require_once 'db_config.php';

if (isset($_GET['horse_name'])) {
    $_SESSION['horse_name'] = urldecode($_GET['horse_name']);
}
if (isset($_GET['analysis_date'])) {
    $_SESSION['analysis_date'] = urldecode($_GET['analysis_date']);
}
if (isset($_GET['analysis_type'])) {
    $_SESSION['analysis_type'] = urldecode($_GET['analysis_type']);
}

if (isset($_POST['save_conf_notes'])) {
    update_analysis_details($conn, $_POST['details']);
}

retrieve_analysis_details($conn);
$conn->close();
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
</head>
<body>
    <div class="onboarding-overlay">
        <div class="onboarding-overlay-outer">
            <?php include 'navbar.php'; ?>
            <div class="onboarding-overlay-inner returning">
                <a href='horse.php'><button class='back-button'>&lt; <?php echo htmlspecialchars($_SESSION['horse_name']); ?></button></a>
                <br>
                <h1 class="returning__header">Analysis Details</h1>
                <br>
                <?php if (isset($_SESSION['username'])): ?>
                    <h3 class='returning__text'><span class='detail-label'>Horse:</span> <?php echo htmlspecialchars($_SESSION['horse_name']); ?></h3>
                    <h3 class='returning__text'><span class='detail-label'>Date:</span> <?php echo htmlspecialchars($_SESSION['analysis_date']); ?></h3>
                    <h3 class='returning__text'><span class='detail-label'>Type:</span> <?php echo htmlspecialchars($_SESSION['analysis_type']); ?></h3>

                    <?php if ($_SESSION['user_type'] == "Admin"): ?>
                        <?php if (isset($_POST['edit']) && $_POST['edit'] == 'details'): ?>
                            <form method='POST'>
                                <h3 class='returning__text'><span class='detail-label'>Details:</span>
                                    <input type='submit' name='save_conf_notes' value='Save' class='conf-save'>
                                    <div><textarea class='edit-input' name='details' style='height: 100px; width: 400px;'><?php echo htmlspecialchars($_SESSION['analysis']['details'] ?? ''); ?></textarea></div>
                                </h3>
                            </form>
                        <?php else: ?>
                            <h3 class='returning__text'><span class='detail-label'>Details:</span>
                                <button class='expand-arrow' onclick='expandNotes()'>▼</button>
                                <form method='POST' style='display:inline;'>
                                    <input type='hidden' name='edit' value='details'>
                                    <input type='submit' value='Edit' class='conf-button'>
                                </form>
                                <?php
                                $first_line = explode("\n", nl2br($_SESSION['analysis']['details'] ?? ''))[0];
                                ?>
                                <div class='conf-notes-short'><?php echo substr($first_line, 0, 50); ?>...</div>
                                <div class='conf-notes-full' style='display: none;'><?php echo nl2br(htmlspecialchars($_SESSION['analysis']['details'] ?? '')); ?></div>
                            </h3>
                        <?php endif; ?>
                    <?php else: ?>
                        <h3 class='returning__text'><span class='detail-label'>Details:</span>
                            <button class='expand-arrow' onclick='expandNotes()'>▼</button>
                            <?php
                            $first_line = explode("\n", nl2br($_SESSION['analysis']['details'] ?? ''))[0];
                            ?>
                            <div class='conf-notes-short'><?php echo substr($first_line, 0, 50); ?>...</div>
                            <div class='conf-notes-full' style='display: none;'><?php echo nl2br(htmlspecialchars($_SESSION['analysis']['details'] ?? '')); ?></div>
                        </h3>
                    <?php endif; ?>

                    <!-- Analysis file download -->
                    <?php if (!empty($_SESSION['analysis']['analysis_path']) && file_exists($_SESSION['analysis']['analysis_path'])): ?>
                        <br>
                        <h3 class='returning__text'>
                            <span class='detail-label'>Attached File:</span>
                            <a href='<?php echo htmlspecialchars($_SESSION['analysis']['analysis_path']); ?>' target='_blank'>
                                <button class='table-button'>Download</button>
                            </a>
                        </h3>
                    <?php elseif (!empty($_SESSION['analysis']['analysis_path'])): ?>
                        <br>
                        <h3 class='returning__text'>
                            <span class='detail-label'>Attached File:</span>
                            <span style='color: #ae0404; font-size: 0.9em;'>no file for this record exists.</span>
                        </h3>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
            <p class="overlay-copyright">Equiterra &copy;2026</p>
        </div>
    </div>
</body>
</html>