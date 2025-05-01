
<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: log-in.php');
    exit;
}

// Database configuration
$host = "localhost"; 
$db_name = "usersidedb"; 
$username = "root";
$password = ""; 

// Create a new PDO instance to connect to the database
try {
    $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$successMessage = '';
$errorMessage = '';

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
    
.breakdown {
    margin-top: 20px;
    padding: 15px;
    background-color: #e7f3fe; /* Light blue background */
    border-left: 5px solid #2196F3; /* Blue accent border */
}

.breakdown table {
    width: 100%;
    margin-top: 10px;
}

.breakdown th,
.breakdown td {
    text-align: left;
    padding: 8px;
}

.breakdown th {
    background-color: red; /* Lighter blue background for header */
}

.breakdown tr {
    background-color: white;
}
.notification {
    margin-top: 20px;
    padding: 15px;
    background-color: #ffcccb; /* Light red background */
    text-align: center;
    border-left: 5px solid #f44336; /* Red accent border */
}
</style>
</head>
<body>
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
    <main>
        <section class="services-section">
            <h2>BILLINGS AND SERVICES:</h2>
            <div class="services-container">
            <button class="service-button" onclick="location.href='membership.php'">MEMBERSHIP</button>
            <button class="service-button" onclick="location.href='secmain.php'">SECURITY and MAINTENANCE</button>
            <button class="service-button" onclick="location.href='donation.php'">DONATIONS</button>
        </div>
        </section>
        <div class="container notification">
    <p><strong>Reminder: Read here before paying.</strong></p>
</div>

<!-- Monthly due breakdown -->
<div class="container breakdown">
    <h3>Billing and Services Cost</h3>
    <table>
        <tr>
            <td><strong>Description</strong></td>
            <td><strong>Amount</strong></td>
        </tr>
        <tr>
            <td>Membership</td>
            <td>₱125.00</td>
        </tr>
        <tr>
            <td>Security and Maintenance</td>
            <td>₱125.00</td>
        </tr>
        <tr>
            <td>Donation</td>
            <td>₱ ---------</td>
        </tr>
    </table>
</div>
    </main>
    </section>
</main>

</body>

</html>
