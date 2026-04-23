<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Include functions
require_once 'utility.php';


/**
 * Retrieves farriers from the database and stores them in the session.
 *
 * @param mysqli $conn - The database connection object.
 */
function retrieve_farriers($conn) {
    // Prepare a SQL procedure for retrieving farriers.
    $stmt_user = $conn->prepare("CALL GetFarriers");
    $stmt_user->execute();

    // Get the result set from the executed statement.
    $user_result = $stmt_user->get_result();

    // Create an array to store farrier information.
    $farriers = [];

    // Loop through each farrier record in the result set.
    while ($farrier_tuple = $user_result->fetch_assoc()) {
        // Add farrier information to the $farriers array.
        $farriers[] = [
            "fname" => $farrier_tuple['Fname'],
            "fusername" => $farrier_tuple['Fusername']
        ];
    }

    // Store the $farriers array in the session under the 'farriers' key.
    $_SESSION['farriers'] = $farriers;
}


/**
 * Retrieve user information based on the session username.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 *
 * @return array|false - An associative array representing the user which contains 
 *                      the username, user type, and email if successful, 
 *                      or returns false if there was an error.
 */
function retrieve_user_login($conn, $username_or_email, $password)
{
    $stmt_user = $conn->prepare("CALL GetUserLogin(?,?)");
    $stmt_user->bind_param("ss", $username_or_email, $password);
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();
    if ($user_tuple = $user_result->fetch_assoc()) {
        // This tuple could not have been retrieved if the password or username/email was invalid.
        return $user_tuple;
    } else {
        return false;
    }
}

/**
 * Checks if the given invoice number already exists in the database.
 *
 * @param mysqli $conn - The database connection object.
 * @param string $invoice_number - The invoice number to check.
 * @return bool - Returns true if the invoice number does not exist, false otherwise.
 */
function check_invoice_number($conn, $invoice_number) {
    // Prepare a SQL statement to select the 'Number' column from the 'Invoice' table.
    $stmt_invoice = $conn->prepare("SELECT Number FROM Invoice");

    // Execute the prepared statement.
    $stmt_invoice->execute();

    // Get the result set from the executed statement.
    $invoice_result = $stmt_invoice->get_result();

    // Loop through each row in the result set.
    while ($invoice = $invoice_result->fetch_assoc()) {
        // Compare the current invoice number with the provided invoice number.
        if ($invoice['Number'] == $invoice_number) {
            // If a matching invoice number is found, output it to the console (you might want to replace this with logging).
            debug_to_console($invoice_number);
            // The invoice number already exists, so return false.
            return false;
        } 
            // If the current invoice number does not match, continue checking the next row.
    }

    // If no matching invoice number is found in the loop, return true (indicating that the invoice number does not exist in the database).
    return true;
}


/**
 * Checks if a user with the given username and email already exists in the database.
 *
 * @param mysqli $conn - The database connection object.
 * @param string $username - The username to check.
 * @param string $email - The email to check.
 * @return bool - Returns true if a user with the provided username and email exists, false otherwise.
 */
function check_user_exists($conn, $username, $email) {
    // Prepare a SQL procedure for checking the existence of a user with the given username and email.
    $stmt_user = $conn->prepare("CALL CheckUsernameEmail(?,?)");
    $stmt_user->bind_param("ss", $username, $email);
    $stmt_user->execute();

    // Get the result set from the executed statement.
    $user_result = $stmt_user->get_result();

    // Check if a user with the provided username and email exists in the database.
    if ($user_tuple = $user_result->fetch_assoc()) {
        // If a matching user is found, return true.
        return true;
    } else {
        // If no matching user is found, return false.
        return false;
    }
}


/**
 * Retrieve user information based on the session username.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 *
 * @return array|false - An associative array representing the client information
 *                      if successful, or false if there was an error.
 */
function retrieve_user($conn)
{
    if (isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
        // Prepare SQL statement for Client name retrieval
        $stmt_user = $conn->prepare("SELECT * FROM Web_user WHERE Username = ?");
        $stmt_user->bind_param("s", $username);
        $stmt_user->execute();
        $user_result = $stmt_user->get_result();
        if ($user_result) {
            // Get the user Tuple returned by the SQL Query
            $user_tuple = $user_result->fetch_assoc();
            return $user_tuple;
        } else return false;
    } else return false;
}
