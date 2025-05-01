<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: log-in.php');
    exit;
}

// Database configuration
$host = "localhost"; // or your database host
$db_name = "usersidedb";
$username = "root";
$password = "";


// Connect to the database
try {
    $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch user information
$user_id = $_SESSION['user_id'];
$stmt = $connection->prepare("SELECT first_name, last_name, email FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us</title>
<link rel="stylesheet" href="dashboardstyle.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #eef2f3;
    }
    .navbar {
        background-color: #007b5e;
        color: white;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .navbar ul {
        list-style: none;
        display: flex;
        margin: 0;
        padding: 0;
    }
    .navbar ul li {
        padding: 0 15px;
    }
    .navbar a {
        text-decoration: none;
        color: white;
    }
    .contact-container {
        background-color: white;
        width: 80%;
        margin: 20px auto;
        padding: 20px;
        box-shadow: 0px 0px 10px 0px #00000033;
    }
    .contact-container h2 {
        text-align: center;
        color: #007b5e;
    }
    .contact-info {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 20px;
    }
    .contact-info div {
        text-align: center;
    }
    .contact-info img {
        max-width: 100px;
        margin: 10px auto;
    }
</style>
<header>
<div class="profile-menu">
    <button class="profile-button">
        <i class="fas fa-user-circle"></i> <!-- Font Awesome User Icon -->
    </button>
    <div class="dropdown-content">
        <a href="profile.php">Profile</a>
        <a href="payments.php">Payments</a>
        <a href="logout.php">Log-Out</a>
    </div>
</div>


    <img src="imgs/logo.png" alt="HOA Transecta Logo" class="logo">
    <nav>
        <ul>
            <li><a href="user_dashboard.php">HOME</a></li>
            <li><a href="about.php">ABOUT</a></li>
            <li><a href="billing.php">BILLING</a></li>
            <li><a href="help.php">HELP DESK</a></li>
            <li><a href="updates.php">UPDATES</a></li>
        </ul>
    </nav>
</header>
<div class="contact-container">
        <h2>CONTACT US!</h2>
        <div class="contact-info">
            <div>
                <img src="imgs/phone-icon.png" alt="Phone">
                <p>09066362301</p>
                <p>(082) 287-8223</p>
            </div>
            <div>
                <img src="imgs/email-icon.png" alt="Email">
                <p>HOATransecta@gmail.com</p>
                <p>Transecta.HOA@gmail.com</p>
            </div>
            <div>
                <img src="imgs/facebook-icon.png" alt="Facebook">
                <p>@HOATransectaOfficial</p>
                <p>@TransectaHOAHelp</p>
            </div>
        </div>
    </div>
    </body>
</html>