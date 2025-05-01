<?php
session_start(); // Start the session at the very beginning
if (!isset($_SESSION['admin_id'])) {
  header('Location: log-in.php');
  exit;
}
// Database connection parameters
$host = 'localhost'; // Host name
$dbname = 'usersidedb'; // Database name
$user = "root"; // Username
$pass = ""; // Password
$charset = 'utf8mb4';

// Set up PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// SQL Query to fetch membership data
$sql = "SELECT first_name, last_name, middle_name, CONCAT('Block',' ',block,' ','Lot', ' ', lot,' ','Street', ' ', street,' ', 'Phase',' ', phase) AS address, email_address, cellphone, occupant_name, created_at FROM membership";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch data
$users = $stmt->fetchAll();


// For date filtering (optional)
$dateQuery = "SELECT DISTINCT DATE_FORMAT(created_at, '%Y-%m-%d') AS reg_date FROM  membership ORDER BY created_at DESC";
$dateStmt = $pdo->prepare($dateQuery);
$dateStmt->execute();
$dates = $dateStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="dashboardstyles.css">
<!-- Add this inside your <head> tag -->
<link href='https://fonts.googleapis.com/css?family=Nunito:400,700&display=swap' rel='stylesheet'>

<style>
/* styles.css */
body, html {
  margin: 0;
  padding: 0;
  font-family: 'Nunito', sans-serif; /* A more modern font */
  background: #f4f7fa;
  color: #333;
}


.welcome-content h2 {
  color: #333;
  margin-bottom: 10px;
}

.welcome-content p {
  color: #555;
  margin-bottom: 20px;
}

.overview-cards {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

/* Example styles for overview cards */
.overview-card {
  background: #FFFFFF;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  padding: 20px;
  width: calc(33% - 10px);
  margin-right: 15px;
  text-align: center;
}

.overview-card:last-child {
  margin-right: 0;
}

.overview-card h3 {
  margin-top: 0;
  color: #27293D;
}

.overview-card p {
  color: #555;
}

.menu-item.logout {
  color: #ff4d4d; /* Example color for log-out button */
}
.table-container {
  margin-top: 20px; /* Adjust as needed */
  background: #fff; /* Light background for the table */
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

#data-table {
  width: 100%;
  border-collapse: collapse;
}

#data-table th, #data-table td {
  text-align: left;
  padding: 8px;
  border-bottom: 1px solid #ddd; /* Light border for each cell */
}

#data-table th {
  background-color: #007bff; /* Header background */
  color: white;
}

/* Zebra striping for rows */
#data-table tr:nth-child(odd) {
  background-color: #f2f2f2;
}



#data-table th {
  background-color: #4a5368; /* Softer header background color */
  color: #ffffff; /* White color for header text */
}


/* Adjust other properties as needed */

/* Add more styles as per your design requirements */


/* Add more styles for your content and adjust the sidebar as needed */

</style>
<!-- Boxicons CDN for icons (you may use your preferred one) -->
<link href='https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<link href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css' rel='stylesheet'>
<link href='https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css' rel='stylesheet'>
<script defer  src="https://code.jquery.com/jquery-3.7.0.js"> </script>
<script defer  src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"> </script>
<script defer  src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"> </script>
<script defer  src="script.js"> </script>

<!-- Sidebar -->
<aside id="sidebar">
  <style>#sidebar {
  width: 250px;
  height: 100vh;
  background: #27293D; /* A deep shade for the sidebar */
  color: #fff;
  position: fixed;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
}

.sidebar-header {
  padding: 20px;
  background-image: url(imgs/bg.png);
  text-align: center;
  font-size: 1.4em;
}

.sidebar-menu {
  list-style: none;
  padding: 0;
  margin-top: 20px;
}

.menu-item {
  padding: 20px;
  border-bottom: 1px solid #40445c; /* Slight contrast for separation */
  transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for hover effect */
  cursor: pointer; /* Change mouse cursor on hover */
}

.menu-item:hover, .menu-item.active {
  background-color: #1A1C27; /* Darken item on hover/active */
  color: #4cd137; /* Change text color for contrast */
}

.menu-item i {
  margin-right: 10px;
  transition: transform 0.3s ease; /* Smooth icon transition */
}

.menu-item:hover i {
  transform: scale(1.1); /* Slightly enlarge icons on hover */
}

#main-content {
  margin-left: 250px;
  padding: 20px;
  background: #fff; /* Light background for the content */
  min-height: 100vh;
  box-shadow: -1px 0 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for content box */
}

/* Additional global styles */
a {
  text-decoration: none;
  color: inherit; /* Links inherit the color from its parent */
}

a:hover {
  color: #4cd137; /* Change link color on hover */
}
/* Add to your existing <style> or external CSS file */
</style>
  <div class="sidebar-header">
  <img src="imgs/logo.png" alt="HOA Transecta Logo" class="logo">
  </div>
<!-- ... -->
<ul class="sidebar-menu">
  <li class="menu-item"><a href="Officer_Dashboard.php"><i class='bx bx-home-alt'></i> Dashboard</a></li>
  <li class="menu-item"><a href="history.php"><i class='bx bx-history'></i> History</a></li>
  <li class="menu-item active"><a href="#"><i class='bx bx-data'></i>Members Data</a></li>
  <li class="menu-item"><a href="costcomp.php"><i class='bx bx-calculator'></i> Cost Computation</a></li>
  <li class="menu-item logout"><a href="logout.php"><i class='bx bx-log-out'></i> Log Out</a></li>


</ul>
<!-- ... -->

</aside>

<!-- Main Content -->
<div id="main-content">
  
     
  <div class="table-container">
    <h2>Registered Membership</h2>
  
    <!-- Data Table -->
    <table id="data-table">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Middle Name</th>
                <th>Address</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Occupant Name</th>
                <th>Membership Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                <td><?= htmlspecialchars($user['first_name']) ?></td>
                            <td><?= htmlspecialchars($user['last_name']) ?></td>
                            <td><?= htmlspecialchars($user['middle_name']) ?></td>
                            <td><?= htmlspecialchars($user['address']) ?></td>
                            <td><?= htmlspecialchars($user['email_address']) ?></td>
                            <td>+63<?= htmlspecialchars($user['cellphone']) ?></td>
                            <td><?= htmlspecialchars($user['occupant_name']) ?></td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
      </tbody>
    </table>  
   


  </div>
</div>


<script src="script.js">
  
    // script.js
// Wait for the DOM to fully load
document.addEventListener('DOMContentLoaded', (event) => {
  // Get all menu items
  const menuItems = document.querySelectorAll('.menu-item');
  
  // Add click event to each menu item
  menuItems.forEach(item => {
    item.addEventListener('click', function() {
      // Remove 'active' class from all menu items
      menuItems.forEach(subItem => {
        subItem.classList.remove('active');
      });
      // Add 'active' class to the clicked menu item
      this.classList.add('active');
    });
  });
});
</script>

</body>
</html>
