<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
// Include functions
require_once 'utility.php';

function retrieve_invoices_client($conn)
{
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
            $invoices[] = [
                "number" => $tuple["Number"],
                "status" => $tuple["Status"],
                "price" => $tuple["Price"]
            ];
        }
    }
}

function retrieve_invoice_details($conn)
{
}

function retrieve_invoices_admin($conn)
{
    // Prepare SQL statement for ALL Invoice retrieval
    $stmt_user = $conn->prepare("SELECT * FROM Invoice");
    $stmt_user->execute();
    $invoice_result = $stmt_user->get_result();
    // Creating an array to store the invoices
    $invoices = [];
    // Check if any invoices were retrieved
    while ($tuple = $invoice_result->fetch_assoc()) {
        $invoices[] = [
            "number" => $tuple["Number"],
            "status" => $tuple["Status"],
            "price" => $tuple["Price"],
            "username" => $tuple["Cusername"]
        ];
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
            "client" => $tuple["Cname"],
        ];
    }
    $_SESSION['clients'] = $clients;
}

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



// function retrieve_barn_details($conn)
// {
//     if (isset($_SESSION['barn_name']) && !empty($_SESSION['barn_name'])) {
//         // Get the barn name from the session and sanitize it
//         $barn_name = $conn->real_escape_string($_SESSION['barn_name']);
//         // Prepare SQL statement for Invoice retrieval by client name
//         // Prepare SQL statement for retrieval of ALL Barn details for a specific barn.
//         $stmt_barn = $conn->prepare("SELECT * FROM Barn WHERE Bname = ?");
//         $stmt_barn->bind_param("s", $barn_name);
//         $stmt_barn->execute();
//         // Get the result set
//         $barn_result = $stmt_barn->get_result();
//         // Creating an array to store the barn details
//         $barns = [];
//         // Check if any barns were retrieved
//         while ($tuple = $barn_result->fetch_assoc()) {
//             // Add barn details to the array
//             //debug_to_console($tuple["Street_name"]);
//             $barns[] = [
//                 "name" => $tuple["Bname"],
//                 "email" => $tuple["Email"],
//                 "street_number" => $tuple["Street_num"],
//                 "city" => $tuple["City"],
//                 "postal_code" => $tuple["Postal_code"],
//                 "contact" => $tuple["Contact"],
//                 "phone_number" => $tuple["Phone_num"],
//                 "street_name" => $tuple["Street_name"],
//                 "province" => $tuple["Province"]
//             ];
//         }
//         // Store the array in the session under the key 'barns'
//         $_SESSION['barn'] = $barns;
//     } else {
//         return false;
//     }
// }


function retrieve_barn_details($conn)
{
    if (isset($_SESSION['barn_name']) && !empty($_SESSION['barn_name'])) {
        // Get the barn name from the session and sanitize it
        $barn_name = $conn->real_escape_string($_SESSION['barn_name']);
        // Prepare SQL statement for Invoice retrieval by client name
        // Prepare SQL statement for retrieval of ALL Barn details for a specific barn.
        $stmt_barn = $conn->prepare("SELECT * FROM Barn WHERE Bname = ?");
        $stmt_barn->bind_param("s", $barn_name);
        $stmt_barn->execute();
        // Get the result set
        $barn_result = $stmt_barn->get_result();
        // Creating an array to store the barn details
        $barns = [];
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

?>