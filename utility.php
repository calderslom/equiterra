<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

// Function for outputting data to the console
function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

?>