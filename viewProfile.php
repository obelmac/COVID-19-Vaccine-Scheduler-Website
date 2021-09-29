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
      <h1 class="my-5" style="color:white"><b>Patient Profile</b></h1>
</body>
</html>

<?php
$patientId = $_SESSION["patientId"];

$connection = new mysqli("localhost", "root", "Lebo53200", "db_project_schema");

if ($connection->connect_error){
  die("Connection failed: ". $connection->connect_error);
}

$sqlQuery = "SELECT * FROM patient WHERE patientId = '$patientId' ";

$result = $connection->query($sqlQuery);

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $patientId = $row["patientId"];
        $patientName = $row["patientName"];
        $patientSurname = $row["patientSurname"];
        $patientAddress = $row["patientAddress"];
        $patientCity = $row["patientCity"];
        $patientState = $row["patientState"];
        $patientZipCode = $row["patientZipCode"];
        $distancePreference = $row["distancePreference"];
        $patientPhone = $row["patientPhone"];
        $priorityGroup = $row["priorityGroup"];
        $patientEmail = $row["patientEmail"];
        $ssn = $row["ssn"];
        $dob = $row["dob"];

        echo '<table border="0" cellspacing="2" cellpadding="2">
              <tr>
                  <form action="patientAcceptAppointment.php" method="post">
                  <input type="text" name=patientId value="Patient Id" style="width: 220px;" readonly />
                  <input type="text" name=patientId value="'.$patientId.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=patientName value="Name" style="width: 220px;" readonly />
                  <input type="text" name=patientName value="'.$patientName.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=patientSurname value="Surname" style="width: 220px;" readonly />
                  <input type="text" name=patientSurname value="'.$patientSurname.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=dob value="Date of Birth" style="width: 220px;" readonly />
                  <input type="text" name=dob value="'.  $dob.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=ssn value="SSN" style="width: 220px;" readonly />
                  <input type="text" name=ssn value="'.$ssn.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=patientAddress value="Patient Address" style="width: 220px;" readonly />
                  <input type="text" name=patientAddress value="'.$patientAddress.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=City value="City" style="width: 220px;" readonly />
                  <input type="text" name=City value="'.$patientCity.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=State value="State" style="width: 220px;" readonly />
                  <input type="text" name=State value="'.$patientState.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=ZipCode value="Zip Code" style="width: 220px;" readonly />
                  <input type="text" name=ZipCode value="'.$patientZipCode.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=phone value="Contact Number" style="width: 220px;" readonly />
                  <input type="text" name=phone value="'.$patientPhone.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=priorityGroup value="Prioirity Group" style="width: 220px;" readonly />
                  <input type="text" name=priorityGroup value="'.$priorityGroup.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=email value="Email" style="width: 220px;" readonly />
                  <input type="text" name=email value="'.$patientEmail.'" style="width: 250px;" readonly /><br>
                  <input type="text" name=distance value="Distance Preference(m)" style="width: 220px;" readonly />
                  <input type="text" name=distance value="'.$distancePreference.'" style="width: 250px;" readonly /><br>
                  </form>
                  <br>
              </tr>';
    }
    $result->free();
}
else{
  echo "Oops try again later";
}
?>
