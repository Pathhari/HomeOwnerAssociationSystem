<?php
session_start(); // Start the session to store user information

// Database configuration
$host = 'localhost';
$dbname = 'usersidedb';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$login_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST['login']);
    $input_password = trim($_POST['password']);

    // Attempt to retrieve admin and superadmin data
    $stmt = $connection->prepare("SELECT admin_id, username, password, 'admin' as type FROM admin WHERE username = ? UNION SELECT superadmin_id, username, password, 'superadmin' as type FROM superadmin WHERE username = ?");
    $stmt->execute([$login, $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Validate password
        if ($input_password === $user['password']) {
            if ($user['type'] === 'admin') {
                // Set session variables and redirect for admin
                $_SESSION['admin_id'] = $user['admin_id'];
                $_SESSION['username'] = $user['username'];
                header("Location: officer_dashboard.php");
                exit;
            }else if ($input_password === $user['password'] && $user['type'] === 'superadmin') {
                // Set session variables for Super Admin
                $_SESSION['superadmin_id'] = $user['superadmin_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_super_admin'] = true; // This is the important part
            
                header("Location: superadmin.php");
                exit;
            }
        } else {
            $login_err = "Invalid username or password.";
        }
    } else {
        // If the user is not found in the admin or superadmin, check the regular users
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $stmt = $connection->prepare("SELECT user_id, email, password FROM users WHERE email = ?");
            $stmt->execute([$login]);
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($input_password, $user['password'])) {
                    // Set session variables and redirect for regular user
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['email'] = $user['email'];
                    header("Location: user_dashboard.php");
                    exit;
                } else {
                    $login_err = "Invalid username or password.";
                }
            }
        }
    }

    if (empty($login_err)) {
        $login_err = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="loregstyle.css">
</head>
<body>
    <div class="wrapper">
        <div class="form-container">
            <h2>LOG IN</h2>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form">
    <div class="form-group">
        <label for="login">Email or Username:</label>
        <input type="text" id="login" name="login" required placeholder="Enter Email or Username">
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required placeholder="Enter Password">
    </div>
    <div class="form-group">
        <button type="submit" class="btn">LOG-IN</button>
    </div>
                <?php
            if (!empty($login_err)) {
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }
            ?>
                <div class="link-container">
                    <a href="register.php" class="link-button">REGISTER HERE!</a>
                </div>
            </form>
        </div>
        <div class="logo-container">
            <img src="imgs/logo.png" alt="Company Logo" class="logo">
        </div>
    </div>
</body>
</html>