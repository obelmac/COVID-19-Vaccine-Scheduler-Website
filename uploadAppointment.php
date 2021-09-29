<?php
// Initialize the session
session_start();
include('landing.php');
require_once "config.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
// stop patients from accessing provider page
if (isset($_SESSION["patient"]) && $_SESSION["patient"] === true){
    header("location: patient.php");
    exit;
}
?>
<?php

//check if add or remove slots to patient preferences an update
$providerId = $_SESSION["providerId"];
$date = "";
$start_time = "";

if(isset($_POST["date"]) && isset($_POST["start_time"])){

  $date = $_POST["date"];
  $start_time = $_POST["start_time"];
  $start_time_temp = "";
  $start_time_temp.= $date;
  $start_time_temp.= " ";
  $start_time_temp.= $start_time;

  $sql = "INSERT INTO appointments (start_time, providerId) VALUES (?, ?)";

  if ($stmt = mysqli_prepare($link, $sql)) {

    mysqli_stmt_bind_param($stmt,"si", $param_start_time, $param_providerId);
    $param_start_time = $start_time_temp;
    $param_providerId = $providerId;

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      echo "Succesfully Uploaded a New Appointment for the Following Date and Time: ";
      echo $start_time_temp;
    } else {
      echo "Failed to upload appointment, please insert a valid time";
    }
    // Close statement
    mysqli_stmt_close($stmt);
    mysqli_close($link);

  }
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
    <h1 class="my-5" style="color:white">Upload New Appointments For: <b><?php echo htmlspecialchars($_SESSION["providerName"]); ?></b></h1>
    <p>
      <a href="uploadAppointment.php" class="btn btn-primary">Upload an Appointment</a>
      <a href="uploadedAppointments.php" class="btn btn btn-success">View Uploaded Appointment</a>
    </p>
      <form action="uploadAppointment.php" method = POST>
        <label for="date"><b>Date:</b></label> <input type="date" name="date" value=""><br>

        <label for="start_time"><b>Start Time:</b></label> <input type="time" name="start_time" value="09:00:00" ><br>


        <br><input type="submit" class="btn btn-dark" value="Add New Appointment">
      </form>

      <p style="color:white">If you click the "Submit" button, the appointment will be added to your available appointments.</p>
  </body>
</html>
