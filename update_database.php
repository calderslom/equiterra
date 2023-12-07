<?php

// Include functions
require_once 'utility.php';

/**
 * Updates the phone number in the Web_user table, and (conditionally) the Client table.
 *
 * This function first updates the phone number in the Web_user table and then,
 * if the user is a Client, updates the phone number in the Client table.
 *
 * @param mysqli $conn - The MySQLi database connection.
 */
function update_phone_number($conn) {
    // Must update the Web_user Table for admin and client
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
        $phone_number = $conn->real_escape_string($_SESSION['phone_number']);
        // Prepare SQL statement for Invoice retrieval by client name
        $stmt_user = $conn->prepare("UPDATE Web_user SET Phone_num = ? WHERE Username = ?;");
        $stmt_user->bind_param("ss", $phone_number, $username);
        $stmt_user->execute();
    }
    else {
        debug_to_console("Cannot update phone number.");
    }
    // Must update Client Table if user is a Client
    if(isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] == "Client"){
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
function update_email($conn) {
    // Must update the Web_user Table for admin and client
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
        $email = $conn->real_escape_string($_SESSION['email']);
        // Prepare SQL statement for Invoice retrieval by client name
        $stmt_user = $conn->prepare("UPDATE Web_user SET Email = ? WHERE Username = ?;");
        $stmt_user->bind_param("ss", $email, $username);
        $stmt_user->execute();
    }
    else {
        debug_to_console("Cannot update phone number.");
    }
    // Must update Client Table if user is a Client
    if(isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] == "Client"){
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
function update_password($conn) {
    // Must update the Web_user Table for admin and client
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
        $password = $conn->real_escape_string($_SESSION['password']);
        // Prepare SQL statement for Invoice retrieval by client name
        $stmt_user = $conn->prepare("UPDATE Web_user SET Password = ? WHERE Username = ?;");
        $stmt_user->bind_param("ss", $password, $username);
        $stmt_user->execute();
    }
    else {
        debug_to_console("Cannot update phone number.");
    }
    // Must update Client Table if user is a Client
    if(isset($_SESSION['user_type']) && !empty($_SESSION['user_type'])) {
        if ($_SESSION['user_type'] == "Client"){
        // Get the username from the session and sanitize it
        $username = $conn->real_escape_string($_SESSION['username']);
        $password = $conn->real_escape_string($_SESSION['password']);
        // Prepare SQL statement for Invoice retrieval by client name
        $stmt_user = $conn->prepare("UPDATE Client SET Cpassword = ? WHERE Cusername = ?;");
        $stmt_user->bind_param("ss", $password, $username);
        $stmt_user->execute();
        }
    }
}

?>
