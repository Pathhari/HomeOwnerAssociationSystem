<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: log-in.php');
    exit;
}

$host = "localhost";
$db_name = "usersidedb";
$username = "root";
$password = "";
$connection = null;

try {
    $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $connection->prepare("SELECT user_id, first_name, last_name, email, phone FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = $_POST['first_name'] ?? $user['first_name'];
        $last_name = $_POST['last_name'] ?? $user['last_name'];
        $email = $_POST['email'] ?? $user['email'];
        $phone = $_POST['phone'] ?? $user['phone'];
        $password_to_update = $_POST['password'] ?? null;
        $confirm_password = $_POST['confirm_password'] ?? null;

        $params = [
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone' => $phone,
            ':user_id' => $_SESSION['user_id']
        ];

        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone";

        if ($password_to_update && $password_to_update === $confirm_password) {
            $hashed_password = password_hash($password_to_update, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
            $params[':password'] = $hashed_password;
        }

        $sql .= " WHERE user_id = :user_id";
        $update_stmt = $connection->prepare($sql);

        $update_stmt->execute($params);

        $success_msg = "Profile updated successfully.";

        header("Location: profile.php");
        exit;
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HOA Officers</title>
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
  .navbar li {
    padding: 0 15px;
  }
  .navbar a {
    text-decoration: none;
    color: white;
  }
  .officers-container {
    background-color: white;
    padding: 20px;
    margin: 20px auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
  }
  .officer {
    margin: 10px;
    text-align: center;
    flex-basis: calc(33.33% - 20px);
  }
  .officer img {
    border-radius: 50%;
    width: 100px;
    height: 100px;
    object-fit: cover;
  }
  .officer-title {
    margin-top: 10px;
    font-weight: bold;
    color: #333;
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
        <li><a href="user_dashboard.php">HOME</a></li>
        <li><a href="about.php">ABOUT</a></li>
        <li><a href="billing.php">BILLING</a></li>
        <li><a href="help.php">HELP DESK</a></li>
        <li><a href="updates.php">UPDATES</a></li>
    </ul>
</nav>
</header>

<div class="officers-container">
  <div class="officer">
    <img src="imgs/b1.png" alt="President">
    <div class="officer-title">PRESIDENT</div>
    <p>Col. Joel Sulapas</p>
  </div>
  <div class="officer">
    <img src="imgs/g2.png" alt="Vice President">
    <div class="officer-title">VICE PRESIDENT</div>
    <p>Lilidale David</p>
  </div>
  <div class="officer">
    <img src="imgs/g1.png" alt="Secretary">
    <div class="officer-title">SECRETARY</div>
    <p>Leonides Lapatis</p>
  </div>
  <div class="officer">
    <img src="imgs/g3.png" alt="Treasurer">
    <div class="officer-title">TREASURER</div>
    <p>Gemma Apa-ap</p>
  </div>
  <div class="officer">
    <img src="imgs/b2.png" alt="Auditor">
    <div class="officer-title">AUDITOR</div>
    <p>Dale Gahito</p>
  </div>
</div>

</body>
</html>
