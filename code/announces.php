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
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
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
                    <li><a href="/DDS/DDS-ENCGT/code/announces.php" class="menu-option" id="active">ANNONCES</a></li>
                    <li><a href="/DDS/DDS-ENCGT/code/profile.php" class="menu-option">PROFILE</a></li>
                </ul>
            </div>
        </nav>
        <div class="main-content-container">
            <div class="info-content-container">
                <h2>ANNONCES</h2>

                <?php

                // Prepare an insert statement
                $sql = "SELECT id, announce, created_at FROM announces WHERE email = ?";

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
                        mysqli_stmt_bind_result($stmt, $id, $announce, $created_at);

                        // Check if any rows were returned
                        if (mysqli_stmt_num_rows($stmt) > 0) {
                            // this keeps track of table indexing
                            $tableindex = 0;

                            // Output table header
                            echo '<div class="info-title-data-container">
                            <table style="width: 100%;">
                                <tr>
                                    <th style="width: 5%;">Num</th>
                                    <th style="width: 13%;">Date</th>
                                    <th style="width: 13%;">Type d\'Offre</th>
                                    <th style="width: 13%;">Profile</th>
                                    <th style="width: 11.5%;">Num de Post</th>
                                    <th style="width: 13%;">Lieu</th>
                                    <th style="width: 10%;">Date Limite</th>
                                    <th style="width: 7.16%;">Voir</th>
                                    <th style="width: 7.16%;">Modifier</th>
                                    <th style="width: 7.16%;">Suprimer</th>
                                </tr>';

                            // Fetch rows and output data in table format
                            while (mysqli_stmt_fetch($stmt)) {
                                $annonce_obj = json_decode($announce);
                                $tableindex++;

                                echo '<tr>
                                    <td>' . $tableindex . '</td>' .
                                    //this is used to show only the date of creating the announce
                                    '<td>' . substr($created_at, 0, strpos($created_at, " ")) . '</td>
                                    <td>' . $annonce_obj->entreprise_info->announce_type . '</td>
                                    <td>' . $annonce_obj->offer_info->post_profile . '</td>
                                    <td>' . $annonce_obj->offer_info->post_number . '</td>
                                    <td>' . $annonce_obj->offer_info->post_location . '</td>
                                    <td>' . $annonce_obj->offer_info->post_limite_date . '</td>
                                    <td>
                                        <img src="css/assets/visibility_FILL1_wght400_GRAD0_opsz48.svg" alt="Voir" id="' . $id . '" onclick="view_item()">
                                        <form method="post" action="save_to_pdf.php" style="visibility=hidden;" id="view_item_form">
                                            <input type="hidden" name="announce_id" value="' . $id . '">
                                        </form>
                                    </td>
                                    <td>
                                        <img src="css/assets/edit_FILL1_wght400_GRAD0_opsz48.svg" alt="edit" id="' . $id . '" onclick="edit_item()">
                                        <form method="post" action="ajouter.php" style="visibility=hidden;" id="edit_item_form">
                                            <input type="hidden" name="announce_id" value="' . $id . '">
                                        </form>
                                        
                                    </td>
                                    <td><img src="css/assets/Mask Group 3.svg" alt="delete" id="' . $id . '"  onclick="delete_item()">
                                        <form method="post" action="deleteAnnounce.php" style="visibility=hidden;" id="delete_item_form">
                                            <input type="hidden" name="announce_id" value="' . $id . '">
                                        </form>
                                    </td>
                                </tr>';
                            }

                            // Output table footer
                            echo "</table>";
                            echo "</div>";
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

                <a class="btn-modifier" href="/DDS/DDS-ENCGT/code/ajouter.php">AJOUTER</a>
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
        <script>
            function delete_item() {
                var delete_item_form = document.getElementById("delete_item_form");
                delete_item_form.submit();
            }

            function view_item() {
                var view_item_form = document.getElementById("view_item_form");
                view_item_form.submit();
            }

            function edit_item(){
                var edit_item_form = document.getElementById("edit_item_form");
                edit_item_form.submit();
            }

        </script>
</body>
</html>