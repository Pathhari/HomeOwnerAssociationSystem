<?php
$host = "localhost";
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "usersidedb"; // Replace with your dbname
$charset = 'utf8mb4';


$conn = new mysqli($host, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    // Prepare a delete statement
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    
    // Attempt to execute
    if ($stmt->execute([$userId])) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting user";
    }
}
?>

catch (mysqli_sql_exception $exception) {
      $conn->rollback();
      $_SESSION['message'] = "Exception occurred while deleting user: " . $exception->getMessage();
      $_SESSION['message_type'] = 'error';
  }
  
  // Redirect to the same page to prevent form resubmission
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// At the top of your script (where you handle the $_POST['delete'])
if (isset($_SESSION['message'])) {
  // Save the message and type in a variable and clear it from the session
  $message = $_SESSION['message'];
  $message_type = $_SESSION['message_type'] ?? 'info'; // Default to 'info' if not set
  unset($_SESSION['message'], $_SESSION['message_type']);
}
?>