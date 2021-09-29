<?php
include('header.php');

// Define variables and initialize with empty values
$providerEmail = $providerPassword = $patientEmail = $patientPassword = '';
$providerEmail_err = NULL; $providerPassword_err = NULL; $ProviderLoginError = NULL;
$patientEmail_err = NULL; $patientPassword_err = NULL; $PatientLoginError = NULL;
$pat_err = NULL; $prov_err = NULL;



// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_SESSION["patient"]) && $_SESSION["patient"] === true){
        header("location: patient.php");
        exit;
    }
    if (isset($_SESSION["provider"]) && $_SESSION["provider"] === true){
        header("location: provider.php");
        exit;
    }
}

// Include config file
require_once "config.php";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // store who is trying to log in
    $providerLoginAttempt = isset($_POST["Provider_Login"]);
    $patientLoginAttempt = isset($_POST["Patient_Login"]);


    // if Provider signing in
    if ($providerLoginAttempt) {

        $prov_err = false;

        // Check if Email is empty
        if (empty(trim($_POST["providerEmail"]))) {
            $providerEmail_err = "Please enter Email.";
            $prov_err = true;
        } else {
            $providerEmail = trim($_POST["providerEmail"]);
        }

        // Check if password is empty
        if (empty(trim($_POST["providerPassword"]))) {
            $providerPassword_err = "Please enter your password.";
            $prov_err = true;
        } else {
            $providerPassword = trim($_POST["providerPassword"]);
        }
        if (!$prov_err){
            // Validate credentials
            // if (!isset($providerEmail_err) && !isset($providerPassword_err)) {
                // Prepare a select statement
                $sql = "SELECT providerId, providerEmail, providerName, providerPassword FROM Provider WHERE providerEmail = ?";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_providerEmail);

                    // Set parameters
                    $param_providerEmail = $providerEmail;

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Store result
                        mysqli_stmt_store_result($stmt);

                        // Check if providerEmail exists, if yes then verify password
                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            // Bind result variables
                            mysqli_stmt_bind_result($stmt, $providerId, $providerEmail, $providerName, $hashed_password);
                            if (mysqli_stmt_fetch($stmt)) {
                                if (password_verify($providerPassword, $hashed_password)) {
                                    // Password is correct, so start a new session
                                    // Store data in session variables
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["provider"] = true;
                                    $_SESSION["providerId"] = $providerId;
                                    $_SESSION["providerEmail"] = $providerEmail;
                                    $_SESSION["providerName"] = $providerName;

                                    // Redirect user to welcome page
                                    header("location: provider.php");
                                } else {
                                    // Password is not valid, display a generic error message
                                    $ProviderLoginError = "Invalid Email or password.";
                                }
                            }
                        } else {
                            // Email doesn't exist, display a generic error message
                            $ProviderLoginError = "Invalid Email or password.";
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            // }
                // Close connection
                mysqli_close($link);
        }
    };

    if ($patientLoginAttempt) {

        $par_err = false;
        // Check if Email is empty
        if (empty(trim($_POST["patientEmail"]))) {
            $patientEmail_err = "Please enter an Email.";
            $pat_err = true;
        } else {
            $patientEmail = trim($_POST["patientEmail"]);
        }

        // Check if password is empty
        if (empty(trim($_POST["patientPassword"]))) {
            $patientPassword_err = "Please enter your password.";
            $pat_err = true;
        } else {
            $patientPassword = trim($_POST["patientPassword"]);
        }

        // Validate credentials
        if(!$pat_err){
            // if (empty($patientEmail_err) && empty($patientPassword_err)) {
                // Prepare a select statement
                $sql = "SELECT patientId, patientEmail, patientName, patientPassword FROM Patient WHERE patientEmail = ?";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_patientEmail);

                    // Set parameters
                    $param_patientEmail = $patientEmail;

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Store result
                        mysqli_stmt_store_result($stmt);

                        // Check if patientEmail exists, if yes then verify password
                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            // Bind result variables
                            mysqli_stmt_bind_result($stmt, $patientId, $patientEmail, $patientName, $hashed_password);
                            if (mysqli_stmt_fetch($stmt)) {
                                if (password_verify($patientPassword, $hashed_password)) {
                                    // Password is correct, so start a new session

                                    // Store data in session variables
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["patient"] = true;
                                    $_SESSION["patientId"] = $patientId;
                                    $_SESSION["patientEmail"] = $patientEmail;
                                    $_SESSION["patientName"] = $patientName;

                                    // Redirect user to welcome page
                                    header("location: patient.php"); //need to change this
                                } else {
                                    // Password is not valid, display a generic error message
                                    $PatientLoginError = "Invalid Email or password.";
                                }
                            }
                        } else {
                            // Email doesn't exist, display a generic error message
                            $PatientLoginError = "Invalid Email or password.";
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close statement
                    mysqli_stmt_close($stmt);

                }
                // Close connection
                mysqli_close($link);
            // }
        }
    };

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.3/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
        }
        label {
            font: 20px sans-serif;
            }

    </style>
</head>

<body>


    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col col-md-5 ">
                <?php
                if (isset($_SESSION["success_message"])) {
                    echo $_SESSION["success_message"];
                    unset($_SESSION["success_message"]);
                }
                ?>
                <div class="card" style="align-items: center;">
                    <h2 class="card-title text-center mb-4 mt-4">
                        Log In
                    </h2>
                    <ul class="nav nav-pills mb-3 justify-content-md-center" style="border-radius: 10px;border: 2px solid #007BFF;" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo (isset($providerLoginAttempt) && $providerLoginAttempt === true) ? '' : 'active'; ?>" id="pills-patient-tab" data-bs-toggle="pill" data-bs-target="#pills-patient" type="button" role="tab" aria-controls="pills-patient" aria-selected="<?php echo (isset($providerLoginAttempt) && $providerLoginAttempt === true) ? 'false' : 'true'; ?>">Patient</button>
                        </li>
                        <li class=" nav-item" role="presentation">
                            <button class="nav-link <?php echo (isset($providerLoginAttempt) && $providerLoginAttempt === true) ? 'active' : ''; ?>" id="pills-provider-tab" data-bs-toggle="pill" data-bs-target="#pills-provider" type="button"  role="tab" aria-controls="pills-provider" aria-selected="<?php (isset($providerLoginAttempt) && $providerLoginAttempt === true) ? 'true' : 'false'; ?>">Provider</button>
                        </li>
                    </ul>

                    <div class="card-body" style="width:80%">
                        <div class="tab-content" id="pills-tabContent">

                        <?php
                        if (isset($PatientLoginError)) {
                            echo '<div class=" alert alert-dismissible alert-danger" style="padding: 1.0rem !important;">' .
                            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.
                            $PatientLoginError . '</div>';
                        }
                        ?>
                            <div class="tab-pane fade <?php echo (isset($providerLoginAttempt) && $providerLoginAttempt === true) ? '' : 'active show'; ?>" id="pills-patient" role="tabpanel" aria-labelledby="pills-patient-tab">


                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="form-group ">
                                        <label>Patient's Email:</label>
                                        <input type="text" name="patientEmail" placeholder ="Enter Email Here" class="form-control <?php echo (isset($patientEmail_err)) ? 'is-invalid' : ''; ?>" value="<?php echo (isset($patientEmail)) ? $patientEmail :'' ; ?>">
                                        <span class="invalid-feedback"><?php echo $patientEmail_err; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <input type="password" name="patientPassword" placeholder ="Enter Password Here" class="form-control <?php echo (isset($patientPassword_err)) ? 'is-invalid' : ''; ?>">
                                        <span class="invalid-feedback"><?php echo $patientPassword_err; ?></span>
                                    </div>
                                    <div class="form-group" style="display:flex; justify-content: center;">
                                        <input type="submit" class="btn btn-success" name="Patient_Login" value="Log In">
                                    </div>
                                    <div style="display:flex; justify-content: center;"> Don't have an account? <a style="margin-left: 5px" href="register_patient.php">Sign up now</a>.</div>

                                </form>

                            </div>
                            <div class="tab-pane fade <?php echo (isset($providerLoginAttempt) && $providerLoginAttempt === true) ? 'active show' : ''; ?>" id="pills-provider" role="tabpanel" aria-labelledby="pills-provider-tab">
                                <?php
                                if (isset($ProviderLoginError)) {
                                    echo '<div class=" alert alert-dismissible alert-danger " style="padding: 1.0rem !important;">' .
                                    '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.
                                    $ProviderLoginError . '</div>';
                                }
                                ?>

                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="form-group ">
                                        <label>Provider's Email:</label>
                                        <input type="text" name="providerEmail" placeholder ="Enter Email Here" class="form-control <?php echo (isset($providerEmail_err)) ? 'is-invalid' : ''; ?>" value="<?php echo (isset($providerEmail)) ? $providerEmail :''; ?>">
                                        <span class="invalid-feedback"><?php echo $providerEmail_err; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <input type="password" name="providerPassword" placeholder ="Enter Password Here" class="form-control <?php echo (isset($providerPassword_err)) ? 'is-invalid' : ''; ?>">
                                        <span class="invalid-feedback"><?php echo $providerPassword_err; ?></span>
                                    </div>
                                    <div class="form-group" style="display:flex; justify-content: center;">
                                        <input type="submit" class="btn btn-success" name="Provider_Login" value="Log In">
                                    </div>
                                    <div style="display:flex; justify-content: center;"> Don't have an account? <a style="margin-left: 5px" href="register_provider.php">Sign up now</a>.</div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
