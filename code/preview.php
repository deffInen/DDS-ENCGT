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
$announceid = $announce_err = "";

$annonce_obj = [];

// Processing form data when form is submitted
if (isset($_POST['announce_id'])) {

    // Check if id is empty
    if (empty(trim($_POST["announce_id"]))) {
        $announce_err = "no anounce id was passed.";
        echo $announce_err;
        header("location: announces.php");
        exit();
    } else {
        $announceid = mysqli_real_escape_string($link, trim($_POST["announce_id"]));
    }

    // Validate credentials
    if (empty($announce_err)) {
        // Prepare a select statement
        $sql = "SELECT announce FROM announces WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_announce_id);

            // Set parameters
            $param_announce_id = $announceid;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {

                // Store result
                mysqli_stmt_store_result($stmt);

                // Bind result variables
                mysqli_stmt_bind_result($stmt, $announce);

                // if the item got deleted
                if (mysqli_stmt_num_rows($stmt) == 1) {

                    // Fetch rows and output data in table format
                    while (mysqli_stmt_fetch($stmt)) {
                        // extract announce data
                        $annonce_obj = json_decode($announce);
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
}
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
    <div class="main-bg-container-preview">
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
        <div class="main-content-container-dds">
            <div class="info-content-container-dds">
                <div class="top-logos">
                    <img src="https://encgt.ma/wp-content/uploads/2020/06/logo-web.png" alt="LOGO">
                    <div class="company-logo"></div>
                </div>
                <h1 class="annonce-title">OFFRE DE STAGE PFE (BAC+5)</h1>
                <h2 class="annonce-desc">Profil : Comptable</h2>
                <div class="annonce-aprop">
                    <div class="entro-aprop">
                        <h3>A PROPOS DE L'ENTREPRISE</h3>
                        <div class="line-break"></div>
                        <p class="entro-desc"><?php echo $annonce_obj->entreprise_info->entreprise_info;?></p>
                    </div>
                    <div class="offre-aprop">
                        <h3>A PROPOS DE L'OFFRE</h3>
                        <div class="line-break"></div>
                        <div class="key-value-desc">
                            <p class="key-desc">Nature</p>
                            <p class="value-desc"><?php echo $annonce_obj->entreprise_info->announce_type;?></p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Profile</p>
                            <p class="value-desc"><?php echo $annonce_obj->offer_info->post_profile;?></p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Duree</p>
                            <p class="value-desc"><?php echo $annonce_obj->offer_info->post_duration;?></p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Nombre de Poste</p>
                            <p class="value-desc"><?php echo $annonce_obj->offer_info->post_number;?></p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">lieu</p>
                            <p class="value-desc"><?php echo $annonce_obj->offer_info->post_location;?></p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Date Limite de Récepion des CVs</p>
                            <p class="value-desc"><?php echo $annonce_obj->offer_info->post_limite_date;?></p>
                        </div>
                        <p class="desc-desc">Description : <?php echo $annonce_obj->offer_info->post_description;?></p>
                    </div>
                    <div class="comp-aprop">
                        <h3>COMPETENCES RECHERCHEES</h3>
                        <div class="line-break"></div>
                        <div class="key-value-desc">
                            <p class="key-desc">Formation</p>
                            <p class="value-desc"><?php echo $annonce_obj->target_info->target_formation;?></p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Niveau de Formation</p>
                            <p class="value-desc"><?php echo $annonce_obj->target_info->target_formation_level;?></p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Experiences</p>
                            <p class="value-desc"><?php echo $annonce_obj->target_info->target_experience;?></p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Langues</p>
                            <p class="value-desc"><?php echo $annonce_obj->target_info->target_languages;?></p>
                        </div>
                        <p class="desc-desc">Extras : <?php echo $annonce_obj->target_info->target_extras;?></p>
                    </div>
                    <div class="instru-aprop">
                        <h3>INSTRUCTIONS</h3>
                        <div class="line-break"></div>
                        <p class="entro-desc">Pour postuler à cette offre, veuillez addresser votre CV en indiquant offre de stage PFE AUDI NORD aux adresses emails ci-aprés:</p>
                        <div class="emails-instru">
                            <p class="email-instru"><?php echo $annonce_obj->entreprise_info->entreprise_email;?></p>
                            <p class="email-instru">sce.recherche.cooperation@encgt.ma</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <button class="btn-telecharger">TELECHARGER</button>
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