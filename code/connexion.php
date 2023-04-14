<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$useremail = $password = "";
$useremail_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["user_email"]))) {
        $useremail_err = "Please enter username.";
    } else {
        $useremail = trim($_POST["user_email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($useremail_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_useremail);

            // Set parameters
            $param_useremail = $useremail;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $useremail, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_destroy();
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["useremail"] = $useremail;

                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
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
                <p>NOUS CONTACTER</p>
            </div>
        </nav>
        <div class="main-content-container">
            <div class="wrapper">
                <div class="form-container">
                    <div class="slide-controls">
                        <p class="connect-title">CONNEXION</p>
                        <?php
                        if (!empty($login_err)) {
                            echo '<div class="alert alert-danger">' . $login_err . '</div>';
                        }
                        ?>
                    </div>
                    <div class="form-inner">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login">
                            <div class="field">
                                <input type="text" name="user_email" placeholder="Email Address" required>
                                <span class="invalid-feedback"><?php echo $useremail_err; ?></span>
                            </div>
                            <div class="field">
                                <input type="password" name="password" placeholder="Password" required>
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="pass-link">
                                <a href="#">Forgot password?</a>
                            </div>
                            <div class="field btn">
                                <div class="btn-layer"></div>
                                <input type="submit" value="CONNECTER">
                            </div>
                            <div class="signup-link">
                                Pas un Membre? <a href="/DDS/DDS-ENCGT/code/inscription.php">Inscrire d'abord</a>
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
                <div class="footer-section-filler"></div>
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