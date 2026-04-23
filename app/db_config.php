<?php
/**
 * db_config.php
 * 
 * Central database connection for the Equiterra service.
 * All PHP files should replace their inline mysqli() call with:
 *
 *   require_once 'db_config.php';
 *
 * Environment variables are set by docker-compose.yml.
 * Falls back to localhost defaults when running outside Docker.
 */

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_name = getenv('DB_NAME') ?: 'equiterra';
$db_user = getenv('DB_USER') ?: 'equiterra_admin';
$db_pass = getenv('DB_PASS') ?: 'equiterra_password';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
