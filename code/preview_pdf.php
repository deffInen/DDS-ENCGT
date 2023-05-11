<?php

// Include config file
require_once "config.php";

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: connexion.php");
  exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
//$announceid = null;
$announce_err = "";
$useremail = $_SESSION["useremail"];
$annonce_obj = [];

// Processing form data when form is submitted
if (isset($_GET['announce_id'])) {

  //check if the id is a number
  if (is_numeric($_GET['announce_id'])) {

    // Check if id is empty
    if (empty(trim($_GET["announce_id"]))) {
      $announce_err = "no anounce id was passed.";
      echo $announce_err;
      header("location: announces.php");
      exit();
    } else {
      $announceid = mysqli_real_escape_string($link, trim($_GET["announce_id"]));
    }

    
    // Validate credentials
    if (empty($announce_err)) {
      // Prepare a select statement
      $sql = "SELECT pdf FROM announces WHERE id = ? AND email = ?";
        
      if ($stmt = mysqli_prepare($link, $sql)) {

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "is", $param_announce_id, $param_email);

        // Set parameters
        $param_announce_id = $announceid;
        $param_email = $useremail;
        

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {

          // Store result
          mysqli_stmt_store_result($stmt);

          // Bind result variables
          mysqli_stmt_bind_result($stmt, $pdf_content_base64);

          // if the item got deleted
          if (mysqli_stmt_num_rows($stmt) == 1) {

            // Fetch rows and output data in table format
            while (mysqli_stmt_fetch($stmt)) {
              
              // Decode the PDF data back into its original format
              $pdf_content = base64_decode($pdf_content_base64);      
            }
          } else {
            // if the item did not delete or something went wrong
            echo "something went wrong";
            exit();
          }
        } else {
          echo "Oops! Something went wrong. Please try again later.";
          exit();
        }

        // Close statement
        mysqli_stmt_close($stmt);
      }
    }

    // Close connection
    mysqli_close($link);
  } else {
    header("location: announces.php");
    exit();
  }
}

$fileName = "dds_log_" . $announceid;

// Set the headers to indicate that this is a PDF file
header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=example.pdf");

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Content-Length: " . mb_strlen($pdf_content, "8bit"));
header('Content-Disposition: inline; filename="' . $fileName . '"');


// Output the PDF content
echo $pdf_content;

flush();

?>
