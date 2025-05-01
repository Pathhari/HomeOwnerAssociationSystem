<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database configuration
$host = "localhost"; // Your database host
$db_name = "usersidedb"; // Your database name
$username = "root"; // Your database username
$password = ""; // Your database password

// Create a new PDO instance to connect to the database
try {
    $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $memberName = $_POST['member_name'] ?? '';
    $memberEmail = $_POST['member_email'] ?? '';
    $memberAddress = $_POST['member_address'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Prepare the SQL statement
    $stmt = $connection->prepare("INSERT INTO memberships (full_name, email, address, phone_number) VALUES (:full_name, :email, :address, :phone_number)");

    // Bind parameters to the prepared statement
    $stmt->bindParam(':full_name', $memberName);
    $stmt->bindParam(':email', $memberEmail);
    $stmt->bindParam(':address', $memberAddress);
    $stmt->bindParam(':phone_number', $phone);

    // Execute the statement and check if it was successful
    if ($stmt->execute()) {
        // Output a success message (or you could redirect to another page, etc.)
        echo "Membership submitted successfully!!";
    } else {
        // Output an error message
        echo "There was a problem submitting the membership.";
    }
}
?>
