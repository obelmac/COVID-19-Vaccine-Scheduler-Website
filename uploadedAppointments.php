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
    <h1 class="my-5" style="color:white" >Uploaded Appointments For: <b><?php echo htmlspecialchars($_SESSION["providerName"]); ?></b></h1>
    <p>
        <a href="uploadAppointment.php" class="btn btn-primary">Upload an Appointment</a>
        <a href="uploadedAppointments.php" class="btn btn btn-success">View Uploaded Appointment</a>
        <p class="my-5" style="color:white">"1" Indicates Appointment Still Available & "0" Indicates Not Available</p>
    </p>
  </body>
</html>

<?php

$connection = new mysqli("localhost", "root", "Lebo53200", "db_project_schema");

if ($connection->connect_error){
  die("Connection failed: ". $connection->connect_error);
}

$providerId = $_SESSION["providerId"];

$sqlQuery = "SELECT * FROM appointments WHERE providerId = $providerId ORDER BY start_time DESC";

$result = $connection->query($sqlQuery);

if ($result->num_rows > 0) {
  echo  '<table border="0" cellspacing="2" cellpadding="2">
        <tr>
            <form>
            <input type="text" name=appointment_id value="appointment id" style="width: 200px;" readonly />
            <input type="text" name=start_time value="start time" style="width: 220px;" readonly />
            <input type="text" name=appointment_available value="appointment available" style="width: 240px;" readonly />
            </form>
            <br>
        </tr>';

    while ($row = $result->fetch_assoc()) {
        $appointment_id = $row["appointment_id"];
        $start_time = $row["start_time"];
        $appointment_available = $row["appointment_available"];

        echo '<tr>
                  <form action="patientPreferences.php" method="post">

                  <input type="text" name=appointment_id value="'.$appointment_id.'" style="width: 200px;" readonly />
                  <input type="text" name="start_time" value="'.$start_time.'" style="width: 220px;" readonly >
                  <input type="text" name="appointment_available" value="'.$appointment_available.'" style="width: 240px;" readonly >
                  </form>
                  <br>
              </tr>';
    }
    $result->free();
}
else{
  echo "No records exist for that search";
}

?>
