<?php
$host = "localhost";
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "usersidedb"; // Replace with your dbname
$charset = 'utf8mb4';


$conn = new mysqli($host, $username, $password, $dbname);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Sanitize and validate input
  $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
  $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
  $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
  // ... sanitize other fields

  // SQL to update user data
  $sql = "UPDATE users SET first_name = ?, last_name = ? WHERE user_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$first_name, $last_name, $user_id]);
  
  // Echo result or status
  echo "User updated successfully";
}
?>
