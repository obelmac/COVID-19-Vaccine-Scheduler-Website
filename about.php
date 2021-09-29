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
    <h1 class="my-5" style="color:white">INFORMATION COVID VACCINATION SCHEDULING<br></b></h1></br>
    <p>
        <a href="index.php" class="btn btn btn-success">Return Home</a>
    </p>
    <article>
      <p ><font color=white>Important information on COVID-19 Vaccines: </p><br>
      <p >  If you have questions about getting COVID-19 vaccine, you should talk to your healthcare providers for advice. Inform your vaccination provider about all your allergies and health conditions.

        People with HIV and those with weakened immune systems due to other illnesses or medication might be at increased risk for severe COVID-19. They may receive a COVID-19 vaccine. However, they should be aware of the limited safety data:

        Information about the safety of COVID-19 vaccines for people who have weakened immune systems in this group is not yet available
        People living with HIV were included in clinical trials, though safety data specific to this group are not yet available at this time

        People with weakened immune systems should also be aware of the potential for reduced immune responses to the vaccine, as well as the need to continue following current guidance to protect themselves against COVID-19.<br><br>
      If you have questions about getting COVID-19 vaccine, you should talk to your healthcare providers for advice. Inform your vaccination provider about all your allergies and health conditions.
        People who have autoimmune conditions

  People with autoimmune conditions may receive a COVID-19 vaccine. However, they should be aware that no data are currently available on the safety of COVID-19 vaccines for people with autoimmune conditions. People from this group were eligible for enrollment in some of the clinical trials. More information about vaccine clinical trials can be found below.
  After vaccination, follow current guidelines to prevent the spread of COVID-19

  After you are fully vaccinated against COVID-19, you may be able to start doing some things that you had stopped doing because of the pandemic. <br><br> Learn more about what you can do when you have been fully vaccinated.


  You can find additional information on COVID-19 vaccine clinical trials at clinicaltrials.govexternal icon, a database of privately and publicly funded clinical studies conducted around the world.</font></p>
    </article>
  </body>
</html>
