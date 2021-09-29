<!DOCTYPE html>
<html lang="en">
<head>
  <style>
body {
  background-image: url('turquoise2.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navigation</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="menu">
        <div class="menu-wrap">
            <img src="logo.png" class="logo-img" alt="Logo">
            <input type="checkbox" id="checkbox">
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="viewProfile.php" class="btn btn-outline-warning">My Profile</a></li>
                    <li><a href="patientUpdateProfile.php" class="btn btn-outline-warning">Update Profile</a></li>
                    <li><a href="logout.php" class="btn btn-outline-danger">Sign Out of Your Account</a></li>
                </ul>
            </nav>
            <label for="checkbox">
                <i class="fa fa-bars menu-icon"></i>
            </label>
        </div>
    </header>

</body>
</html>
