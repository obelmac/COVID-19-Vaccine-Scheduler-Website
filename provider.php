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
    <h1 class="my-5" style="color:white">Welcome, <b><?php echo htmlspecialchars($_SESSION["providerName"]); ?><br></b></h1></br>
    <p>
      <a href="uploadAppointment.php" class="btn btn-primary">Upload an Appointment</a>
      <a href="uploadedAppointments.php" class="btn btn btn-success">View Uploaded Appointment</a>
    </p>
  </body>
</html>
<p> <font color=white>Latest News on COVID-19 VACCINES For Providers:</font> </p>"
<iframe width="560" height="315" src="https://www.youtube.com/embed/gs2pPZYoIH8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<iframe width="560" height="315" src="https://www.youtube.com/embed/EV6YXdVNtl0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
