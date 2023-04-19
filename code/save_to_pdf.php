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
$announceid = null;
$announce_err = "";
$useremail = $_SESSION["useremail"];
$annonce_obj = [];

// Processing form data when form is submitted
if (isset($_POST['announce_id'])) {

    //check if the id is a number
    if (is_numeric($_POST['announce_id'])) {

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

require_once 'vendor/autoload.php';

use Dompdf\Dompdf;

//set options
$options = new \Dompdf\Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true);
$options->set('chroot', '/var/www/html/DDS/DDS-ENCGT/code');
$options->set('base_path', '/css/assets/');

// Instantiate Dompdf
$dompdf = new Dompdf($options);

// HTML content to be converted to PDF
$html = '
<html lang="en">
<style>

* {
    margin: 0;
    padding: 0;
}

body{
    margin-top:20px;
}

.main-content-container-dds{
    height: 1120px;
    width: 793px;
}

.info-content-container-dds{
  height: 1120px;
  width: 793px;
  background-color: white;
}

.info-content-container-dds p{
  color: #7F7F7F;
}

.info-content-container-dds img{
  height: 100px;
  float: left;
  margin-left:20px;
}

.top-logos{
    width: 793px;
    height: 105px;
}

.company-logo{
  height: 100px;
  width: 200px;
  float: right;
  background-color: #7F7F7F;
  margin-right:20px;
}

.annonce-title{
  text-align: center;
  width: 100%;
  margin-top: 30px;
  color: #529A0B;
}

.annonce-desc{
  text-align: center;
  width: 100%;
  margin-top: 10px;
  color: #529A0B;
}

.annonce-aprop{
  width: 100%;
  margin-top: 30px;
}

.entro-aprop{
  width: 90%;
  margin: 0 auto;
  margin-bottom: 20px;
}

.offre-aprop{
  width: 90%;
  margin: 0 auto;
  margin-bottom: 20px;
}

.comp-aprop{
  width: 90%;
  margin: 0 auto;
  margin-bottom: 20px;
}

.instru-aprop{ 
  width: 90%;
  margin: 0 auto;
}

.line-break{
  width: 100%;
  height: 2px;
  margin-bottom: 10px;
  background-color: #529A0B;
}

.key-value-desc{
  width: 100%;
  height: 30px;
  margin-bottom: 10px;
}

.key-desc{
  float: left;
}

.value-desc{
  float: right;
  max-width: 65%;
}

.desc-desc{
  width: 100%;
}

.emails-instru{
  width: 100%;
  margin-top: 10px;
}

.email-instru{
  width: 100%;
  margin-bottom: 5px;
  text-align: end;
  font-weight: bold;
  text-align: right;
}


.info-content-container-dds h3{
  color: #529A0B;
  font-weight: bold;
  text-align: left;
  width: 100%;
}

</style>

<body>
        <div class="main-content-container-dds">
            <div class="info-content-container-dds">
                <div class="top-logos">
                    <img src="css/assets/logo-web.jpg" alt="LOGO">
                    <div class="company-logo"></div>
                </div>
                <h1 class="annonce-title">OFFRE DE STAGE PFE (BAC+5)</h1>
                <h2 class="annonce-desc">Profil : Comptable</h2>
                <div class="annonce-aprop">
                    <div class="entro-aprop">
                        <h3>A PROPOS DE LENTREPRISE</h3>
                        <div class="line-break"></div>
                        <p class="entro-desc">'.$annonce_obj->entreprise_info->entreprise_info.'</p>
                    </div>
                    <div class="offre-aprop">
                        <h3>A PROPOS DE LOFFRE</h3>
                        <div class="line-break"></div>
                        <div class="key-value-desc">
                            <p class="key-desc">Nature</p>
                            <p class="value-desc">'.$annonce_obj->entreprise_info->announce_type.'</p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Profile</p>
                            <p class="value-desc">'.$annonce_obj->offer_info->post_profile.'</p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Duree</p>
                            <p class="value-desc">'.$annonce_obj->offer_info->post_duration.'</p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Nombre de Poste</p>
                            <p class="value-desc">'.$annonce_obj->offer_info->post_number.'</p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">lieu</p>
                            <p class="value-desc">'.$annonce_obj->offer_info->post_location.'</p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Date Limite de Récepion des CVs</p>
                            <p class="value-desc">'.$annonce_obj->offer_info->post_limite_date.'</p>
                        </div>
                        <p class="desc-desc">Description : '.$annonce_obj->offer_info->post_description.'</p>
                    </div>
                    <div class="comp-aprop">
                        <h3>COMPETENCES RECHERCHEES</h3>
                        <div class="line-break"></div>
                        <div class="key-value-desc">
                            <p class="key-desc">Formation</p>
                            <p class="value-desc">'.$annonce_obj->target_info->target_formation.'</p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Niveau de Formation</p>
                            <p class="value-desc">'.$annonce_obj->target_info->target_formation_level.'</p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Experiences</p>
                            <p class="value-desc">'.$annonce_obj->target_info->target_experience.'</p>
                        </div>
                        <div class="key-value-desc">
                            <p class="key-desc">Langues</p>
                            <p class="value-desc">'.$annonce_obj->target_info->target_languages.'</p>
                        </div>
                        <p class="desc-desc">Extras : '.$annonce_obj->target_info->target_extras.'</p>
                    </div>
                    <div class="instru-aprop">
                        <h3>INSTRUCTIONS</h3>
                        <div class="line-break"></div>
                        <p class="entro-desc">Pour postuler à cette offre, veuillez addresser votre CV en indiquant le titre de loffre et le poste aux adresses emails ci-aprés:</p>
                        <div class="emails-instru">
                            <p class="email-instru">'.$annonce_obj->entreprise_info->entreprise_email.'</p>
                            <p class="email-instru">sce.recherche.cooperation@encgt.ma</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
';


// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();


//generate the output name
$pdfname = 'dds_annonce_' . $announceid . '.pdf';

// Output the generated PDF to the browser
$dompdf->stream($pdfname);

// Output the generated PDF to the browser
//file_put_contents('pdf-file5.pdf', $dompdf->output());

?>

