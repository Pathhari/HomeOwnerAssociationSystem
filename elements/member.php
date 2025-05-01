<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$successMessage = '';
$errorMessage = '';

// Check if form data is received via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Payment processing logic
    // Placeholder for your actual payment processing code
    $userId = $_SESSION['user_id'];  // Get user ID from session
    $amount = $_POST['amount'];      // Get amount from form
    // ... other payment related fields ...
    // Assume $paymentSuccessful is set based on payment processing result
    $paymentSuccessful = true;  // This should be set based on actual payment status

    if ($paymentSuccessful) {
        // Prepare and execute membership data insertion on successful payment
        $stmt = $connection->prepare("
            INSERT INTO membership (
                user_id, last_name, first_name, middle_name, block, lot, street, phase,
                cellphone, telephone, email_address, education_post_grad,
                education_college, education_highschool, education_elementary,
                occupant_name, occupant_age, relation_to_member, gender
            ) VALUES (
                :user_id, :last_name, :first_name, :middle_name, :block, :lot, :street, :phase,
                :cellphone, :telephone, :email_address, :education_post_grad,
                :education_college, :education_highschool, :education_elementary,
                :occupant_name, :occupant_age, :relation_to_member, :gender
            )
        ");

        // Bind parameters
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':last_name', $_POST['last_name']);
        $stmt->bindParam(':first_name', $_POST['first_name']);
        // ... Bind other parameters ...

        // Execute the statement
        if ($stmt->execute()) {
            $successMessage = 'Payment and Membership submitted successfully!';
        } else {
            $errorMessage = 'There was a problem submitting the membership.';
        }
    } else {
        $errorMessage = 'Payment failed. Membership not submitted.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOA Payment and Membership</title>
    <link rel="stylesheet" href="dashboardstyle.css">
</head>
<body>
<section class="services-section">
            <h2>MEMBERSHIP INFORMATION</h2>
            <div class="p-container">
                <form id="membershipForm" method="post">
                    <div class="form-section">
                        <h3>Basic Information</h3>
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required placeholder="Your Last Name">
                        
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" required placeholder="Your First Name">

                        <label for="middle_name">Middle Name:</label>
                        <input type="text" id="middle_name" name="middle_name" placeholder="Your Middle Name">
                    </div>

                    <div class="form-section">
                        <h3>Address</h3>
                        <label for="block">Block:</label>
                        <input type="text" id="block" name="block" placeholder="Block Number">
                        
                        <label for="lot">Lot:</label>
                        <input type="text" id="lot" name="lot" placeholder="Lot Number">

                        <label for="street">Street:</label>
                        <input type="text" id="street" name="street" placeholder="Street Name">

                        <label for="phase">Phase:</label>
                        <input type="text" id="phase" name="phase" placeholder="Phase Number">
                    </div>

                    <div class="form-section">
                        <h3>Contact Information</h3>
                        <label for="cellphone">Cellphone:</label>
                        <input type="tel" id="cellphone" name="cellphone" placeholder="Cellphone Number">
                        
                        <label for="telephone">Telephone:</label>
                        <input type="tel" id="telephone" name="telephone" placeholder="Telephone Number">

                        <label for="email">Email address:</label>
                        <input type="email" id="email" name="email" required placeholder="Your Email Address">
                    </div>

                    <div class="form-section">
                        <h3>Education</h3>
                        <label for="post_grad">Post-graduate:</label>
                        <input type="text" id="post_grad" name="post_grad" placeholder="Post-graduate Details">
                        
                        <label for="college">College:</label>
                        <input type="text" id="college" name="college" placeholder="College Details">

                        <label for="highschool">Highschool:</label>
                        <input type="text" id="highschool" name="highschool" placeholder="Highschool Details">

                        <label for="elementary">Elementary:</label>
                        <input type="text" id="elementary" name="elementary" placeholder="Elementary Details">
                    </div>

                    <div class="form-section">
                        <h3>Other Occupants</h3>
                        <label for="occupant_name">Name:</label>
                        <input type="text" id="occupant_name" name="occupant_name" placeholder="Occupant's Name">
                        
                        <label for="occupant_age">Age:</label>
                        <input type="number" id="occupant_age" name="occupant_age" placeholder="Occupant's Age">

                        <label for="occupant_relation">Relation to Member:</label>
                        <input type="text" id="occupant_relation" name="occupant_relation" placeholder="Relation to Member">

                        <label for="occupant_gender">Gender:</label>
                        <select id="occupant_gender" name="occupant_gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <button type="button" onclick="goToNextPage()">Next</button>
<button type="button" onclick="closeForm()">Close</button>
                </form>
                <form action="submit_payment.php" method="post" id="gcashForm">
            <div class="form-group">
                <label for="Name">G-cash Account Name:</label>
                <input type="name id="Name" name="name" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" required>
            </div>
            <div class="form-group">
                <label for="gcash_number">Gcash Number:</label>
                <input type="tel" id="gcash_number" name="gcash_number" required>
            </div>
            <div class="form-group">
                <label for="transaction_id">Transaction ID:</label>
                <input type="text" id="transaction_id" name="transaction_id" required>
            </div>
            <div class="form-group">
                <label for="receipt">Upload Receipt:</label>
                <input type="file" id="receipt" name="receipt" required>
            </div>
            <button type="submit">Pay with Gcash</button>
        </form>
            </div>
        </section>
    </main>

    </section>
    
    <?php if (!empty($successMessage)) echo "<p>$successMessage</p>"; ?>
    <?php if (!empty($errorMessage)) echo "<p>$errorMessage</p>"; ?>
</body>
</html>