<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Include functions
require_once 'utility.php';


function update_protocol_status($conn)
{
    if (
        isset($_SESSION['protocol_horse']) && !empty($_SESSION['protocol_horse'])
        && isset($_SESSION['protocol_date']) && !empty($_SESSION['protocol_date'])
    ) {
        
        $protocol_status = $conn->real_escape_string($_SESSION['shoeing_protocol']["status"]);
        $protocol_date = $conn->real_escape_string($_SESSION['protocol_date']);
        $horse_name = $conn->real_escape_string($_SESSION['protocol_horse']);
        $stmt_update = $conn->prepare("CALL UpdateProtocolStatus(?,?,?)");
        // Bind parameters and execute the SQL statement
        $stmt_update->bind_param("sss", $protocol_status, $horse_name, $protocol_date);
        $stmt_update->execute();
    }
}

/**
 * Updates conformation notes for a specific horse in the database.
 *
 * @param mysqli $conn - The MySQLi database connection object.
 */
function update_conformation_notes($conn)
{
    // Check if horse name is set and not empty in the session
    if (isset($_SESSION['horse']['name']) && !empty($_SESSION['horse']['name'])) {
        // Get the conformation notes and horse name from the session and sanitize them
        $conf_notes = $conn->real_escape_string($_SESSION['horse']['conf_notes']);
        $horse_name = $conn->real_escape_string($_SESSION['horse']['name']);

        // Remove newline, carriage return symbols, and escape characters from the conformation notes
        $conf_notes = trim($conf_notes);
        $conf_notes = stripslashes($conf_notes);

        // Prepare SQL statement for updating conformation notes by horse name
        $stmt_admin = $conn->prepare("CALL UpdateConformationNotes(?, ?)");

        // Bind parameters and execute the SQL statement
        $stmt_admin->bind_param("ss", $conf_notes, $horse_name);
        $stmt_admin->execute();
    }
}

/**
 * Updates the phone number in the Web_user table, and (conditionally) the Client table.
 *
 * This function first updates the phone number in the Web_user table and then,
 * if the user is a Client, updates the phone number in the Client table.
 *
 * @param mysqli $conn - The MySQLi database connection.
 */
function update_phone_number($conn)
{

    // Must update the Web_user Table for admin and client
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {

        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
        $phone_number = $conn->real_escape_string($_SESSION['phone_number']);

        // Prepare SQL statement for Invoice retrieval by client name
        $stmt_user = $conn->prepare("UPDATE Web_user SET Phone_num = ? WHERE Username = ?;");
        $stmt_user->bind_param("ss", $phone_number, $username);
        $stmt_user->execute();
    } else {
        debug_to_console("Cannot update phone number.");
    }
    // Must update Client Table if user is a Client
    if (isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] == "Client") {

            // Get the username from the session and sanitize it
            $username = $conn->real_escape_string($_SESSION['username']);
            $phone_number = $conn->real_escape_string($_SESSION['phone_number']);

            // Prepare SQL statement for Invoice retrieval by client name
            $stmt_user = $conn->prepare("UPDATE Client SET Phone_num = ? WHERE Cusername = ?;");
            $stmt_user->bind_param("ss", $phone_number, $username);
            $stmt_user->execute();
        }
    }
}

/**
 * Updates the email address in the Web_user table, and (conditionally) the Client table.
 *
 * This function first updates the email in the Web_user table and then,
 * if the user is a Client, updates the email in the Client table.
 *
 * @param mysqli $conn - The MySQLi database connection.
 */
function update_email($conn)
{
    // Must update the Web_user Table for admin and client
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {

        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
        $email = $conn->real_escape_string($_SESSION['email']);

        // Prepare SQL statement for Invoice retrieval by client name
        $stmt_user = $conn->prepare("UPDATE Web_user SET Email = ? WHERE Username = ?;");
        $stmt_user->bind_param("ss", $email, $username);
        $stmt_user->execute();
    } else {
        debug_to_console("Cannot update email.");
    }
    // Must update Client Table if user is a Client
    if (isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] == "Client") {

            // Get the username from the session and sanitize it
            $username = $conn->real_escape_string($_SESSION['username']);
            $email = $conn->real_escape_string($_SESSION['email']);

            // Prepare SQL statement for Invoice retrieval by client name
            $stmt_user = $conn->prepare("UPDATE Client SET Email = ? WHERE Cusername = ?;");
            $stmt_user->bind_param("ss", $email, $username);
            $stmt_user->execute();
        }
    }
}

/**
 * Updates the password in the Web_user table, and (conditionally) the Client table.
 *
 * This function first updates the password in the Web_user table and then,
 * if the user is a Client, updates the password in the Client table.
 *
 * @param mysqli $conn - The MySQLi database connection.
 */
function update_password($conn)
{
    // Must update the Web_user Table for admin and client
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
        $password = $conn->real_escape_string($_SESSION['password']);
        // Prepare SQL statement for Invoice retrieval by client name
        $stmt_user = $conn->prepare("UPDATE Web_user SET Password = ? WHERE Username = ?;");
        $stmt_user->bind_param("ss", $password, $username);
        $stmt_user->execute();
    } else {
        debug_to_console("Cannot update password.");
    }
    // // Must update Client Table if user is a Client
    // if (isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
    //     if ($_SESSION['user_type'] == "Client") {
    //         // Get the username from the session and sanitize it
    //         $username = $conn->real_escape_string($_SESSION['username']);
    //         $password = $conn->real_escape_string($_SESSION['password']);
    //         // Prepare SQL statement for Invoice retrieval by client name
    //         $stmt_user = $conn->prepare("UPDATE Client SET Cpassword = ? WHERE Cusername = ?;");
    //         $stmt_user->bind_param("ss", $password, $username);
    //         $stmt_user->execute();
    //     }
    // }
}
