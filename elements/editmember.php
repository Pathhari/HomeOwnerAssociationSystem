<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member - HOA Transecta</title>
    <link rel="stylesheet" href="loregstyle.css">

    <style>
        .form-container .btn-close {
        background-color: red !important; /* Red background with !important to override any other styles */
        color: white !important; /* White text color with !important to override any other styles */
        border: none; /* Remove border */
        cursor: pointer; /* Change cursor to pointer when hovering over the button */
    }
    .form-container .btn-close:hover {
        background-color: darkred !important; /* Darker red when hovering with !important to override any other styles */
    }
    </style>
</head>
<body>
    <?php
     session_start(); // Start or resume a session

     // Database configuration
     $host = "localhost";
     $db_name = "usersidedb";
     $username = "root";
     $password = "";
     $connection = null;

 
     try {
         $connection = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
         $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     } catch(PDOException $e) {
         die("Connection failed: " . $e->getMessage());
     }
 
     // Assuming you pass the user ID to edit in the URL as a GET parameter
     $userId = isset($_GET['membership_id']) ? (int)$_GET['membership_id'] : 0;
 
     // Fetch the existing user data
     if ($userId > 0) {
        $stmt = $connection->prepare("SELECT * FROM membership WHERE membership_id = ?"); // Correct the column name here
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Populate form fields with existing data
    } else {
        die("No membership ID provided.");
    }
    
    // Check if the form data has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form data
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $block = $_POST['block'];
        $lot = $_POST['lot'];
        $street = $_POST['street'];
        $phs = $_POST['phase'];
        $email = $_POST['email_address'];
        $oc_name = $_POST['occupant_name'];
        $phone = $_POST['cellphone'];
        
        // Prepare and execute the update statement
        $stmt = $connection->prepare("UPDATE membership SET first_name = ?, last_name = ?, email_address = ?,  block = ?, lot = ?, street = ?, phase = ?,occupant_name = ?, cellphone = ? WHERE membership_id = ?"); // Correct the column name here
        $stmt->execute([
            $first_name,
            $last_name,
            $email,
            $block,
            $lot,
            $street,
            $phase,
            $oc_name,
            $phone,
            $userId
        ]);
        
        // Inform the user of successful update and redirect
        echo "<p>Update successful!</p>";
        header('Location: membersdatas.php'); // Redirect to the list page or wherever you want
        exit; // Ensure no further execution after a redirect
    }
    ?>

    <div class="registration-wrapper">
        <div class="form-container">
            <h2>Edit Member</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?membership_id=<?php echo $userId; ?>" method="post" class="registration-form">
    <div class="form-row">
        <div class="form-group">
            <label for="first-name">First Name:</label>
            <input type="text" id="first-name" name="first_name" required placeholder="Enter First Name" value="<?php echo isset($user['first_name']) ? htmlspecialchars($user['first_name']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="last-name">Last Name:</label>
            <input type="text" id="last-name" name="last_name" required placeholder="Enter Last Name" value="<?php echo isset($user['last_name']) ? htmlspecialchars($user['last_name']) : ''; ?>">
        </div>
    </div>
    <div class="form-group single">
        <label for="block">Block:</label>
        <input type="text" id="block" name="block" required placeholder="Enter Block" value="<?php echo isset($user['block']) ? htmlspecialchars($user['block']) : ''; ?>">
    </div>  
    <div class="form-group single">
        <label for="lot">Lot:</label>
        <input type="text" id="lot" name="lot" required placeholder="Enter Lot" value="<?php echo isset($user['lot']) ? htmlspecialchars($user['lot']) : ''; ?>">
    </div>  
    <div class="form-group single">
        <label for="street">Street:</label>
        <input type="text" id="street" name="street" required placeholder="Enter Street" value="<?php echo isset($user['street']) ? htmlspecialchars($user['street']) : ''; ?>">
    </div>  
    <div class="form-group single">
        <label for="phase">Phase:</label>
        <input type="text" id="phase" name="phase" required placeholder="Enter Phase" value="<?php echo isset($user['phase']) ? htmlspecialchars($user['phase']) : ''; ?>">
    </div>  
    <div class="form-group">
            <label for="name">Occupant Name:</label>
            <input type="text" id="name" name="occupant_name" required placeholder="Enter Occupant Name" value="<?php echo isset($user['occupant_name']) ? htmlspecialchars($user['occupant_name']) : ''; ?>">
        </div> 
    <div class="form-group single">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required placeholder="Enter Email" value="<?php echo isset($user['email_address']) ? htmlspecialchars($user['email_address']) : ''; ?>">
    </div>                
    <div class="form-row">
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required placeholder="Enter Phone Number" value="<?php echo isset($user['cellphone']) ? htmlspecialchars($user['cellphone']) : ''; ?>">
        </div>
    </div>
    <!-- Add a hidden field to keep track of the user's ID -->
    <input type="hidden" name="membership_id" value="<?php echo $userId; ?>">
    <div class="form-group">
        <button type="submit" class="btn" onclick="return confirm('Are you sure to save changes?');">SAVE</button> 
        <button type="button" class="btn btn-close" onclick="redirectToOtherPage()">CLOSE</button>

    </div>
</form>
        </div>
       
    </div>
</body>
</html>
<script>
        // JavaScript function to redirect to another PHP page
        function redirectToOtherPage() {
            window.location.href = 'membersdatas.php'; // Replace 'otherpage.php' with the actual PHP file you want to redirect to
        }
    </script>