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
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
      <h1 class="my-5" style="color:white" ><b>Please Select an Appointment</b></h1>
      <p>
        <a href="scheduleAppointment.php" class="btn btn btn-success">Make an Appointment</a>
        <a href="editAppointment.php" class="btn btn-primary">Edit an Appointment</a>
        <a href="patientPreferences.php" class="btn btn btn-success">Update Availability/Preferences</a>
      </p>
</body>
</html>

<?php
//algorithm to maximize appointments
// A PHP program to find maximal
// Bipartite matching.

//first find the number of available appointments:
$connection = new mysqli("localhost", "root", "Lebo53200", "db_project_schema");

if ($connection->connect_error){
  die("Connection failed: ". $connection->connect_error);
}

$patientId = $_SESSION["patientId"];

//Get total number of appointments
$sqlQuery = "SELECT COUNT(*) as total FROM appointments WHERE appointment_available = 1";
$result = mysqli_query($connection,$sqlQuery);
$values = mysqli_fetch_assoc($result);
$numOfAppointments = $values['total'];

//echo "NumOfAppointment: ".$numOfAppointments." ";

//Get total number of patients waiting for appointment
$sqlQuery = "WITH CompletedScheduledOffered AS(
                                          			SELECT pat.priorityGroup, pat.patientId
                                          			FROM offered ofr RIGHT JOIN patient pat ON ofr.patientId = pat.patientId
                                          			WHERE ofr.appointment_status =  'completed' OR
                                          				  ofr.appointment_status = 'accepted' OR
                                          				  ofr.appointment_status = 'offered'
                                                )
              SELECT COUNT(DISTINCT pat2.patientId) as numStillWaiting
              FROM patient pat2
              WHERE pat2.patientId NOT IN (SELECT CompletedScheduledOffered.patientId
              							 FROM CompletedScheduledOffered)";

$result = mysqli_query($connection,$sqlQuery);
$values = mysqli_fetch_assoc($result);
$numWaitingForAppointment = $values['numStillWaiting'];
//echo "NumOfPeopleWaitingForAppt: ".$numWaitingForAppointment." ";

//fill in appointment seekers and available appointmentments in Adjacency Matrix:
if ($connection->connect_error){
  die("Connection failed: ". $connection->connect_error);
}

//get all available appointments:
$sqlQuery = "call getAvailableAppointments()";
$result2 = $connection->query($sqlQuery); //cannot fetch two queries in the for loops same time must store one result
//store data in array
$avlAppointments = array();
while ($rowResult = $result2->fetch_assoc()){
  array_push($avlAppointments, $rowResult["appointment_id"]); // add the row in to the results (data) array
  //echo $rowResult["appointment_id"];
}

//get patients looking for appointment Query returns patients still waiting as well as thier respective availbility preference given and distance preference:
$connection = new mysqli("localhost", "root", "Lebo53200", "db_project_schema");
$sqlQuery = "CALL db_project_schema.patientsThatNeedAppointments()";
$result = $connection->query($sqlQuery);
//$rowResult = $result->fetch_assoc();
//echo $rowResult["appointment_id"];



$array [0][0] = array();

//fill matrix in with values 1 or 0
if ($result->num_rows > 0) {
    $x = 0;
    //patients waiting for appointment array
    while ($rowResult = $result->fetch_assoc()){

        //available appointments array
        for($y = 0; $y < sizeof($avlAppointments); $y++) {
            if (($rowResult["appointment_id"] == $avlAppointments[$y])){ //add also distance constraint
              $array[$x][$y] = 1;
              //echo "appointment_id:".$rowResult["appointment_id"];
              //echo "Matchappointment_id:".$avlAppointments[$y];
            }
            else{
              $array[$x][$y] = 1;
            }
            //echo "PatientID:".$rowResult["patientId"];
            //echo "  ".$x.",".$y."=";
            //echo $array[$x][$y];
        }
        $x++;
    }
    $result->free();
}
else {
  echo "No Records";
}


// M is number of patients looking for appointments in a particular prioirty group
// and N is number of available appointments
$M = $numWaitingForAppointment;// selected preferences???
$N = $numOfAppointments;

// A DFS based recursive function
// that returns true if a matching
// for vertex u is possible
function bpm($bpGraph, $u, &$seen, &$matchR)
{
    global $N;

    // Try every job one by one
    for ($v = 0; $v < $N; $v++)
    {
        // If applicant u is interested in
        // job v and v is not visited
        if ($bpGraph[$u][$v] && !$seen[$v])
        {
            // Mark v as visited
            $seen[$v] = true;

            // If job 'v' is not assigned to an
            // applicant OR previously assigned
            // applicant for job v (which is matchR[v])
            // has an alternate job available.
            // Since v is marked as visited in
            // the above line, matchR[v] in the following
            // recursive call will not get job 'v' again
            if ($matchR[$v] < 0 || bpm($bpGraph, $matchR[$v],
                                    $seen, $matchR))
            {
                $matchR[$v] = $u;
                return true;
            }
        }
    }
    return false;
}

// Returns maximum number
// of matching from M to N
function maxBPM($bpGraph)
{
    global $N,$M;

    // An array to keep track of the
    // applicants assigned to jobs.
    // The value of matchR[i] is the
    // applicant number assigned to job i,
    // the value -1 indicates nobody is
    // assigned.
    $matchR = array_fill(0, $N, -1);

    // Initially all jobs are available

    // Count of jobs assigned to applicants
    $result = 0;
    for ($u = 0; $u < $M; $u++)
    {
        // Mark all jobs as not seen
        // for next applicant.
        $seen=array_fill(0, $N, false);

        // Find if the applicant 'u' can get a job
        if (bpm($bpGraph, $u, $seen, $matchR))
            $result++;
    }
    return $result;
}

// Driver Code

// Let us create a bpGraph
// shown in the above example
/*
$bpGraph = array(array(0, 1, 1, 0, 0, 0),
                    array(1, 0, 0, 1, 0, 0),
                    array(0, 0, 1, 0, 0, 0),
                    array(0, 0, 1, 1, 0, 0),
                    array(0, 0, 0, 0, 0, 0),
                    array(0, 0, 0, 0, 0, 1));
*/
$bpGraph = $array;
//echo "Maximum number of applicants that can get an appointment is ".maxBPM($bpGraph);


// Reference: This code is contributed by chadan_jnu from geeksforgeeks.com
?>




<?php
$connection = new mysqli("localhost", "root", "Lebo53200", "db_project_schema");

if ($connection->connect_error){
  die("Connection failed: ". $connection->connect_error);
}

$patientId = $_SESSION["patientId"];

$sqlQuery = "CALL MatchAvailableAppointments ('$patientId')";

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
                  <form action="patientAcceptAppointment.php" method="post">
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
  echo "No records exist for that search";
}
?>
