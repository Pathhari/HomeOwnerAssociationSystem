<?php
session_start(); // Start the session at the very beginning

// Your database credentials should be secured, for example using environment variables or a configuration file
$host = 'localhost'; // or your database host
$db   = 'usersidedb';
$user = "root";
$pass = "";
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// SQL Query to fetch data
$sql = "SELECT first_name, last_name, email, phone, registration_date FROM users";
$stmt = $pdo->query($sql);

$dateQuery = "SELECT DISTINCT DATE_FORMAT(registration_date, '%Y-%m-%d') AS reg_date FROM users ORDER BY registration_date DESC";
$dateStmt = $pdo->query($dateQuery);
$dates = $dateStmt->fetchAll(PDO::FETCH_COLUMN);
$transactionSql = "SELECT transaction_id, name, amount, payment_date, payment_type FROM gcash_payments ORDER BY payment_date DESC LIMIT 10";
$transactionStmt = $pdo->query($transactionSql);
$transactions = $transactionStmt->fetchAll();
// Fetch data
$users = $stmt->fetchAll();
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<link rel="stylesheet" href="dashboardstyles.css">
<!-- Add this inside your <head> tag -->

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
  background: #fff;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}
.table-container th {

  background-color: #4a5368; /* Softer header background color */
  color: #ffffff; /* White color for header text */
}


#data-table th {
  background-color: #4a5368;
  color: #ffffff;
}

table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  margin-top: 1em;
}

th, td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

tr:hover {
  background-color: #f5f5f5;
}




/* Adjust other properties as needed */

/* Add more styles as per your design requirements */


/* Add more styles for your content and adjust the sidebar as needed */

</style>
<!-- Boxicons CDN for icons (you may use your preferred one) -->
<link href='https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css' rel='stylesheet'>
<link href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css' rel='stylesheet'>
<link href='https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css' rel='stylesheet'>
<script defer  src="https://code.jquery.com/jquery-3.7.0.js"> </script>
<script defer  src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"> </script>
<script defer  src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"> </script>
<script defer  src="scripts.js"> </script>

</head>
<body>
<link href='https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.9/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<style>
#sidebar {
  width: 250px;
  height: 100vh;
  background: #27293D; /* A deep shade for the sidebar */
  color: #fff;
  position: fixed;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
}

#data-table th {
  background-color: #4a5368; /* Softer header background color */
  color: #ffffff; /* White color for header text */
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
div.dataTables_wrapper {
        margin-bottom: 3em;
    }
a:hover {
  color: #4cd137; /* Change link color on hover */
}
/* Add to your existing <style> or external CSS file */
</style>
<!-- Sidebar -->
<aside id="sidebar">
  <div class="sidebar-header">
  <img src="imgs/logo.png" alt="HOA Transecta Logo" class="logo">
  </div>
<!-- ... -->
<ul class="sidebar-menu">
  <li class="menu-item"><a href="Officer_Dashboard.php"><i class='bx bx-home-alt'></i> Dashboard</a></li>
  <li class="menu-item active"><a href="#"><i class='bx bx-history'></i> History</a></li>
  <li class="menu-item"><a href="membersdata.php"><i class='bx bx-data'></i>Members Data</a></li>
  <li class="menu-item"><a href="costcomp.php"><i class='bx bx-calculator'></i> Cost Computation</a></li>
  <li class="menu-item logout"><a href="logout.php"><i class='bx bx-log-out'></i> Log Out</a></li>


</ul>
<!-- ... -->

</aside>

</body>
<!-- Main Content -->
<div id="main-content">
  
  <div class="table-container">
    <h2>Registered Users</h2>
    <!-- Data Table -->
    <table id="" class="display" style="width:100%">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registration Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['first_name']) ?></td>
                    <td><?= htmlspecialchars($user['last_name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>+63<?= htmlspecialchars($user['phone']) ?></td>
                    <td><?= htmlspecialchars($user['registration_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
      </tbody>
    </table>  
  </div>


<div class="table-container">
  
    <h2>Recent Transactions</h2>
    <table id="transactions-table" class="table table-striped">
      <table id="" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Transaction Date</th>
                <th>Payment Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['transaction_id']) ?></td>
                    <td><?= htmlspecialchars($transaction['name']) ?></td>
                    <td>₱<?= htmlspecialchars($transaction['amount']) ?></td>
                    <td><?= htmlspecialchars($transaction['payment_date']) ?></td>
                    <td><?= htmlspecialchars($transaction['payment_type']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>


<script>
  
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

  // Dropdown filter event listener
  document.getElementById('date-filter').addEventListener('change', function() {
    var selectedDate = this.value;
    var tableRows = document.querySelectorAll('#data-table tbody tr');
    
    tableRows.forEach(row => {
      var dateCellText = row.cells[4].textContent; // Adjust the index if needed
      var rowDate = dateCellText.split(' ')[0]; // Assuming the date is in 'YYYY-MM-DD' format
      if(selectedDate === "" || rowDate === selectedDate) {
        row.style.display = ''; // Show row
      } else {
        row.style.display = 'none'; // Hide row
      }
    });
  });
});


// Initial population of the table
populateTable(tableData);
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
