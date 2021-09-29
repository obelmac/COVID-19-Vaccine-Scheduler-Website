<?php
// Initialize the session
session_start();
include('landing.php');
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title >Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 18px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5" style="color:white">Welcome, <b><?php echo htmlspecialchars($_SESSION["patientName"]); ?><br></b></h1></br>
    <p>
        <a href="scheduleAppointment.php" class="btn btn btn-success">Make an Appointment</a>
        <a href="editAppointment.php" class="btn btn-primary">Edit an Appointment</a>
        <a href="patientPreferences.php" class="btn btn btn-success">Update Availability/Preferences</a>
    </p>
  </body>
</html>
<p> <font color=white>Latest News on COVID-19 VACCINES:</font> </p>"
<iframe width="560" height="315" src="https://www.youtube.com/embed/j6Sn07fQeYM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<iframe width="560" height="315" src="https://www.youtube.com/embed/DpsDkSbliKw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
