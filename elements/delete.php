<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: log-in.php');
    exit;
}

// Database configuration
$host = "localhost";
$db_name = "usersidedb";
$username = "root";
$password = "";


try {
    $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if PaymentID is set in the URL
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $paymentID = $_GET['id'];

        // Start transaction
        $connection->beginTransaction();

        // Prepare DELETE statement for gcashmember table
        $stmt = $connection->prepare("DELETE FROM gcashmember WHERE PaymentID = :PaymentID");
        $stmt->bindParam(':PaymentID', $paymentID, PDO::PARAM_INT);
        $stmt->execute();

        // Prepare DELETE statement for gcashdonation table
        $stmt = $connection->prepare("DELETE FROM gcashdonation WHERE PaymentID = :PaymentID");
        $stmt->bindParam(':PaymentID', $paymentID, PDO::PARAM_INT);
        $stmt->execute();

        // Prepare DELETE statement for gcashsecmain table
        $stmt = $connection->prepare("DELETE FROM gcashsecmain WHERE PaymentID = :PaymentID");
        $stmt->bindParam(':PaymentID', $paymentID, PDO::PARAM_INT);
        $stmt->execute();

        // Commit transaction
        $connection->commit();

        // Redirect back to the payment transactions page
        header('Location: payments.php');
        exit();
    }
} catch (PDOException $e) {
    // Rollback transaction if any deletion fails
    $connection->rollBack();
    die("Deletion failed: " . $e->getMessage());
}
// ... [rest of your code below this]

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Deletion - HOA Transecta</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <style>
        .confirm-buttons {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}

.confirm-yes, .confirm-no {
    padding: 10px 20px;
    text-decoration: none;
    color: #fff;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.confirm-yes {
    background-color: #e53935;
}

.confirm-yes:hover {
    background-color: #b71c1c;
}

.confirm-no {
    background-color: #4caf50;
}

.confirm-no:hover {
    background-color: #2e7d32;
}

    </style>
    <!-- Additional CSS can go here -->
</head>
<body>
    <div class="container">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this payment transaction?</p>
        <div class="confirm-buttons">
            <a href="delete.php?id=<?php echo $_GET['id']; ?>" class="confirm-yes">Yes, Delete</a>
            <a href="payments.php" class="confirm-no">No, Cancel</a>
        </div>
    </div>
    <!-- Rest of your dashboard's footer/header -->
</body>
</html>
