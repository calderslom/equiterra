<?php
// Include functions
require_once 'utility.php';
session_start();

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
    }
}

function retrieve_invoices_admin($conn) {
        // Prepare SQL statement for Client name retrieval
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
}


?>