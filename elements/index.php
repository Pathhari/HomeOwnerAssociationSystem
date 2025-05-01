<?php
// Database connection parameters
$host = 'localhost';
$dbUsername = "root";
$dbPassword = "";
$dbName = 'usersidedb';
// Create connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
if (isset($_GET['user_id'])) {
  $userId = $_GET['user_id'];

  // Fetch user data
  $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Now you can use $user to fill out the edit form
  } else {
    echo "No user found with that ID.";
  }
  $stmt->close();
}
// Check for query error
if (!$result) {
  die("Error: " . $conn->error);
}
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to delete a user
// Function to delete a user


// Function to edit a user
function editUser($userId, $firstName, $lastName, $email, $phone) {
  global $conn;
  $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE user_id = ?");
  $stmt->bind_param("ssssi", $firstName, $lastName, $email, $phone, $userId);
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
    echo "User updated successfully.";
  } else {
    echo "Error updating user.";
  }
  $stmt->close();
}

// Example usage of deleteUser function
if (isset($_POST['delete'])) {
  $userId = $_POST['user_id'];
  deleteUser($userId);
}
// Example usage of editUser function
if (isset($_POST['edit'])) {
  // Instead of directly editing, you would typically display a form with the user's current data
  // For simplicity, let's assume you redirect to an edit page with the user's ID as a query string
  $userId = $_POST['user_id'];
  header("Location: edit_user.php?user_id=" . $userId);
  exit();
}
function deleteUser($userId) {
  global $conn;
  
  // Begin transaction
  $conn->begin_transaction();

  try {
    // Attempt to delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
      echo "User deleted successfully.";
      // Commit the transaction
      $conn->commit();
    } else {
      // Before rolling back, check if there was an SQL error
      if ($conn->errno) {
        echo "SQL Error: " . $conn->error;
      } else {
        echo "Error deleting user: No user found with that ID.";
      }
      // Rollback the transaction
      $conn->rollback();
    }
    
    $stmt->close();
  } catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    error_log("Error: " . $exception->getMessage()); // Log the exception message
    echo "Exception occurred while deleting user.";
  }
}
// Rest of your PHP code to retrieve users and display them in HTML
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<style>
  body { font-family: Arial, sans-serif; }
  .container { width: 90%; margin: auto; }
  table { width: 100%; border-collapse: collapse; margin-top: 20px; }
  th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
  th { background-color: #f2f2f2; }
  .button { padding: 5px 10px; color: white; border: none; cursor: pointer; }
  .edit { background-color: blue; }
  .delete { background-color: red; }
</style>
</head>
<body>
<div class="container">
  <h1>Registered Users</h1>
  <table>
    <!-- ... [table headers] -->
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['user_id']; ?></td>
      <td><?php echo $row['first_name']; ?></td>
      <td><?php echo $row['last_name']; ?></td>
      <td><?php echo $row['email']; ?></td>
      <td><?php echo $row['phone']; ?></td>
      <td><?php echo $row['registration_date']; ?></td>
      <td>
        <form method="post" action="">
          <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
          <input class="button edit" type="submit" name="edit" value="Edit">
        </form>
        <form method="post" action="">
          <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
          <input class="button delete" type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure?');">
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
</body>
</html>
