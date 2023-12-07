<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
// Include functions
require_once 'utility.php';

/**
 * Retrieves client usernames and names from the Client table and stores them in the session.
 *
 * This function prepares and executes an SQL statement to retrieve all clients
 * from the Client table. It then creates an array containing client usernames
 * and names and stores the array in the session under the key 'clients'.
 *
 * @param mysqli $conn - The MySQLi database connection.
 */
function retrieve_client_names($conn)
{
    // Prepare SQL statement for retrieval of ALL clients
    $stmt_user = $conn->prepare("SELECT * FROM Client");
    $stmt_user->execute();
    $client_result = $stmt_user->get_result();
    // Creating an array to store the invoices
    $clients = [];
    // Check if any clients were retrieved
    while ($tuple = $client_result->fetch_assoc()) {
        $clients[] = [
            "username" => $tuple["Cusername"],
            "name" => $tuple["Cname"],
        ];
    }
    $_SESSION['clients'] = $clients;
}

/**
 * Retrieves invoices for a client from the Invoice table.
 *
 * This function retrieves invoices for the client identified by the
 * username stored in the session. It prepares an SQL statement, executes
 * it, and creates an array containing the retrieved invoice information.
 *
 * @param mysqli $conn - The MySQLi database connection.
 * @return array|null - An array containing invoice information, or null if no invoices are found.
 */
function retrieve_invoices_client($conn) {
    if (isset($_SESSION['Cusername']) && !empty($_SESSION['Cusername'])) {
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['Cusername']);
        // Prepare SQL statement for Invoice retrieval by client name
        $stmt_user = $conn->prepare("SELECT * FROM Invoice WHERE Cusername = ?");
        $stmt_user->bind_param("s", $username);
        $stmt_user->execute();
        $invoice_result = $stmt_user->get_result();
        // Creating an array to store the invoices
        $invoices = [];
        // Check if any invoices were retrieved
        while ($tuple = $invoice_result->fetch_assoc()) {
            $invoices[] = [ "number" => $tuple["Number"],
                            "status" => $tuple["Status"],
                            "price" => $tuple["Price"]];                
        }
        $_SESSION['invoices'] = $invoices;
    }
}

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
    if (isset($_SESSION['Cusername']) && !empty($_SESSION['Cusername'])) {
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['Cusername']);
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
