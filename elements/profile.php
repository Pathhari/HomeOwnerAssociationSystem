<?php
session_start(); // Start or resume a session

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: log-in.php');
    exit;
}

// Database configuration
$host = "localhost";
$db_name = "usersidedb";
$username = "root";
$password = "";
$connection = null;

try {
    // Connect to the database
    $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user data from the database using the user ID from the session
    $stmt = $connection->prepare("SELECT user_id, first_name, last_name, email, phone FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the form data has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the updated data from the form
        $first_name = $_POST['first_name'] ?? $user['first_name'];
        $last_name = $_POST['last_name'] ?? $user['last_name'];
        $email = $_POST['email'] ?? $user['email'];
        $phone = $_POST['phone'] ?? $user['phone'];
        $password_to_update = $_POST['password'] ?? null;
        $confirm_password = $_POST['confirm_password'] ?? null;

        // Initialize an array to hold the parameters for the SQL statement
        $params = [
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone' => $phone,
            ':user_id' => $_SESSION['user_id']
        ];

        // Prepare the UPDATE statement
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone";

        // If password fields are set and match, add password to the update statement
        if ($password_to_update && $password_to_update === $confirm_password) {
            $hashed_password = password_hash($password_to_update, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
            $params[':password'] = $hashed_password;
        }

        // Finish preparing the SQL statement
        $sql .= " WHERE user_id = :user_id";
        $update_stmt = $connection->prepare($sql);

        // Execute the statement with the parameters
        $update_stmt->execute($params);

        // Optional: Set a message to indicate success
        $success_msg = "Profile updated successfully.";

        // Optional: Refresh the page to show the updated information
        header("Location: profile.php");
        exit;
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HOA Transecta - User Profile</title>
<link rel="stylesheet" href="dashboardstyle.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<style>
   body, html {
    background-color: #e0f2f1;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    background: #ffffff;
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.profile h2 {
    font-size: 24px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.profile p {
    font-size: 16px;
    color: #555;
    text-align: center;
    margin-bottom: 20px;
}

.profile label {
    font-weight: bold;
    color: #555;
    display: block;
    margin-top: 10px;
}

.profile .form-control {
    display: block;
    width: calc(100% - 22px); /* Subtract the padding and border */
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 16px;
}

.profile .gender {
    display: flex; /* Use flexbox for inline arrangement */
    align-items: center; /* Align items vertically */
    justify-content: start; /* Align items to the start of the container */
    margin-top: 20px; /* Spacing from the previous element */
}

.profile .gender label {
    margin-right: 10px; /* Spacing between label and input */
    white-space: nowrap; /* Prevent wrapping of text */
}

.profile .gender input[type="radio"] {
    margin-right: 5px; /* Spacing after the radio button */
    margin-left: 0; /* Ensure there's no margin on the left */
}

.profile .save-btn {
    background: #00897b;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: block;
    width: 100%;
    margin-top: 20px;
    font-size: 18px;
}

.profile .save-btn:hover {
    background: #00695c;
}

@media (max-width: 768px) {
    .container {
        width: 90%;
        margin: 20px auto;
    }

    .profile h2, .profile p {
        font-size: 20px;
    }

    .profile .gender {
        flex-direction: column; /* Stack the gender options vertically on small screens */
        align-items: flex-start; /* Align items to the start of the container */
    }

    .profile .gender label {
        margin: 5px 0; /* Adjust vertical spacing between options */
    }

    /* Make sure the gender options don't stack too tightly on small screens */
    .profile .gender input[type="radio"] {
        margin-bottom: 5px;
    }
}


</style>
</head>
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
<div class="container">
    <div class="profile">
        <h2>My Profile</h2>
        <p>Manage and protect your account</p>
    <form method="post">
    <label for="name">Name</label>
    <input type="text" id="name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>">
    <!-- You need to separate the first name and last name inputs if they are separate in the database -->
    <input type="text" id="name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>">

    <label for="email">Email</label>
    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
    <label for="password">New Password</label>
<div class="password-container">
    <input type="password" id="password" name="password" class="form-control">
    <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('password')"></i>
</div>

<!-- Confirm New Password Field -->
<label for="confirm_password">Confirm New Password</label>
<div class="password-container">
    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
    <i class="fas fa-eye toggle-password" onclick="togglePasswordVisibility('confirm_password')"></i>
</div>
    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">

    <!-- Add your gender logic here -->

    <button type="submit" class="save-btn">Save</button>
</form>
<script>
// Function to toggle the visibility of the password
function togglePasswordVisibility(fieldId) {
    var passwordField = document.getElementById(fieldId);
    var passwordFieldType = passwordField.getAttribute('type');

    if (passwordFieldType == 'password') {
        passwordField.setAttribute('type', 'text');
    } else {
        passwordField.setAttribute('type', 'password');
    }
}
</script>


    </div>
</div>

</body>
</html>
