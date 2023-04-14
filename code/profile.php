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

$useremail = $_SESSION["useremail"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main-bg-container">
        <nav>
            <div>
                <a href="">
                    <img src="https://encgt.ma/wp-content/uploads/2020/06/logo-web.png" alt="LOGO">
                </a>
                <ul>
                    <li><a href="/DDS/DDS-ENCGT/code/logout.php" class="btn-decconecter">DECCONECTER</a></li>
                    <li><a href="/DDS/DDS-ENCGT/code/announces.php" class="menu-option">ANNONCES</a></li>
                    <li><a href="/DDS/DDS-ENCGT/code/profile.php" class="menu-option" id="active">PROFILE</a></li>
                </ul> 
            </div>
        </nav>
        <div class="main-content-container">
            <div class="info-content-container">
                <h2>PROFILE</h2>

                <?php

                // Prepare an insert statement
                $sql = "SELECT username, post, entreprise, phone FROM users WHERE email = ?";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_useremail);

                    // Set parameters
                    $param_useremail = $useremail;

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {

                        // Store result
                        mysqli_stmt_store_result($stmt);

                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $username, $post, $entreprise, $phone);

                        // Check if any rows were returned
                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            
                            // Fetch rows and output data in table format
                            while (mysqli_stmt_fetch($stmt)) {
                                echo '
                                <div class="info-title-data-container">
                                    <div class="info-title">
                                    <p>Nom et Prenom</p>
                                    <p>Email</p>
                                    <p>Tel</p>
                                    <p>Entreprise</p>
                                    <p>Poste</p>
                                </div>';
                                echo '
                                <div class="info-data">
                                    <p>'.$username.'</p>
                                    <p>'.$useremail.'</p>
                                    <p>'.$phone.'</p>
                                    <p>'.$entreprise.'</p>
                                    <p>'.$post.'</p>
                                </div>
                            </div>
                            <a class="btn-modifier" href="">MODIFIER</a>
                                ';  
                            }

                            
                        } else {
                            echo '<p class="msg_no_result">No results found.</p>';
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                }

                // Close connection
                mysqli_close($link);

                ?>

                
                    
            </div>
        </div>
    </div>

    <div class="footer">
        <footer>
            <div class="footer-top">
                <div class="footer-section-one">
                    <div class="footer-section-one-container">
                        <img src="https://encgt.ma/wp-content/uploads/2020/06/logo-web.png" alt="LOGO">
                        <p>L’ENCG Tanger est un établissement d’enseignement supérieur public qui a 
                        pour mission de former les lauréats aux métiers du commerce et de gestion.</p>
                    </div>
                    
                </div>
                <div class="footer-section-filler">
                    
                </div>
                <div class="footer-section-two">
                    <div class="footer-section-two-container">
                        <p class="footer-title">EMAIL</p>
                        <p class="footer-sub-info">encgtanger@encgt.ma</p>
                        <p class="footer-title-mt">ADRESS</p>
                        <p class="footer-sub-info">Route de l’aéroport, B.P 1255,90000 Tanger, Maroc</p>
                    </div>
                </div>
                <div class="footer-section-tree">
                    <div class="footer-section-tree-container">
                        <p class="footer-title">Service formation continue</p>
                        <p class="footer-sub-info">Tél. +212 (0) 539 313 489</p>
                        <p class="footer-title-mt">Standard de l’ENCGT</p>
                        <p class="footer-sub-info">Tél. +212 (0) 539 313 4 87</p>
                        <p class="footer-sub-info-mt">Tél. +212 (0) 539 313 4 88</p>
                    </div>
                </div>
            </div>
            <div class="footer-sub">
                <div class="cp-container">
                    <p>© ENCGT 2023</p>
                </div>
                <div class="icons-container">
                    <img src="css/assets/Path 1.svg" alt="logo3">
                    <img src="css/assets/Path 2.svg" alt="logo2">
                    <img src="css/assets/Path 3.svg" alt="logo1">
                </div>
                
            </div>
        </footer>
    </div>
</body>
</html>