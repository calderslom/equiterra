<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Include functions
require_once 'utility.php';


/**
 * Retrieves all barn names from the Barn table and stores them in the session.
 * Barn names are themselves the primary key in the Barn SQL table.
 *
 * This function prepares and executes an SQL statement to retrieve all barn names
 * from the Barn table. It then creates an array containing the barn names and stores
 * the array in the session under the key 'barns'.
 *
 * @param mysqli $conn - The MySQLi database connection.
 */
function retrieve_all_barns($conn)
{
    // Prepare SQL statement for retrieval of ALL Barn Names
    $stmt_barn = $conn->prepare("SELECT Bname FROM Barn");
    $stmt_barn->execute();
    // Get the result set
    $barn_result = $stmt_barn->get_result();
    // Creating an array to store the barn names
    $barns = [];
    // Check if any barns were retrieved
    while ($tuple = $barn_result->fetch_assoc()) {
        // Add barn name to the array
        $barns[] = $tuple["Bname"];
    }
    // Store the array in the session under the key 'barns'
    $_SESSION['barns'] = $barns;
}

/**
 * Retrieves the number of clients associated with a specific barn and stores it in the session.
 * If there are no clients associated with a barn, the default session value is set to zero.
 *
 * @param mysqli $conn - The database connection object.
 */
function retrieve_barn_num_clients($conn)
{
    if (isset($_SESSION['barn_name']) && !empty($_SESSION['barn_name'])) {
        // Get the barn name from the session and sanitize it
        $barn_name = $conn->real_escape_string($_SESSION['barn_name']);
        // Prepare SQL statement for retrieval of ALL Barn details for a specific barn.
        $stmt_barn = $conn->prepare("CALL GetBarnNumClients(?)");
        $stmt_barn->bind_param("s", $barn_name);
        $stmt_barn->execute();
        // Get the result set
        $barn_result = $stmt_barn->get_result();
        // Check if any barns were retrieved
        $_SESSION['barn']['num_clients'] = 0;
        while ($tuple = $barn_result->fetch_assoc()) {
            $_SESSION['barn']['num_clients'] ++;
        }
    }
}

/**
 * Function to retrieve details of a specific barn from the database and store them in the session.
 * @param mysqli $conn - The database connection object.
 */
function retrieve_barn_details($conn)
{
    if (isset($_SESSION['barn_name']) && !empty($_SESSION['barn_name'])) {
        // Get the barn name from the session and sanitize it
        $barn_name = $conn->real_escape_string($_SESSION['barn_name']);
        // Prepare SQL statement for retrieval of ALL Barn details for a specific barn.
        $stmt_barn = $conn->prepare("SELECT * FROM Barn WHERE Bname = ?");
        $stmt_barn->bind_param("s", $barn_name);
        $stmt_barn->execute();
        // Get the result set
        $barn_result = $stmt_barn->get_result();
        // Check if any barns were retrieved
        if ($tuple = $barn_result->fetch_assoc()) {
            // Add barn details to the array
            //debug_to_console($tuple["Street_name"]);
            $_SESSION['barn']['name'] = $tuple['Bname'];
            $_SESSION['barn']['email'] = $tuple['Email'];
            $_SESSION['barn']['street_number'] = $tuple['Street_num'];
            $_SESSION['barn']["city"] = $tuple["City"];
            $_SESSION['barn']["postal_code"] = $tuple["Postal_code"];
            $_SESSION['barn']["contact"] = $tuple["Contact"];
            $_SESSION['barn']["phone_number"] = $tuple["Phone_num"];
            $_SESSION['barn']["street_name"] = $tuple["Street_name"];
            $_SESSION['barn']["province"] = $tuple["Province"];
        }
    }
}

/**
 * Retrieves and populates the $_SESSION['barn_horses'] array with horse names and their owners
 *
 * @param mysqli $conn - The MySQLi database connection
 */
function retrieve_barn_horses($conn)
{
    // Get the barn name from the session and sanitize it
    $barn_name = $conn->real_escape_string($_SESSION['barn_name']);
    // Prepare SQL statement for retrieval of ALL Barn details for a specific barn.
    $stmt_barn = $conn->prepare("CALL GetBarnHorses(?)");
    $stmt_barn->bind_param("s", $barn_name);
    $stmt_barn->execute();
    // Get the result set
    $barn_result = $stmt_barn->get_result();
     // Initialize the barn_horses array
     $_SESSION['barn_horses'] = [];
    // Check if any barns were retrieved
    while ($tuple = $barn_result->fetch_assoc()) {
        debug_to_console($tuple['Hname']);
        // Add horse and owner to the array
        $_SESSION['barn_horses'][$tuple['Hname']] = $tuple['Cname'];
    }
}

?>
