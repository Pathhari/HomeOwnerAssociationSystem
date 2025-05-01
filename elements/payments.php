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

$user_id = $_SESSION['user_id'];
// Connect to the database
try {
    $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$stmt = $connection->prepare("SELECT payment_id, transaction_id, amount, payment_type, payment_date, receipt_file_name FROM gcash_payments WHERE user_id = :user_id ORDER BY payment_date DESC");
$stmt->bindParam(':user_id', $user_id);
$executeSuccess = $stmt->execute();

if ($executeSuccess) {
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($payments)) {
        
    }
} else {
    // handle the case where the statement failed to execute
    echo "Failed to execute query.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HOA Transecta - Payment Transactions</title>
<link rel="stylesheet" href="dashboardstyle.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f2f1;
        }
        .container {
            width: 80%;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: gray;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .notification {
    margin-top: 20px;
    padding: 15px;
    background-color: #ffcccb; /* Light red background */
    text-align: center;
    border-left: 5px solid #f44336; /* Red accent border */
}

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

    </style>

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
<div class="container notification">
    <p>Reminder: Please pay your monthly dues.</p>
</div>

<!-- Monthly due breakdown -->
<div class="container breakdown">
    <h3>Monthly Due Breakdown</h3>
    <table>
        <tr>
            <td>Description</td>
            <td>Amount</td>
        </tr>
        <tr>
            <td>Maintenance</td>
            <td>₱70.00</td>
        </tr>
        <tr>
            <td>Security</td>
            <td>₱55.00</td>
        </tr>
        <tr>
            <th>Total Due</th>
            <th>₱125.00</th>
        </tr>
    </table>
</div>
        </table>
    </div>
    
<div class="container">
    <h2>Payment Transactions</h2>
    <table>
        <tr>
            <th>Payment ID</th>
            <th>Transaction ID</th>
            <th>Amount</th>
            <th>Payment Type</th>
            <th>Payment Date</th>
            <th>Receipt</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($payments as $payment): ?>
        <tr id="payment-row-<?php echo $payment['payment_id']; ?>">
            <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
            <td><?php echo htmlspecialchars($payment['transaction_id']); ?></td>
            <td><?php echo htmlspecialchars($payment['amount']); ?></td>
            <td><?php echo htmlspecialchars($payment['payment_type']); ?></td>
            <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
            <td>
                <?php if ($payment['receipt_file_name']): ?>
                    <a href="uploads/<?php echo htmlspecialchars($payment['receipt_file_name']); ?>" target="_blank">View Receipt</a>
                <?php else: ?>
                    No Receipt
                <?php endif; ?>
            </td>
            <!-- Update the Delete button with an onclick event to call hideRow() -->
            <td><button onclick="hideRow(<?php echo $payment['payment_id']; ?>)">Delete</button></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>


</body>
<script>
function hideRow(paymentId) {
    var row = document.getElementById('payment-row-' + paymentId);
    row.style.display = 'none'; // This will hide the row
}
</script>
</head>
</html>
