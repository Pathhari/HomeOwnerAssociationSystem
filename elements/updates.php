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
<title>HOA Updates & Reminders</title>
<link rel="stylesheet" href="dashboardstyle.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
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
    .navbar li {
        padding: 0 15px;
    }
    .navbar a {
        text-decoration: none;
        color: white;
    }
    .content-container {
    display: flex;
    align-items: center; /* This will vertically center the items in the container */
    justify-content: start; /* This will align items to the start of the container */
    background-color: white;
    padding: 20px;
    margin: 20px;
    border-left: 10px solid teal;
}
.text-content {
    margin-left: 20px; /* This adds some space between the image and the text */
}

    .content-container h1 {
        color: darkgreen;
    }
    .content-container p {
        color: #333;
    }
    .reminder {
        background-color: tomato;
        color: white;
        padding: 10px;
        margin-top: 20px;
        position: relative;
    }
    .reminder:before {
        content: "";
        position: absolute;
        top: 50%;
        left: -30px;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        background-color: tomato;
        border-radius: 50%;
    }
    .reminder:before {
    /* This pseudo-element may not be needed anymore, or you may need to adjust its styles */
    display: none; /* Temporarily hide it to see the new layout */
}
.reminder-container {
    display: flex;
    align-items: center;
    justify-content: start;
    background-color: tomato; /* Assuming tomato is the background color for the reminder */
    color: white;
    padding: 20px;
    margin: 20px;
    position: relative;
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

<!-- ... Your existing HTML and PHP ... -->

<div class="content-container">
    <div class="image-placeholder">
        <img src="imgs/meeting-image.png" alt="Meeting Image" style="max-width: 200px;"> <!-- Adjust the max-width as needed -->
    </div>
    <div class="text-content">
        <h1>EXCITING NEWS!</h1>
        <p>Participate in community development! The Homeowners Association (HOA) Board Meeting next week is open to all residents. This gathering decides important neighborhood policies, projects, and activities. We value your input and aim to incorporate it into every decision. Your opinion matters, whether you're local or new. Work together to improve our community's quality of life.</p>
    </div>
</div>

<!-- ... The rest of your HTML ... -->
<div class="reminder-container">
    <div class="reminder-image">
        <img src="imgs/reminder-image.png" alt="Reminder Image" style="max-width: 200px;"> <!-- Adjust the max-width as needed -->
    </div>
    <div class="text-content">
    <h1>IMPORTANT REMINDER!</h1>
        <p>Remember to pay HOA dues by the end of the month. Please pay on promptly, homeowners. Our community's services and facilities depend on your prompt donations. Please pay by [due date] to prevent late fines and inconveniences. We appreciate your help maintaining our neighborhood's greatness.</p>
    </div>

    </body>
</html>