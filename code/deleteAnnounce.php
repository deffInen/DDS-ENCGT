<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: coneexion.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$announceid = $announce_err = "";

// Processing form data when form is submitted
if (isset($_POST['announce_id'])) {

    // Check if id is empty
    if (empty(trim($_POST["announce_id"]))) {
        $announce_err = "no anounce id was passed.";
        echo $announce_err;
        exit;
    } else {
        $announceid = mysqli_real_escape_string($link,trim($_POST["announce_id"]));
    }

    // Validate credentials
    if (empty($announce_err)) {
        // Prepare a select statement
        $sql = "DELETE FROM announces WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_announce_id);

            // Set parameters
            $param_announce_id = $announceid;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
        
                // if the item got deleted
                if (mysqli_affected_rows($link) > 0) {
                    // return to the announces page
                    header("location: announces.php");
                    
                } else {
                    // if the item did not delete or something went wrong
                    echo "something went wrong";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>