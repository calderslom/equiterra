<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Include functions
require_once 'utility.php';
require_once 'client_functions.php';


/**
 * Retrieves horse images from the database and stores them in the session.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 */
function retrieve_horse_images($conn)
{
    if (isset($_SESSION['horse_name']) && !empty($_SESSION['horse_name'])) {
        // Get the horse name from the session and sanitize it
        $horse_name = $conn->real_escape_string($_SESSION['horse_name']);
        debug_to_console($horse_name);
        // Prepare SQL procedure for retrieval of horse images
        $stmt_images = $conn->prepare("CALL GetHorseImages(?)");
        $stmt_images->bind_param("s", $horse_name);
        $stmt_images->execute();

        // Get the result set
        $images_result = $stmt_images->get_result();

        // Creating an array to store the image URLs
        $horse_images = [];

        // Check if any images were retrieved
        while ($image = $images_result->fetch_assoc()) {
            debug_to_console($image['Image_path']);
            $horse_images[] = $image['Image_path'];
        }

        // Store the image URLs in the session variable
        $_SESSION['images'] = $horse_images;
    }
}

/**
 * Retrieves analysis dates and types for a specific horse and stores them in the session.
 *
 * @param mysqli $conn - The database connection object.
 */
function retrieve_analysis_dates_types($conn)
{
    if (isset($_SESSION['horse_name']) && !empty($_SESSION['horse_name'])) {
        // Get the horse name from the session and sanitize it
        $horse_name = $conn->real_escape_string($_SESSION['horse_name']);
        // Prepare SQL procedure for retrieval of ALL Shoeing protocol dates for a specific horse.
        $stmt_horse = $conn->prepare("CALL GetHorseAnalysis(?)");
        $stmt_horse->bind_param("s", $horse_name);
        $stmt_horse->execute();
        // Get the result set
        $analysis_result = $stmt_horse->get_result();
        // Creating an array to store the protocol dates and horse name
        $analysis = [];
        // Check if any invoices were retrieved
        while ($tuple = $analysis_result->fetch_assoc()) {
            $$analysis[] = [
                "date" => $tuple["Analysis_Date"],
                "type" => $tuple["Type"]
            ];
        }
        $_SESSION['analysis_table'] = $$analysis;
    }
}

/**
 * Retrieve shoeing protocol details for a specific horse on a given date and store them in the session.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 */
function retrieve_shoeing_protocol_details($conn)
{
    if (
        isset($_SESSION['protocol_horse']) && !empty($_SESSION['protocol_horse'])
        && isset($_SESSION['protocol_date']) && !empty($_SESSION['protocol_date'])
    ) {
        // Wiping any previous shoeing_protocols
        unset($_SESSION['shoeing_protocol']);
        // Get the horse name from the session and sanitize it
        $horse_name = $conn->real_escape_string($_SESSION['protocol_horse']);
        // Prepare SQL procedure for retrieval of ALL horse details for a specific horse.
        $stmt_horse = $conn->prepare("CALL GetShoeingProtocol(?,?)");
        //$stmt_horse = $conn->prepare("SELECT * FROM Shoeing_Protocol WHERE Hname = ? AND Date = ?");
        $stmt_horse->bind_param("ss", $horse_name, $_SESSION['protocol_date']);
        $stmt_horse->execute();
        // Get the result set
        $horse_result = $stmt_horse->get_result();
        if ($tuple = $horse_result->fetch_assoc()) {
            $_SESSION['shoeing_protocol']['horse_name'] = $tuple['Hname'];
            $_SESSION['shoeing_protocol']['left_front'] = $tuple['Left_Front'];
            $_SESSION['shoeing_protocol']['right_front'] = $tuple['Right_Front'];
            $_SESSION['shoeing_protocol']['left_hind'] = $tuple['Left_Rear'];
            $_SESSION['shoeing_protocol']['right_hind'] = $tuple['Right_Rear'];
            $_SESSION['shoeing_protocol']['status'] = $tuple['Status'];
            $_SESSION['shoeing_protocol']['notes'] = $tuple['Notes'];
            $_SESSION['shoeing_protocol']['date'] = $tuple['Date'];
            debug_to_console($tuple['Date']);
            debug_to_console($tuple['Left_Front']);
        }
    }
}

/**
 * Retrieves shoeing protocol dates for a specific horse and stores them in the session.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 *
 * This function retrieves shoeing protocol dates for a specific horse from the database
 * and stores them in the $_SESSION['shoeing_protocols'] array.
 */
function retrieve_shoeing_protocol_dates($conn)
{
    if (isset($_SESSION['horse_name']) && !empty($_SESSION['horse_name'])) {
        // Get the horse name from the session and sanitize it
        $horse_name = $conn->real_escape_string($_SESSION['horse_name']);
        // Prepare SQL procedure for retrieval of ALL Shoeing protocol dates for a specific horse.
        $stmt_horse = $conn->prepare("CALL GetShoeingProtocolDates(?)");
        $stmt_horse->bind_param("s", $horse_name);
        $stmt_horse->execute();
        // Get the result set
        $protocol_result = $stmt_horse->get_result();
        // Creating an array to store the protocol dates and horse name
        $shoeing_protocols = [];
        // Check if any invoices were retrieved
        while ($tuple = $protocol_result->fetch_assoc()) {
            $shoeing_protocols[] = [
                "date" => $tuple["Date"],
                "horse_name" => $tuple["Hname"]
            ];
        }
        $_SESSION['shoeing_protocols'] = $shoeing_protocols;
    }
}

/**
 *
 * Retrieves details of a specific horse from the database and stores them in the session along with the name of their owner.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 *
 * @return void
 */
function retrieve_horse_details($conn)
{
    if (isset($_SESSION['horse_name']) && !empty($_SESSION['horse_name'])) {
        // Get the horse name from the session and sanitize it
        $horse_name = $conn->real_escape_string($_SESSION['horse_name']);
        // Prepare SQL procedure for retrieval of ALL horse details for a specific horse.
        $stmt_horse = $conn->prepare("CALL GetHorseDetails(?)");
        $stmt_horse->bind_param("s", $horse_name);
        $stmt_horse->execute();
        // Get the result set
        $horse_result = $stmt_horse->get_result();
        while ($tuple = $horse_result->fetch_assoc()) {
            $_SESSION['horse']['name'] = $tuple['Hname'];
            $_SESSION['horse']['gender'] = $tuple['Gender'];
            $_SESSION['horse']['discipline'] = $tuple['Discipline'];
            $_SESSION['horse']['height'] = $tuple['Height'];
            $_SESSION['horse']['birthdate'] = $tuple['Birthdate'];
            $_SESSION['horse']['breed'] = $tuple['Breed'];
            $_SESSION['horse']['conf_notes'] = $tuple['Conf_notes'];
            $_SESSION['horse']['barn'] = $tuple['Bname'];
            $_SESSION['horse']['owner'] = $tuple['Cname'];
            $_SESSION['horse']['name'] = $tuple['Hname'];
            $_SESSION['horse']['cusername'] = $tuple['Cusername'];
        }
    }
}
