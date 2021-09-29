<?php
// Initialize the session
session_start();
require_once "config.php";
include('landing.php');
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 18px sans-serif; text-align: center; }

    </style>
</head>
<body>
      <h1 class="my-5" style="color:white"><b>Please Confirm Appointment</b></h1>
      <p>
        <a href="scheduleAppointment.php" class="btn btn btn-success">Make an Appointment</a>
        <a href="editAppointment.php" class="btn btn-primary">Edit an Appointment</a>
        <a href="patientPreferences.php" class="btn btn btn-success">Update Availability/Preferences</a>
      </p>
</body>
</html>

<!DOCTYPE html>
<html>
<body>

<h2 style="color:white">Please select an option to update appointment offer status:</h2>


</body>
</html>

<?php
//echo $_POST["appointmentStatus"];
//echo $_POST["appointment_id"];
//echo $_POST["patientId"];

$connection = new mysqli("localhost", "root", "Lebo53200", "db_project_schema");

if ($connection->connect_error){
  die("Connection failed: ". $connection->connect_error);
}

$appointment_id = $_POST["appointment_id"];
$patientId = $_POST["patientId"];
$appointmentStatus = $_POST["appointmentStatus"];

if($appointmentStatus == "accept"){

  $appointmentStatus = "accepted";
  //$slot_id = $_POST["addSlotId"];
  $sql = "UPDATE offered SET appointment_status = ? WHERE appointment_id = $appointment_id AND patientId = $patientId ";
  if ($stmt = mysqli_prepare($connection, $sql)) {
    mysqli_stmt_bind_param($stmt,"s", $param_appointment_status);
    $param_appointment_status = $appointmentStatus;
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
    } else {
        echo "failed to accept appointment try again later";
    }
    // Close statement
    mysqli_stmt_close($stmt);
    mysqli_close($link);

  }

}
else if($appointmentStatus == "decline"){
  $appointmentStatus = "declined";
  $sql = "UPDATE offered SET appointment_status = ? WHERE appointment_id = $appointment_id AND patientId = $patientId ";
  if ($stmt = mysqli_prepare($connection, $sql)) {
    mysqli_stmt_bind_param($stmt,"s", $param_appointment_status);
    $param_appointment_status = $appointmentStatus;
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
    } else {
        echo "failed to accept appointment try again later";
    }
    // Close statement
    mysqli_stmt_close($stmt);
    mysqli_close($link);

  }

}
else {
  echo "Oops, something went wrong!!";
}

?>
