<?php
// Include config file
require_once "config.php";


// Define variables and initialize with empty values
$username = $password = $userpost = $useremail = $userphone = $entreprisename = "";
$username_err = $password_err = $userpost_err = $useremail_err = $userphone_err = $entreprisename_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["user_name"]))) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_ ]+$/', trim($_POST["user_name"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    }else{
        $username = mysqli_real_escape_string($link,trim($_POST["user_name"]));
    }

    // Validate password
    if (empty(trim($_POST["user_password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["user_password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = mysqli_real_escape_string($link,trim($_POST["user_password"]));
    }

    // Validate user post
    if (empty(trim($_POST["user_post"]))) {
        $userpost_err = "Please add post.";
    }else{
        $userpost = mysqli_real_escape_string($link,trim($_POST["user_post"]));
    }
    
    // Validate user email
    if (empty(trim($_POST["user_email"]))) {
        $useremail_err = "Please add email.";
    }elseif(!filter_var(trim($_POST["user_email"]), FILTER_VALIDATE_EMAIL)) {
        $useremail_err = "Please add a valid email.";
    }else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = mysqli_real_escape_string($link,trim($_POST["user_email"]));
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $useremail_err = "This email is already taken.";
                } else{
                    $useremail = mysqli_real_escape_string($link,trim($_POST["user_email"]));
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate entreprise name
    if (empty(trim($_POST["entreprise_name"]))) {
        $entreprisename_err = "Please add entrepise name.";
    }else{
        $entreprisename = mysqli_real_escape_string($link,trim($_POST["entreprise_name"]));
    }

    // Validate user phone
    if (empty(trim($_POST["user_phone"]))) {
        $userpassword_err = "Please add a phone nomber.";
    }else{
        $userphone = mysqli_real_escape_string($link,trim($_POST["user_phone"]));
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($useremail_err) 
        && empty($userphone_err) && empty($entreprisename_err) && empty($userpost_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password, entreprise, post, phone) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_useremail, $param_password, $param_entreprise, $param_userpost, $param_userphone);

            // Set parameters
            $param_username = $username;
            $param_useremail = $useremail;
            $param_entreprise = $entreprisename;
            $param_userpost = $userpost;
            $param_userphone = $userphone;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: connexion.php");
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

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="main-bg-container">
        <nav>
            <div>
                <a href="">
                    <img src="https://encgt.ma/wp-content/uploads/2020/06/logo-web.png" alt="LOGO">
                </a>
                <p>NOUS CONTACTER</p>
            </div>
        </nav>
        <div class="main-content-container">


            <div class="wrapper">
                <div class="form-container">
                    <div class="slide-controls">
                    <p class="connect-title">INSCRIPTION</p>
                    </div>
                    <div class="form-inner">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="signup">
                            <div class="field">
                                <input type="text" name="user_name" placeholder="Nom et Prenom" required>  
                            </div>
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                            <div class="field">
                                <input type="text" name="entreprise_name" placeholder="Entreprise" required>  
                            </div>
                            <span class="invalid-feedback"><?php echo $entreprisename_err; ?></span>
                            <div class="field">
                                <input type="text" name="user_post" placeholder="Post" required>
                            </div>
                            <span class="invalid-feedback"><?php echo $userpost_err; ?></span>
                            <div class="field">
                                <input type="tel" name="user_phone" placeholder="Tel" required>
                            </div>
                            <span class="invalid-feedback"><?php echo $userphone_err; ?></span>
                            <div class="field">
                                <input type="email" name="user_email" placeholder="Email" required>
                            </div>
                            <span class="invalid-feedback"><?php echo $useremail_err; ?></span>
                            <div class="field">
                                <input type="password" name="user_password" placeholder="Password" required>
                            </div>
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            <div class="field btn">
                                <div class="btn-layer"></div>
                                <input type="submit" value="INSCRIRE">
                            </div>
                            <div class="signup-link">
                                Deja un Membre? <a href="/DDS/DDS-ENCGT/code/connexion.php">connecter d'abord</a>
                            </div>
                        </form>
                    </div>
                </div>
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