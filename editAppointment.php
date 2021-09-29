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
      <h1 class="my-5" style="color:white"><b>Please Select an Appointment You Would Like To Change</b></h1>
      <p>
        <a href="scheduleAppointment.php" class="btn btn btn-success">Make an Appointment</a>
        <a href="editAppointment.php" class="btn btn-primary">Edit an Appointment</a>
        <a href="patientPreferences.php" class="btn btn btn-success">Update Availability/Preferences</a>
      </p>
</body>
</html>

<?php
$patientId = $_SESSION["patientId"];

$connection = new mysqli("localhost", "root", "Lebo53200", "db_project_schema");

if ($connection->connect_error){
  die("Connection failed: ". $connection->connect_error);
}

$sqlQuery = "
              WITH t1 AS(SELECT aps.appointment_id, pr.providerName, aps.start_time,
                          pr.providerAddress, pr.providerCity, pr.providerState,
                          pr.providerZipCode, ofr.patientId, pr.providerLatitude,
                            pr.providerLongitude
                          FROM offered ofr JOIN appointments aps ON  ofr.appointment_id = aps.appointment_id
                          JOIN provider pr ON aps.providerId = pr.providerId
                          WHERE patientId = $patientId AND appointment_status = 'accepted'
                          )
              SELECT  t1.appointment_id, t1.providerName, t1.start_time,
              t1.providerAddress, t1.providerCity, t1.providerState,
              t1.providerZipCode, ROUND ((haversine(t1.providerLatitude, t1.providerLongitude, pat.patientLatitude, pat.patientLongitude) / 8)*5, 2) as distance
              FROM t1 JOIN patient pat ON t1.patientId = pat.patientId ";

$result = $connection->query($sqlQuery);

if ($result->num_rows > 0) {
  echo  '<table border="0" cellspacing="2" cellpadding="2">
        <tr>
            <form>
            <input type="text" name=appointment_id value="Select Appointment id" style="width: 200px;" readonly />
            <input type="text" name=providerName value="Provider Name" style="width: 220px;" readonly />
            <input type="text" name=start_time value="start time" style="width: 220px;" readonly />
            <input type="text" name=providerAddress value="Address" style="width: 220px;" readonly />
            <input type="text" name=providerCity value="City" style="width: 220px;" readonly />
            <input type="text" name=providerState value="Provider State" style="width: 220px;" readonly />
            <input type="text" name=providerZipCode value="Zip Code" style="width: 220px;" readonly />
            <input type="text" name=distance value="Distance (m)" style="width: 240px;" readonly />
            </form>
            <br>
        </tr>';

    while ($row = $result->fetch_assoc()) {
        $appointment_id = $row["appointment_id"];
        $providerName = $row["providerName"];
        $start_time = $row["start_time"];
        $providerAddress = $row["providerAddress"];
        $providerCity = $row["providerCity"];
        $providerState = $row["providerState"];
        $providerZipCode = $row["providerZipCode"];
        $distance = $row["distance"];

        echo '<tr>
                  <form action="cancelAppointment.php" method="post">
                  <input type="submit" name=appointment_id value="'.$appointment_id.'" style="width: 200px;" readonly />
                  <input type="text" name=providerName value="'.$providerName.'" style="width: 220px;" readonly />
                  <input type="text" name=start_time value="'.$start_time.'" style="width: 220px;" readonly />
                  <input type="text" name=providerAddress value="'.$providerAddress.'" style="width: 220px;" readonly />
                  <input type="text" name=providerCity value="'.$providerCity.'" style="width: 220px;" readonly />
                  <input type="text" name=providerState value="'.$providerState.'" style="width: 220px;" readonly />
                  <input type="text" name=providerZipCode value="'.$providerZipCode.'" style="width: 220px;" readonly />
                  <input type="text" name=distance value="'.$distance.'" style="width: 240px;" readonly />
                  </form>
                  <br>
              </tr>';
    }
    $result->free();
}
else{
  echo "You have not accepted appointments";
}
?>
