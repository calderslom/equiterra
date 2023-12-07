<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Include functions
require_once 'utility.php';
require_once 'client_functions.php';



function retrieve_shoeing_protocol_dates($conn) {
    // Get the horse name from the session and sanitize it
    $horse_name = $conn->real_escape_string($_SESSION['horse_name']);
    // Prepare SQL procedure for retrieval of ALL Shoeing protocol for a specific horse.
    $stmt_horse = $conn->prepare("SELECT Date FROM Shoeing_Protocol WHERE Hname = ?");
    $stmt_horse->bind_param("s", $horse_name);
    $stmt_horse->execute();
    // Get the result set
    $horse_result = $stmt_horse->get_result();
    // Initialize the shoeing_protocols array
    $_SESSION['shoeing_protocols'] = [];
    while ($tuple = $horse_result->fetch_assoc()) {
        $_SESSION['shoeing_protocols'][] = $tuple['Date'];
        //$_SESSION['shoeing_protocols']['horse'] = $horse_name;
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
function retrieve_horse_details($conn) {
    // Get the horse name from the session and sanitize it
    $horse_name = $conn->real_escape_string($_SESSION['horse_name']);
    // Prepare SQL procedure for retrieval of ALL horse details for a specific horse.
    $stmt_horse = $conn->prepare("CALL GetHorseDetails(?)");
    $stmt_horse->bind_param("s", $horse_name);
    $stmt_horse->execute();
    // Get the result set
    $horse_result = $stmt_horse->get_result();
    while ($tuple = $horse_result->fetch_assoc()){
        $_SESSION['horse']['name'] = $tuple['Hname'];
        $_SESSION['horse']['gender'] = $tuple['Gender'];
        $_SESSION['horse']['discipline'] = $tuple['Discipline'];
        $_SESSION['horse']['height'] = $tuple['Height'];
        $_SESSION['horse']['birthdate'] = $tuple['Birthdate'];
        $_SESSION['horse']['breed'] = $tuple['Breed'];
        $_SESSION['horse']['conf_notes'] = $tuple['Conf_notes'];
        $_SESSION['horse']['bname'] = $tuple['Bname'];
        $_SESSION['horse']['owner'] = $tuple['Cname'];
        $_SESSION['horse']['name'] = $tuple['Hname'];
        $_SESSION['horse']['cusername'] = $tuple['Cusername'];
    }
}




?>