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

// Define variables and initialize with empty values
$useremail = $announce = "";
$useremail_err = $annonce_err = "";
$entrepriseName = $announceType = $entrepriseInfo = $entropriseEmail = "";
$postNature = $postDescription = $postLocation = $postLimiteDate = $postNumber = $postDuration = $postProfile = "";
$targetFormation = $targetFormationLevel = $targetExperience = $targetLangues = $targetExtras = "";
$announce_err_state = $is_edit = false;


//this checks if the anounce is about to be updated
if (isset($_POST['announce_id'])) {
    //set is_edit variable
    $is_edit = true;

    // check if the id is a number
    if (is_numeric($_POST['announce_id'])) {

        //define variables
        $useremail = $_SESSION["useremail"];
        $annonce_obj = [];

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
            $sql = "SELECT announce FROM announces WHERE id = ? AND email = ?";

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
                    mysqli_stmt_bind_result($stmt, $announce);

                    // if the item got deleted
                    if (mysqli_stmt_num_rows($stmt) == 1) {

                        // Fetch rows and output data in table format
                        while (mysqli_stmt_fetch($stmt)) {
                            // extract announce data
                            $annonce_obj = json_decode($announce);

                            // setting up values
                            $entrepriseName = $annonce_obj->entreprise_info->entreprise_name;
                            $announceType = $annonce_obj->entreprise_info->announce_type;
                            $entrepriseInfo = $annonce_obj->entreprise_info->entreprise_info;
                            $entropriseEmail = $annonce_obj->entreprise_info->entreprise_email;

                            $postNature = $annonce_obj->offer_info->post_nature;
                            $postDescription = $annonce_obj->offer_info->post_description;
                            $postLocation = $annonce_obj->offer_info->post_location;
                            $postLimiteDate = $annonce_obj->offer_info->post_limite_date;
                            $postNumber = $annonce_obj->offer_info->post_number;
                            $postDuration = $annonce_obj->offer_info->post_duration;
                            $postProfile = $annonce_obj->offer_info->post_profile;

                            $targetFormation = $annonce_obj->target_info->target_formation;
                            $targetFormationLevel = $annonce_obj->target_info->target_formation_level;
                            $targetExperience = $annonce_obj->target_info->target_experience;
                            $targetLangues = $annonce_obj->target_info->target_languages;
                            $targetExtras = $annonce_obj->target_info->target_extras;
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

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$is_edit) {

    $useremail = $_SESSION["useremail"];

    // Check if enteprise name is empty
    if (empty(trim($_POST["entreprise_nom"]))) {
        $announce_err_state = true;
    } else {
        $entrepriseName = mysqli_real_escape_string($link, trim($_POST["entreprise_nom"]));
    }

    // Check if enteprise email is empty
    if (empty(trim($_POST["entreprise_email"]))) {
        $announce_err_state = true;
    } else {
        $entropriseEmail = mysqli_real_escape_string($link, trim($_POST["entreprise_email"]));
    }

    // Check if announce type is empty
    if (empty(trim($_POST["Type_annance"]))) {
        $announce_err_state = true;
    } else {
        $announceType = mysqli_real_escape_string($link, trim($_POST["Type_annance"]));
    }

    // Check if enteprise info is empty
    if (empty(trim($_POST["entreprise_apropos"]))) {
        $announce_err_state = true;
    } else {
        $entrepriseInfo = mysqli_real_escape_string($link, trim($_POST["entreprise_apropos"]));
    }

    // Check if post description is empty
    if (empty(trim($_POST["description-de-post"]))) {
        $announce_err_state = true;
    } else {
        $postDescription = mysqli_real_escape_string($link, trim($_POST["description-de-post"]));
    }

    // Check if post nature is empty
    if (empty(trim($_POST["nature_de_post"]))) {
        $announce_err_state = true;
    } else {
        $postNature = mysqli_real_escape_string($link, trim($_POST["nature_de_post"]));
    }

    // Check if post location is empty
    if (empty(trim($_POST["lieu"]))) {
        $announce_err_state = true;
    } else {
        $postLocation = mysqli_real_escape_string($link, trim($_POST["lieu"]));
    }

    // Check if limite date is empty
    if (empty(trim($_POST["date-limite"]))) {
        $announce_err_state = true;
    } else {
        $postLimiteDate = mysqli_real_escape_string($link, trim($_POST["date-limite"]));
    }

    // Check if enteprisename is empty
    if (empty(trim($_POST["nombre_de_post"]))) {
        $announce_err_state = true;
    } else {
        $postNumber = mysqli_real_escape_string($link, trim($_POST["nombre_de_post"]));
    }

    // Check if post number is empty
    if (empty(trim($_POST["nature_de_post"]))) {
        $announce_err_state = true;
    } else {
        $postNature = mysqli_real_escape_string($link, trim($_POST["nature_de_post"]));
    }

    // Check if post duration is empty
    if (empty(trim($_POST["duree"]))) {
        $announce_err_state = true;
    } else {
        $postDuration = mysqli_real_escape_string($link, trim($_POST["duree"]));
    }

    // Check if post profile is empty
    if (empty(trim($_POST["profile"]))) {
        $announce_err_state = true;
    } else {
        $postProfile = mysqli_real_escape_string($link, trim($_POST["profile"]));
    }

    // Check if formation is empty
    if (empty(trim($_POST["formation"]))) {
        $announce_err_state = true;
    } else {
        $targetFormation = mysqli_real_escape_string($link, trim($_POST["formation"]));
    }

    // Check if formation level is empty
    if (empty(trim($_POST["niveau-de-formation"]))) {
        $announce_err_state = true;
    } else {
        $targetFormationLevel = mysqli_real_escape_string($link, trim($_POST["niveau-de-formation"]));
    }

    // Check if experience is empty
    if (empty(trim($_POST["niveau-de-experience"]))) {
        $announce_err_state = true;
    } else {
        $targetExperience = mysqli_real_escape_string($link, trim($_POST["niveau-de-experience"]));
    }

    // Check if language is empty
    if (empty(trim($_POST["langues"]))) {
        $announce_err_state = true;
    } else {
        $targetLangues = mysqli_real_escape_string($link, trim($_POST["langues"]));
    }

    // Check if extras is empty
    if (!empty(trim($_POST["langues"]))) {
        $targetExtras = mysqli_real_escape_string($link, trim($_POST["extras"]));
    }

    // Validate credentials
    if (empty($useremail_err) && !$announce_err_state) {
        // create announce JSON
        $announce = array(
            "entreprise_info" => array(
                "entreprise_name" => $entrepriseName,
                "entreprise_info" => $entrepriseInfo,
                "entreprise_email" => $entropriseEmail,
                "announce_type" => $announceType
            ),
            "offer_info" => array(
                "post_nature" => $postNature,
                "post_description" => $postDescription,
                "post_location" => $postLocation,
                "post_number" => $postNumber,
                "post_profile" => $postProfile,
                "post_duration" => $postDuration,
                "post_limite_date" => $postLimiteDate
            ),
            "target_info" => array(
                "target_formation" => $targetFormation,
                "target_formation_level" => $targetFormationLevel,
                "target_experience" => $targetExperience,
                "target_languages" => $targetLangues,
                "target_extras" => $targetExtras
            ),
        );

        // Prepare a select statement
        $sql = "INSERT INTO announces (email, announce) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_useremail, $param_announce);

            // Set parameters

            $param_useremail = $useremail;
            $param_announce = json_encode($announce);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: announces.php");
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="main-content-container-ajt" id="main-content-container-ajt" name="ajt_dds">
            <div class="info-content-container-ajt-stp">
                <div class="stp-container" onclick="setStep(1)">
                    <div class="numb-container" id="numb-container-one">
                        <p id="numb-p-one">1</p>
                    </div>
                    <div class="txt-container">
                        <p>A PROPOS DE L'ENTREPRISE</p>
                    </div>
                </div>
                <div class="stp-container" onclick="setStep(2)">
                    <div class="numb-container" id="numb-container-two">
                        <p id="numb-p-two">2</p>
                    </div>
                    <div class="txt-container">
                        <p>A PROPOS DE L'OFFRE</p>
                    </div>
                </div>
                <div class="stp-container" onclick="setStep(3)">
                    <div class="numb-container" id="numb-container-tree">
                        <p id="numb-p-tree">3</p>
                    </div>
                    <div class="txt-container">
                        <p>COMPETENCES RECHERCHEES</p>
                    </div>
                </div>
            </div>
            <div class="info-content-container-ajt-flds-grid" id="info-content-container-ajt-flds">
                <div class="ajt-flds-total" id="ajt-flds-total-stp-one">
                    <div class="ajt-flds-lft">
                        <label for="entreprise_nom">NOM DE L'ENTREPRISE</label><br>
                        <input type="text" id="entreprise_nom" name="entreprise_nom" placeholder="Nom de l'Entreprise" required value="<?php echo $entrepriseName; ?>"><br>
                        <label for="type_annonce">TYPE D'ANNONCE</label><br>
                        <select id="type_annonce" name="Type_annance" required value="<?php echo $announceType; ?>" a>
                            <option value="Stage">Stage</option>
                            <option value="Emploi">Emploi</option>
                        </select><br>
                        <label for="entreprise_apropos">A PROPOS DE L'ENTREPRISE</label><br>
                        <textarea name="entreprise_apropos" id="entreprise_apropos" cols="30" rows="5" placeholder="A Propos de l'Entreprise (3 lign max)" required><?php echo $entrepriseInfo; ?></textarea>
                    </div>
                    <div class="ajt-flds-rght">
                        <label for="entreprise_email">EMAIL D'ENTREPRISE</label><br>
                        <input type="email" id="entreprise_email" name="entreprise_email" placeholder="Email" required value="<?php echo $entropriseEmail; ?>"><br>
                        <label for="entreprise_logo">LOGO D'ENTREPRISE (OPTIONNEL)</label><br>
                        <div class="add-logo-entreprise">
                            <img src="css/assets/iconmonstr-picture-10.svg" alt="add logo">
                            <p>ajouter votre logo d'entreprise</p>
                            <input type="file" name="file" id="file" class="inputfile" />
                            <label for="file">AJOUTER</label>
                        </div>
                    </div>
                </div>
                <div class="ajt-flds-total" id="ajt-flds-total-stp-two">
                    <div class="ajt-flds-lft">
                        <label for="entreprise_nom">NATURE DU STAGE</label><br>
                        <select id="nature_de_post" name="nature_de_post" required value="<?php echo $postNature; ?>">
                            <option value="Stage">Stage</option>
                            <option value="Emploi">Emploi</option>
                        </select><br>
                        <label for="type_annonce">PROFILE</label><br>
                        <input type="text" id="profile" name="profile" placeholder="Profile" required value="<?php echo $postProfile; ?>"><br>
                        <label for="duree">DUREE</label><br>
                        <input type="text" id="duree" name="duree" placeholder="Example: 23/06/2021-15/12/2023" required value="<?php echo $postDuration; ?>"><br>
                        <label for="duree">NOMBRE DE POSTES</label><br>
                        <input type="number" id="nombre_de_post" name="nombre_de_post" placeholder="Nombre de Postes" required value="<?php echo $postNumber; ?>"><br>
                    </div>
                    <div class="ajt-flds-rght">
                        <label for="description-de-post">DESCRIPTION DE POST</label><br>
                        <textarea name="description-de-post" id="description-de-post" cols="30" rows="5" placeholder="Description" required><?php echo $postDescription; ?></textarea>
                        <label for="lieu">LIEU</label><br>
                        <input type="text" id="lieu" name="lieu" placeholder="Lieu" required value="<?php echo $postLocation; ?>"><br>
                        <label for="date-limite">DATE LIMITE DE RECEPTION DES CVS</label><br>
                        <input type="text" id="date-limite" name="date-limite" placeholder="Example: 23/06/2021" required value="<?php echo $postLimiteDate; ?>"><br>
                    </div>
                </div>
                <div class="ajt-flds-total" id="ajt-flds-total-stp-tree">
                    <div class="ajt-flds-lft">
                        <label for="formation">FORMATION</label><br>
                        <select id="formation" name="formation" required value="<?php echo $targetFormation; ?>">
                            <option value="Stage">Stage</option>
                            <option value="Emploi">Emploi</option>
                        </select><br>
                        <label for="niveau-de-formation">NIVEAU DE FORMATIION</label><br>
                        <select id="niveau-de-formation" name="niveau-de-formation" required value="<?php echo $targetFormationLevel; ?>">
                            <option value="Stage">Stage</option>
                            <option value="Emploi">Emploi</option>
                        </select><br>
                        <label for="niveau-de-experience">NIVEAU D'EXPERIENCE</label><br>
                        <select id="niveau-de-experience" name="niveau-de-experience" required value="<?php echo $targetExperience; ?>>
                            <option value=" Stage">Stage</option>
                            <option value="Emploi">Emploi</option>
                        </select><br>
                        <label for="langues">LANGUE</label><br>
                        <select id="langues" name="langues" required value="<?php echo $targetLangues; ?>">
                            <option value="Stage">Stage</option>
                            <option value="Emploi">Emploi</option>
                        </select><br>
                    </div>
                    <div class="ajt-flds-rght">
                        <label for="extras">EXTRAS</label><br>
                        <textarea name="extras" id="extras" placeholder="extras (optionnel)" cols="50" rows="10"><?php echo $targetExtras; ?></textarea>
                    </div>
                </div>
                <div class="btn-action-stp">
                    <div class="btn-main-stp" id="btn-main-stp" onclick="setStep(stage+1)">
                        <p>SUIVANT</p>
                    </div>
                    <div class="btn-action" id="btn-preview-sub-stp">
                        <div class="btn-main-stp" onclick="previewDss()">
                            <p>PREVIEW</p>
                        </div>
                        <div class="btn-main-stp" onclick="ddsSubmit()">
                            <p>SUBMIT</p>
                            <input type="submit" style="visibility: hidden;">
                        </div>
                    </div>
                </div>

            </div>
        </form>
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
<script>
    var stage = 1;
    var btnNxt = true;
    var dds;
    var steps = [document.getElementById("ajt-flds-total-stp-one"),
        document.getElementById("ajt-flds-total-stp-two"),
        document.getElementById("ajt-flds-total-stp-tree")
    ];
    var stepsBg = [document.getElementById("numb-container-one"),
        document.getElementById("numb-container-two"),
        document.getElementById("numb-container-tree")
    ];
    var stepsP = [document.getElementById("numb-p-one"),
        document.getElementById("numb-p-two"),
        document.getElementById("numb-p-tree")
    ];

    function setStep(stageNum) {
        if (stageNum < 3)
            btnNxt = true;
        else
            btnNxt = false;
        if (stageNum != stage && stageNum < 4) {
            steps[stage - 1].style.visibility = "hidden";
            stepsBg[stage - 1].style.backgroundColor = "#ffffff";
            stepsP[stage - 1].style.color = "#529A0B";
            steps[stageNum - 1].style.visibility = "visible";
            stepsBg[stageNum - 1].style.backgroundColor = "#529A0B"
            stepsP[stageNum - 1].style.color = "#ffffff";
            stage = stageNum;
        }
        setStepBtns(btnNxt);
    }
    stepsBg[0].style.backgroundColor = "#529A0B";
    stepsP[0].style.color = "#ffffff";
    setStep(stage);

    function setStepBtns(isBtnNxt) {
        if (isBtnNxt) {
            document.getElementById("btn-main-stp").style.visibility = "visible";
            document.getElementById("btn-preview-sub-stp").style.visibility = "hidden";
        } else {
            document.getElementById("btn-main-stp").style.visibility = "hidden";
            document.getElementById("btn-preview-sub-stp").style.visibility = "visible";
        }
    }


    function ddsCheck() {

        var entrepriseName = document.forms["ajt_dds"]["entreprise_nom"];
        var annonceType = document.forms["ajt_dds"]["Type_annance"];
        var entrepriseInfo = document.forms["ajt_dds"]["entreprise_apropos"];
        var entropriseEmail = document.forms["ajt_dds"]["entreprise_email"];

        var postNature = document.forms["ajt_dds"]["nature_de_post"];
        var postDescription = document.forms["ajt_dds"]["description-de-post"];
        var postLocation = document.forms["ajt_dds"]["lieu"];
        var limiteDate = document.forms["ajt_dds"]["date-limite"];
        var postNumber = document.forms["ajt_dds"]["nombre_de_post"];
        var Duration = document.forms["ajt_dds"]["duree"];
        var profile = document.forms["ajt_dds"]["profile"];

        var formation = document.forms["ajt_dds"]["formation"];
        var formationLevel = document.forms["ajt_dds"]["niveau-de-formation"];
        var experience = document.forms["ajt_dds"]["niveau-de-experience"];
        var langues = document.forms["ajt_dds"]["langues"];

        var stpOneFlds = [entrepriseName, entropriseEmail, entrepriseInfo, annonceType];
        var stpTwoFlds = [postNature, postDescription, postLocation, postNumber, limiteDate, profile, Duration];
        var stpTreeFlds = [formation, formationLevel, experience, langues];

        for (let fld of stpOneFlds) {
            fld.addEventListener("focusout", (event) => {
                redCheck(fld);
            });
        }

        for (let fld of stpTwoFlds) {
            fld.addEventListener("focusout", (event) => {
                redCheck(fld);
            });
        }

        for (let fld of stpTreeFlds) {
            fld.addEventListener("focusout", (event) => {
                redCheck(fld);
            });
        }

        for (let fld of stpOneFlds) {
            if (fld.value == null || fld.value == "") {
                setStep(1);
                setRedBorders(fld);
                return false;
            }
        }
        for (let fld of stpTwoFlds) {
            if (fld.value == null || fld.value == "") {
                setStep(2);
                setRedBorders(fld);
                return false;
            }
        }
        for (let fld of stpTreeFlds) {
            if (fld.value == null || fld.value == "") {
                setStep(3);
                setRedBorders(fld);
                return false;
            }
        }

        var stpOneFldsValues = [entrepriseName.value, entropriseEmail.value, entrepriseInfo.value, annonceType.value];
        var stpTwoFldsValues = [postNature.value, postDescription.value, postLocation.value, postNumber.value, limiteDate.value, profile.value, Duration.value];
        var stpTreeFldsValues = [formation.value, formationLevel.value, experience.value, langues.value];

        dds = {
            "stpOneFlds": stpOneFldsValues,
            "stpTwoFlds": stpTwoFldsValues,
            "stpTreeFlds": stpTreeFldsValues
        };

        function setRedBorders(field) {
            field.style.borderColor = "red";
        }

        function redCheck(field) {
            if (field.value != null && field.value != "") {
                field.style.borderColor = "#529A0B";
            } else
                field.style.borderColor = "red";
        }
        return true;
    }

    function previewDss() {
        if (ddsCheck())
            window.open("ajPreview.html?dds=" + JSON.stringify(dds), "_blank");
    }

    function ddsSubmit() {
        if (ddsCheck()) {
            var form = document.getElementById("main-content-container-ajt");
            form.submit();
        }

    }
</script>

</html>