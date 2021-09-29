<?php
// Initialize the session
require_once "config.php";
include('landing.php');
session_start();

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
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 18px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5" style="color:white"><b>Please Select Your Availability</b></h1>
    <p>
      <a href="scheduleAppointment.php" class="btn btn btn-success">Make an Appointment</a>
      <a href="editAppointment.php" class="btn btn-primary">Edit an Appointment</a>
      <a href="patientPreferences.php" class="btn btn btn-success">Update Availability/Preferences</a>
    </p>
  </body>
</html>
<?php

$connection = new mysqli("localhost", "root", "Lebo53200", "db_project_schema");

if ($connection->connect_error){
  die("Connection failed: ". $connection->connect_error);
}

//check if add or remove slots to patient preferences an update
$patientId = $_SESSION["patientId"];
$slot_id = "";

if(isset($_POST["addSlotId"])){
  $slot_id = $_POST["addSlotId"];
  //$slot_id = $_POST["addSlotId"];
  $sql = "INSERT INTO available (patientId, slot_id) VALUES (?, ?)";

  if ($stmt = mysqli_prepare($link, $sql)) {

    mysqli_stmt_bind_param($stmt,"ii", $param_patientId , $param_slot_id);
    $param_patientId = $patientId;
    $param_slot_id = $slot_id;
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
    } else {

    }
    // Close statement
    mysqli_stmt_close($stmt);
    mysqli_close($link);

  }
}

$slot_id = "";

if(isset($_POST["removeSlotId"])){
  $slot_id = $_POST["removeSlotId"];
  //$slot_id = $_POST["addSlotId"];
  $sql = "DELETE FROM available WHERE patientId = ? AND slot_id = ?";

  if ($stmt = mysqli_prepare($link, $sql)) {

    mysqli_stmt_bind_param($stmt,"ii", $param_patientId, $param_slot_id);
    $param_patientId = $patientId;
    $param_slot_id = $slot_id;
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
    } else {

    }
    // Close statement
    mysqli_stmt_close($stmt);
    mysqli_close($link);

  }
}

$sqlQuery = "SELECT * FROM db_project_schema.calendar_slots";

$result = $connection->query($sqlQuery);

if ($result->num_rows > 0) {
  echo "<p> <font color=white>SELECTABLE TIME PREFERENCES:</font> </p>";
  echo  '<table border="0" cellspacing="2" cellpadding="2">
        <tr>
            <form>
            <input type="text" name=day value="day" style="width: 200px;" readonly />
            <input type="text" name=start_time value="start time" style="width: 200px;" readonly />
            <input type="text" name=end_time value="end time" style="width: 200px;" readonly />
            <input type="text" name=addSlotTitle value="Add" style="width: 200px;" readonly />
            </form>
            <br>
        </tr>';

    while ($row = $result->fetch_assoc()) {
        $slot_id = $row["slot_id"];
        $day_text = $row["day_text"];
        $start_time = $row["start_time"];
        $end_time = $row["end_time"];

        echo '<tr>
                  <form action="patientPreferences.php" method="post">

                  <input type="text" name=day value="'.$day_text.'" style="width: 200px;" readonly />
                  <input type="text" name="start_time" value="'.$start_time.'" style="width: 200px;" readonly >
                  <input type="text" name="end_time" value="'.$end_time.'" style="width: 200px;" readonly >
                  <input type="submit" name="addSlotId" value="'.$slot_id.'" style="width: 200px;" readonly >
                  </form>
                  <br>
              </tr>';
    }
    $result->free();
}
else{
  echo "No records exist for that search";
}

//Form for Preferences Indicated
  echo "\r\n";
  echo "\r\n";
  echo "<p> <font color=white>PATIENT PREFERENCES:</font> </p>";


        $sqlQuery = "SELECT * FROM patient p JOIN available a ON p.patientId = a.patientId
                    JOIN calendar_slots cs ON cs.slot_id = a.slot_id
                    WHERE p.patientId = $patientId ";

        $result = $connection->query($sqlQuery);


        if ($result->num_rows > 0) {
          echo  '<table border="0" cellspacing="2" cellpadding="2">
                <tr>
                    <form>
                    <input type="text" name=day value="day" style="width: 200px;" readonly />
                    <input type="text" name=start_time value="start time" style="width: 200px;" readonly />
                    <input type="text" name=end_time value="end time" style="width: 200px;" readonly />
                    <input type="text" name=removeSlotTitle value="remove" style="width: 200px;" readonly />
                    </form>
                    <br>
                </tr>';

            while ($row = $result->fetch_assoc()) {
                $slot_id = $row["slot_id"];
                $day_text = $row["day_text"];
                $start_time = $row["start_time"];
                $end_time = $row["end_time"];

                echo '<tr>
                          <form action="patientPreferences.php" method="post">
                          <input type="text" name=day value="'.$day_text.'" style="width: 200px;" readonly />
                          <input type="text" name="start_time" value="'.$start_time.'" style="width: 200px;" readonly >
                          <input type="text" name="end_time" value="'.$end_time.'" style="width: 200px;" readonly >
                          <input type="submit" name=removeSlotId value="'.$slot_id.'" style="width: 200px;" readonly />
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
