<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="loregstyle.css">
</head>
<body>
    <?php
  ob_start(); // Turn on output buffering
  session_start(); // Start or resume a session


    
    // Database configuration
    $host = "localhost"; // or your database host
    $db_name = "usersidedb";
    $username = "root";
    $password = "";
    $connection = null;

    try {
        // Attempt to connect to the database
        $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    // Check if the form data has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form data
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password']; // Remember to hash the password before storing it
        $phone = $_POST['phone'];
        

        // Prepare and execute the insert statement
        $stmt = $connection->prepare("INSERT INTO users (first_name, last_name, email, password, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $first_name,
            $last_name,
            $email,
            password_hash($password, PASSWORD_DEFAULT), // Hash the password
            $phone
        ]);

         // Inform the user of successful registration and delay for a bit so they can read the message
    echo "<p>Registration successful! Redirecting to login...</p>";
    sleep(2); // Delay for 2 seconds

    // Redirect to the log-in page
    header('Location: log-in.php');
    exit; // Ensure no further code is executed after redirection
}
ob_end_flush(); // Send the output buffer and turn off output buffering
?>

    <div class="registration-wrapper">
        <div class="form-container">
            <h2>REGISTER</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="registration-form">
            <div class="form-row">
                    <div class="form-group">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first_name" required placeholder="Enter First Name">
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name:</label>
                        <input type="text" id="last-name" name="last_name" required placeholder="Enter Last Name">
                    </div>
                </div>
                <div class="form-group single">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required placeholder="Enter Email">
                </div>                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required placeholder="Enter Password">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="tel" id="phone" name="phone" required placeholder="Enter Phone Number">
                    </div>
                
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">REGISTER</button>
                </div>
            </form>
        </div>
        <div class="logo-container">
            <img src="imgs/logo.png" alt="Company Logo" class="logo">
        </div>
    </div>
</body>
</html>
