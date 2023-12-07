<?php
// Include functions
require_once 'utility.php';
session_start();

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
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
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

function retrieve_invoice_details($conn) {

}

function retrieve_invoices_admin($conn) {
        // Prepare SQL statement for ALL Invoice retrieval
        $stmt_user = $conn->prepare("SELECT * FROM Invoice");
        $stmt_user->execute();
        $invoice_result = $stmt_user->get_result();
        // Creating an array to store the invoices
        $invoices = [];
        // Check if any invoices were retrieved
        while ($tuple = $invoice_result->fetch_assoc()) {
            $invoices[] = [ "number" => $tuple["Number"],
                            "status" => $tuple["Status"],
                            "price" => $tuple["Price"],
                            "username" => $tuple["Cusername"]];                
        }
        $_SESSION['invoices'] = $invoices;
}




/**
 * Retrieves client usernames and names from the Client table and stores them in the session.
 *
 * This function prepares and executes an SQL statement to retrieve all clients
 * from the Client table. It then creates an array containing client usernames
 * and names and stores the array in the session under the key 'clients'.
 *
 * @param mysqli $conn - The MySQLi database connection.
 */
function retrieve_client_names($conn) {
    // Prepare SQL statement for retrieval of ALL clients
    $stmt_user = $conn->prepare("SELECT * FROM Client");
    $stmt_user->execute();
    $client_result = $stmt_user->get_result();
    // Creating an array to store the invoices
    $clients = [];
    // Check if any invoices were retrieved
    while ($tuple = $client_result->fetch_assoc()) {
        $clients[] = [  "username" => $tuple["Cusername"],
                        "client" => $tuple["Cname"],];                
    }
    $_SESSION['clients'] = $clients;
}



?>