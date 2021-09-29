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
      <h1 class="my-5" style="color:white"><b>Cancel Appointment</b></h1>
      <p>
        <a href="scheduleAppointment.php" class="btn btn btn-success">Make an Appointment</a>
        <a href="editAppointment.php" class="btn btn-primary">Edit an Appointment</a>
        <a href="patientPreferences.php" class="btn btn btn-success">Update Availability/Preferences</a>
      </p>
</body>
</html>

<?php
$patientId = $_SESSION["patientId"];


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

        $appointment_id = $_POST["appointment_id"];
        $providerName = $_POST["providerName"];
        $start_time = $_POST["start_time"];
        $providerAddress = $_POST["providerAddress"];
        $providerCity = $_POST["providerCity"];
        $providerState = $_POST["providerState"];
        $providerZipCode = $_POST["providerZipCode"];
        $distance = $_POST["distance"];

        echo '<tr>
                  <input type="text" name=appointment_id value="'.$appointment_id.'" style="width: 200px;" readonly />
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


?>

<!DOCTYPE html>
<html>
<body>

<h2 style="color:white">Please Click Confirm Cancellation to Cancel The Above Appointment:</h2>

<form action="cancelSuccess.php" method="post">
  <input type="hidden" name="appointment_id" value=<?php echo $appointment_id ?> style="width: 200px;" readonly />
   <input type="submit" name="appointmentStatus" value="Confirm Cancellation" class= "btn btn btn-secondary btn-lg">
</form>

</body>
</html>
