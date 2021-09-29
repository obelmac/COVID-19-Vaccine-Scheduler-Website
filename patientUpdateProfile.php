<?php
// Include config file
session_start();
require_once "config.php";
include('landing.php');

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


// Define variables and initialize with empty values
$patientEmail =  $patientPassword = $confirm_password = $patientName = $patientSurname = $ssn = $dob = $patientAddress = $patientCity = $patientState = $patientZipCode = $patientPhone = $distancePreference = "";
$patientEmail_err = $password_err = $confirm_password_err = $patientName_err = $patientSurname_err = $ssn_err = $dob_err = $patientAddress_err = $patientState_err = $patientCity_err = $patientZipCode_err = $patientPhone_err = $distancePreference_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $err = false;


    $patientAddress = trim($_POST["patientAddress"]);
    if (empty($patientAddress)) {
        $patientAddress_err = "Please enter a Address.";
        $err = true;
    }
    $patientCity = trim($_POST["patientCity"]);
    if (empty($patientCity)) {
        $patientCity_err = "Please enter a City.";
        $err = true;
    }
    $patientState = trim($_POST["patientState"]);
    if (empty($patientState)) {
        $patientState_err = "Please enter a State.";
        $err = true;
    }
    $patientZipCode = trim($_POST["patientZipCode"]);
    if (empty($patientZipCode)) {
        $patientZipCode_err = "Please enter a Zip Code.";
        $err = true;
    }
    $patientPhone = trim($_POST["patientPhone"]);
    if (empty($patientPhone)) {
        $patientPhone_err = "Please enter a Phone Number.";
        $err = true;
    }
    $distancePreference = trim($_POST["distancePreference"]);
    if (empty($distancePreference)) {
        $distancePreference_err = "Please enter a Distance Preference.";
        $err = true;
    }



    // } added before but it is not worked
    if (!$err) {
        // Prepare a select statement




            $patientLatitude = "";
            $patientLongitude = "";

            $patientTempAddr = "";
            $patientTempAddr.= $patientAddress;
            $patientTempAddr.= ", ";
            $patientTempAddr.= $patientCity;
            $patientTempAddr.= ", ";
            $patientTempAddr.= $patientState;
            $patientTempAddr.= ", ";
            $patientTempAddr.= $patientZipCode;

            $address = str_replace(" ", "+", $patientTempAddr);
            $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&key=AIzaSyA3mM2cTa1pPBc73_wsR2YEkpEb-W45b8k");
            $json = json_decode($json);
            empty($patientLatitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'});
            empty($patientLongitude = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'});
        

            $patientId = $_SESSION["patientId"];
            // Prepare an Update statement
            $sql = "UPDATE Patient SET patientAddress = ?, patientCity = ?, patientState = ?, patientZipCode = ?, patientLatitude = ?, patientLongitude = ?,
                                        patientPhone = ?, distancePreference = ?
                                      WHERE patientId = '$patientId'";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssddii",
                    $param_patientAddress,
                    $param_patientCity,
                    $param_patientState,
                    $param_patientZipCode,
                    $param_patientLatitude,
                    $param_patientLongitude,
                    $param_patientPhone,
                    $param_distancePreference
                );
                // Set parameters
                $param_patientAddress = $patientAddress;
                $param_patientCity = $patientCity;
                $param_patientState = $patientState;
                $param_patientZipCode = $patientZipCode;
                $param_patientLatitude = $patientLatitude;
                $param_patientLongitude = $patientLongitude;
                $param_patientPhone = $patientPhone;
                $param_distancePreference = $distancePreference;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    echo "Succesfully Udated Profile";

                } else {
                    echo "Oops! Something went wrong. Please try again later. after validate success";
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }

        // Close connection
        mysqli_close($link);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 18px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
            align-self: center;
        }

        label {
            font: 15px sans-serif;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <aside class="col-sm-9" style="margin: auto">
                <div class="card" style="align-items: center;">
                    <h2 class="card-title text-center mb-4 mt-4">
                        Patient Profile Update
                    </h2>
                    <p>Please fill this form to update account.</p>

                    <article class="card-body" style="width:90%">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="patientAddress" placeholder="Enter full address" class="form-control <?php echo (!empty($patientAddress_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientAddress; ?>">
                                <span class="invalid-feedback"><?php echo $patientAddress_err; ?></span>
                            </div>

                          <div class="form-group">
                              <label>City</label>
                              <input type="text" name="patientCity" placeholder="Enter full address" class="form-control <?php echo (!empty($patientCity_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientCity; ?>">
                              <span class="invalid-feedback"><?php echo $patientCity_err; ?></span>
                          </div>
                          <div class="form-group">
                              <label>State</label>
                              <input type="text" name="patientState" placeholder="Enter full address" class="form-control <?php echo (!empty($patientState_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientState; ?>">
                              <span class="invalid-feedback"><?php echo $patientState_err; ?></span>
                          </div>
                          <div class="form-group">
                              <label>Zip Code</label>
                              <input type="number" name="patientZipCode" placeholder="Enter full address" class="form-control <?php echo (!empty($patientZipCode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientZipCode; ?>">
                              <span class="invalid-feedback"><?php echo $patientZipCode_err; ?></span>
                          </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" pattern="\d{10}" name="patientPhone" placeholder="Enter number only without country code" class="form-control <?php echo (!empty($patientPhone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $patientPhone; ?>">
                                <span class="invalid-feedback"><?php echo $patientPhone_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Distance Preference</label>
                                <input type="text" pattern="\d{1,2}" placeholder="Enter maximum distance you are willing to travel to get vaccinated (1-99 miles)" name="distancePreference" class="form-control <?php echo (!empty($distancePreference_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $distancePreference; ?>">
                                <span class="invalid-feedback"><?php echo $distancePreference_err; ?></span>
                            </div>

                            <div class="form-group" style="display: flex;justify-content: center;">
                                <input type="submit" class="btn btn-success" value="Submit">
                            </div>
                            <div style="display: flex;justify-content: center;">

                            <p></p>
                            </div>
                        </form>
                </div>
            </aside>
        </div>
    </div>
</body>

</html>
