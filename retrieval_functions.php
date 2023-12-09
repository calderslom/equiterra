<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Include functions
require_once 'utility.php';



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

function check_user_exists($conn, $username, $email) {
    $stmt_user = $conn->prepare("CALL CheckUsernameEmail(?,?)");
    $stmt_user->bind_param("ss", $username, $email);
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();
    if ($user_tuple = $user_result->fetch_assoc()) {
        return true;
    } else {
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
