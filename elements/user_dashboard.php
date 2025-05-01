<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: log-in.php');
    exit;
}
$host = "localhost"; // or your database host
$db_name = "usersidedb";
$username = "root";
$password = "";
try {
    $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

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
<title>HOA Transecta</title>
<link rel="stylesheet" href="dashboardstyle.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

<style>
    header {
    display: flex; 
    justify-content: space-between; 
    align-items: center;
    padding: 10px;
    z-index: 1000; 
    position: relative; 
}

.profile-menu {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
}

.profile-menu .dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.profile-menu:hover .dropdown-content {
    display: block;
}

@media (max-width: 768px) {
    header {
        flex-direction: column;
    }

    .profile-menu {
        position: static;
        transform: none;
        order: -1;
    }
}

.welcome-section {
    position: relative;
    text-align: center;
    padding: 50px 20px;
    background: url('imgs/dsh.png') center/cover no-repeat;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin: 20px;
    height: 300px;
}
.welcome-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80%;
    background-color: rgba(0, 0, 0, 0.6);
    color: #fff;
    padding: 20px;
    border-radius: 15px;
}
.welcome-section h1 {
    font-size: 2em;
    color: #fff;
    margin-bottom: 0.5em;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}
.tagline {
    font-style: italic;
    margin-bottom: 1em;
}

@media (max-width: 768px) {
    .welcome-section {
        padding: 30px 10px;
        height: auto;
    }
    .welcome-text {
        padding: 10px;
        font-size: 1em;
    }
}
.username {
    color: #007bff; 
}
</style>
</head>
<body>
<header>
<div class="profile-menu">
    <button class="profile-button">
        <i class="fas fa-user-circle"></i> 
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
        <li><a href="#">HOME</a></li>
        <li><a href="about.php">ABOUT</a></li>
        <li><a href="billing.php">BILLING</a></li>
        <li><a href="help.php">HELP DESK</a></li>
        <li><a href="updates.php">UPDATES</a></li>
    </ul>
</nav>
</header>
<main>
<section class="welcome-section">
    <div class="welcome-text">
        <h1>Welcome, <span class="username"><?php echo htmlspecialchars($user['first_name']); ?></span>!</h1>
        <p class="tagline">"Enhancing Communities, Empowering Homeowners"</p>
        <p>At HOA Transecta, we believe in the power of strong, vibrant communities. Our mission is to empower homeowners and transform neighborhoods into places where families flourish, connections thrive, and the quality of life soars. As a leading Homeowners' Association (HOA) management company, we bring innovation, transparency, and dedication to every community we serve. Join us on a journey to elevate your neighborhood, simplify management, and foster a true sense of belonging. Explore the possibilities with HOA Transecta today, and let's build a brighter community together.</p>
    </div>
</section>
</main>
</body>
</html>
