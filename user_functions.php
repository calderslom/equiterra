<?php

session_start();

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
        // Check if the clientNameResult was successful
        if ($user_result) {
            // Get the Client Tuple returned by the SQL Query
            $user_tuple = $user_result->fetch_assoc();
            return $user_tuple;
        } else return false;
    } else return false;
}

?>