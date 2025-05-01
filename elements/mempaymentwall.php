<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: log-in.php');
    exit;
}

// Database configuration
$host = "localhost";
$db_name = "usersidedb";
$username = "root"; // Replace with your actual database username
$password = ""; // Replace with your actual database password
$connection = null;


try {
    $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
// Fetch user information
$user_id = $_SESSION['user_id'];
$stmt = $connection->prepare("SELECT first_name, last_name, email FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
// Initialize error message variable
$errorMessage =  '';

// Check if form data is received via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $transactionId = $_POST['transaction_id'];

    // Handle receipt file upload
    if (isset($_FILES['receipt'])) {
        if ($_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
            $receiptName = $_FILES['receipt']['name'];
            $receiptTempName = $_FILES['receipt']['tmp_name'];
            $receiptSize = $_FILES['receipt']['size'];
            $receiptType = $_FILES['receipt']['type'];

            // Define allowed file types and size limit (example: 5MB)
            $allowedTypes = array('image/png', 'image/jpeg', 'image/webp', 'image/tiff');
            $sizeLimit = 2 * 1024 * 1024;  // 2MB
            
            // Validate file type and size
            if (in_array($receiptType, $allowedTypes) && $receiptSize <= $sizeLimit) {
                // Define the target directory for receipt uploads
                $targetDirectory = "uploads/";
                $targetFile = $targetDirectory . basename($receiptName);

                // Check if uploads directory exists, if not create it
                if (!file_exists($targetDirectory)) {
                    mkdir($targetDirectory, 0755, true);
                }

                // Move file to target directory
                if (move_uploaded_file($receiptTempName, $targetFile)) {
                    // Correct the INSERT statement
                    $insertStmt = $connection->prepare("
                        INSERT INTO gcash_payments (user_id, name, amount, transaction_id, receipt_file_name, payment_date, payment_type)
                        VALUES (:user_id, :name, :amount, :transaction_id, :receipt_file_name, NOW(), :payment_type)
                    ");
                    $insertStmt->bindParam(':user_id', $user_id);
                    $insertStmt->bindParam(':name', $name);
                    $insertStmt->bindParam(':amount', $amount);
                    $insertStmt->bindParam(':transaction_id', $transactionId);
                    $insertStmt->bindParam(':receipt_file_name', $receiptName);
                    
                    $payment_type = 'Membership'; // This should be assigned based on your form or logic

                    $insertStmt->bindParam(':payment_type', $payment_type);
                    
                    if ($insertStmt->execute()) {
                        header('Location: success.php'); // Redirect to a success page if the payment is successful
                        exit;
                    } else {
                        $errorMessage = 'There was a problem submitting the payment.';
                    }
                } else {
                    $errorMessage = 'Error uploading the receipt.';
                }
            }else {
                    $errorMessage = 'Invalid file type. Only PNG, JPEG, WebP, and TIF files are allowed.';
            } 
        } else {
            $errorMessage = 'Error in file upload.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gcash Payment</title>
    <link rel="stylesheet" href="dashboardstyle.css"> <!-- Link to your CSS file -->
    <style>/* General body styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-image: url(imgs/bg.png);
    color: #333;
    margin: 0;
    padding: 20px;
}

/* Container for the payment form */
.payment-container {
    background: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    max-width: 400px;
    margin: auto;
    text-align: center;
}

/* Styles for the form elements */
.payment-container h2 {
    font-size: 24px;
    color: #4CAF50;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
    text-align: left;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
}

.form-group input[type="name"],
.form-group input[type="number"],
.form-group input[type="tel"],
.form-group input[type="text"],
.form-group input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box; 
}
.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box; 
    margin-bottom: 10px; 
}
.error-message {
    color: red;
    font-size: 0.9em;
    margin-top: 10px; 
}
button {
    width: 100%;
    padding: 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #369b47;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .payment-container {
        width: auto;
        padding: 20px;
    }
}

</style>
</head>
<body>
    <div class="payment-container">
        <h2>Gcash Payment</h2>
        <form id="gcashForm" method="post" enctype="multipart/form-data">
        <div class="form-group">
    <label for="Name">G-cash Account Name:</label>
    <input type="text" id="Name" name="name" required> 
</div>

            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" required>
            </div>
            <div class="form-group">
                <label for="transaction_id">Transaction ID:</label>
                <input type="text" id="transaction_id" name="transaction_id" required>
            </div>
            <div class="form-group">
            <div class="form-group">
   
<div class="form-group">
    <label for="qr_code_display">QR Code:</label>
            <img src="imgs/qrcode.png" alt="QR Code" style="max-width:100%; height:auto;">
        </div>
</div>

<div class="form-group">
    <label for="receipt">Upload Receipt:</label>
    <input type="file" id="receipt" name="receipt" required>
    <?php if (!empty($errorMessage)): ?>
    <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
<?php endif; ?>
</div>
            <button type="submit">Pay with Gcash</button>
            <button type="button" onclick="closeForm()">Close</button>
        </form>
    </div>
    <script>
function closeForm() {
    window.location.href = 'billing.php'; 
}

function closeForm() {
    window.history.back();
}

</script>

</body>
</html>
