<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
// Include functions
require_once 'utility.php';

/**
 * Retrieve client information based on the session username.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 *
 * @return array|false - An associative array representing the client information
 *                      if successful, or false if there was an error.
 */
function retrieve_client($conn)
{
    // Ensuring that a user has been succesfully logged in and session variables were assigned
    if (isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
        // Prepare SQL statement for Client name retrieval
        $stmtClient = $conn->prepare("SELECT * FROM Client WHERE Cusername = ?");
        $stmtClient->bind_param("s", $username);
        $stmtClient->execute();
        $client_result = $stmtClient->get_result();
        // Check if the clientNameResult was successful
        if ($client_result) {
            // Get the Client Tuple returned by the SQL Query
            $client_tuple = $client_result->fetch_assoc();
            return $client_tuple;
        } else return false;
    } 
}

?>
