<?php
session_start();
if (!isset($_SESSION['is_super_admin']) || $_SESSION['is_super_admin'] !== true) {
    header('Location: log-in.php');
    exit;
}

$host = 'localhost'; // Host name
$dbname = 'usersidedb'; // Database name
$user = "root"; // Username
$pass = ""; // Password
$charset = 'utf8mb4';


$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

try {
    $sql = "SELECT * FROM gcash_payments WHERE is_deleted = 0";
    $stmt = $pdo->query($sql);

    if (isset($_GET['payment_id'])) {
        $userId = $_GET['payment_id'];
        $stmt = $pdo->prepare("SELECT * FROM gcash_payments WHERE payment_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            echo "No user found with that ID.";
        }
    }
// SQL Query to fetch non-deleted data
$transactionSql = "SELECT payment_id, transaction_id, name, amount, payment_date, payment_type, receipt_file_name FROM gcash_payments WHERE is_deleted = 0 ORDER BY payment_date DESC LIMIT 10";
$transactions = $pdo->query($transactionSql)->fetchAll();


    if (isset($_POST['delete']) && isset($_POST['payment_id'])) {
        deleteUser($_POST['payment_id'], $pdo);
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

function deleteUser($paymentId, $pdo) {
  try {
      $pdo->beginTransaction();
      // Prepare the update statement to set is_deleted to 1 instead of deleting the record
      $stmt = $pdo->prepare("UPDATE gcash_payments SET is_deleted = 1 WHERE payment_id = ?");
      $stmt->execute([$paymentId]);

      if ($stmt->rowCount() > 0) {
          $_SESSION['message'] = "Transaction with Payment ID of $paymentId has been marked as deleted.";
          $_SESSION['message_type'] = 'success';
          $pdo->commit();
      } else {
          $_SESSION['message'] = "No transaction found with Payment ID $paymentId, or it's already marked as deleted.";
          $_SESSION['message_type'] = 'error';
          $pdo->rollBack();
      }
  } catch (PDOException $e) {
      $pdo->rollBack();
      $_SESSION['message'] = "Exception occurred: " . $e->getMessage();
      $_SESSION['message_type'] = 'error';
  }
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'] ?? 'info';
    unset($_SESSION['message'], $_SESSION['message_type']);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Super Admin Dashboard</title>
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
.button {
  padding: 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  transition-duration: 0.4s;
  cursor: pointer;
  border-radius: 5px; /* Rounded corners for aesthetics */
  width: 100px; /* Fixed width for both buttons */
}

/* Styles for delete button */
.button.delete {
  background-color: #f44336; /* Red background for delete */
  color: white;
  border: none;
}

.button.delete:hover {
  background-color: #d32f2f; /* Darker red on hover */
}


.latest-users-card ul {
  /* Existing styles... */
  padding: 0; /* Removes padding inside the list */
}

.latest-users-card li {
  /* Existing styles... */
  list-style-type: none; /* Removes bullet points */
}
.date-filter-container {
  text-align: right; /* Aligns the dropdown to the right */
  margin-bottom: 10px; /* Spacing before the table */
}
#data-table th {
  background-color: #4a5368; /* Softer header background color */
  color: #ffffff; /* White color for header text */
}

#date-filter {
  padding: 5px 10px;
  border: 1px solid #ddd;
  border-radius: 3px;
}
/* Adjust other properties as needed */

/* Add more styles as per your design requirements */


/* Add more styles for your content and adjust the sidebar as needed */

</style>
<!-- Boxicons CDN for icons (you may use your preferred one) -->
<script src='https://code.jquery.com/jquery-3.3.1.slim.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js'></script>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js'></script>
<link href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' rel='stylesheet'>
<link href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css' rel='stylesheet'>
<link href='https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css' rel='stylesheet'>
<script defer  src="https://code.jquery.com/jquery-3.7.0.js"> </script>
<script defer  src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"> </script>
<script defer  src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"> </script>
<script defer  src="script.js"> </script>


</head>
<body>
<link href='https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.9/css/boxicons.min.css' rel='stylesheet'>
<!-- Sidebar -->
<style>#sidebar {
  width: 250px;
  height: 100vh;
  background: #27293D; /* A deep shade for the sidebar */
  color: #fff;
  position: fixed;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
}
.menu-item.logout {
  color: #ff4d4d; /* Example color for log-out button */
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
<aside id="sidebar">
  <div class="sidebar-header">
  <img src="imgs/logo.png" alt="HOA Transecta Logo" class="logo">
  </div>
<!-- ... -->
<ul class="sidebar-menu">
<li class="menu-item" ><a href="superadmin.php"><i class='bx bx-home-alt'></i> Dashboard</a></li>
  <li class="menu-item"><a href="Usersdata.php"><i class='bx bx-face'></i>Users Data</a></li>
  <li class="menu-item"><a href="membersdatas.php"><i class='bx bx-data'></i>Members Data</a></li>
  <li class="menu-item  active"><a href="#"><i class='bx bx-money'></i>Transactions Data</a></li>
  <li class="menu-item logout"><a href="logout.php"><i class='bx bx-log-out'></i> Log Out</a></li>



</ul>
<!-- ... -->

</aside>
<div id="main-content">
<div class="table-container">
  
  <h2>Recent Transactions</h2>
  <table id="transactions-table" class="table table-striped">
  
    <table id="data-table">
      <thead>
          <tr>
          <th>Payment ID</th>
              <th>Transaction ID</th>
              <th>Name</th>
              <th>Amount</th>
              <th>Transaction Date</th>
              <th>Payment Type</th>
              <th>File Receipt</th>
              <th>Actions</th> 
          </tr>
      </thead>
      <tbody>
          <?php foreach ($transactions as $transaction): ?>
              <tr>
              <td><?= htmlspecialchars($transaction['payment_id']) ?></td>
                  <td><?= htmlspecialchars($transaction['transaction_id']) ?></td>
                  <td><?= htmlspecialchars($transaction['name']) ?></td>
                  <td>₱<?= htmlspecialchars($transaction['amount']) ?></td>
                  <td><?= htmlspecialchars($transaction['payment_date']) ?></td>
                  <td><?= htmlspecialchars($transaction['payment_type']) ?></td>
                  <td> 
                <?php if (!empty($transaction['receipt_file_name'])): ?>
                    <a href="uploads/<?= htmlspecialchars($transaction['receipt_file_name']) ?>" target="_blank" class="button">View Receipt</a>
                <?php endif; ?>
                </td>
                  <td>

        <form method="post" action="">
        <input type="hidden" name="payment_id" value="<?= $transaction['payment_id']; ?>">
        <input class="button delete" type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this membership?');">

        </form>
            </td>
              </tr>
          
  <?php endforeach; ?>
      </tbody>
  </table>
  
  <?php if (isset($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message_type) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($message) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

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
