<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: log-in.php');
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

$successMessage = '';
$errorMessage = '';

// Check if form data is received via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $lastName = $_POST['last_name'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $middleName = $_POST['middle_name'] ?? '';
    $block = $_POST['block'] ?? '';
    $lot = $_POST['lot'] ?? '';
    $street = $_POST['street'] ?? '';
    $phase = $_POST['phase'] ?? '';
    $cellphone = $_POST['cellphone'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $emailAddress = $_POST['email_address'] ?? '';
    $educationPostGrad = $_POST['education_post_grad'] ?? '';
    $educationCollege = $_POST['education_college'] ?? '';
    $educationHighschool = $_POST['education_highschool'] ?? '';
    $educationElementary = $_POST['education_elementary'] ?? '';
    $occupantName = $_POST['occupant_name'] ?? '';
    $occupantAge = $_POST['occupant_age'] ?? '';
    $relationToMember = $_POST['relation_to_member'] ?? '';
    $gender = $_POST['gender'] ?? '';

    // Prepare the SQL statement
    $stmt = $connection->prepare("
        INSERT INTO membership (
            last_name, first_name, middle_name, block, lot, street, phase,
            cellphone, telephone, email_address, education_post_grad,
            education_college, education_highschool, education_elementary,
            occupant_name, occupant_age, relation_to_member, gender
        ) VALUES (
            :last_name, :first_name, :middle_name, :block, :lot, :street, :phase,
            :cellphone, :telephone, :email_address, :education_post_grad,
            :education_college, :education_highschool, :education_elementary,
            :occupant_name, :occupant_age, :relation_to_member, :gender
        )
    ");

    // Bind parameters to the prepared statement
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':middle_name', $middleName);
    $stmt->bindParam(':block', $block);
    $stmt->bindParam(':lot', $lot);
    $stmt->bindParam(':street', $street);
    $stmt->bindParam(':phase', $phase);
    $stmt->bindParam(':cellphone', $cellphone);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':email_address', $emailAddress);
    $stmt->bindParam(':education_post_grad', $educationPostGrad);
    $stmt->bindParam(':education_college', $educationCollege);
    $stmt->bindParam(':education_highschool', $educationHighschool);
    $stmt->bindParam(':education_elementary', $educationElementary);
    $stmt->bindParam(':occupant_name', $occupantName);
    $stmt->bindParam(':occupant_age', $occupantAge);
    $stmt->bindParam(':relation_to_member', $relationToMember);
    $stmt->bindParam(':gender', $gender);
// Execute the statement and check if it was successful
if ($stmt->execute()) {
    // Redirect to the payment form page
    header('Location: mempaymentwall.php');
    exit;
} else {
    $errorMessage = 'There was a problem submitting the membership.';
}
    // Execute the statement and check if it was successful
    if ($stmt->execute()) {
        $successMessage = 'Membership submitted successfully!';
    } else {
        $errorMessage = 'There was a problem submitting the membership.';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOA Transecta</title>
    <link rel="stylesheet" href="dashboardstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
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
    <main>
        <section class="services-section">
            <h2>MEMBERSHIP INFORMATION</h2>
            <div class="p-container">
            <form id="membershipForm" method="post" onsubmit="return validateForm()">

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
                        <input type="email" id="email_address" name="email_address" required placeholder="Your Email Address">
                    </div>

                    <div class="form-section">
                        <h3>Education</h3>
                        <label for="post_grad">Post-graduate:</label>
                        <input type="text" id="education_post_grad" name="education_post_grad" placeholder="Post-graduate Details">
                        
                        <label for="college">College:</label>
                        <input type="text" id="education_college" name="education_college" placeholder="College Details">

                        <label for="highschool">Highschool:</label>
                        <input type="text" id="education_highschool" name="education_highschool" placeholder="Highschool Details">

                        <label for="elementary">Elementary:</label>
                        <input type="text" id="education_elementary" name="education_elementary" placeholder="Elementary Details">
                    </div>

                    <div class="form-section">
                        <h3>Other Occupants</h3>
                        <label for="occupant_name">Name:</label>
                        <input type="text" id="occupant_name" name="occupant_name" placeholder="Occupant's Name">
                        
                        <label for="occupant_age">Age:</label>
                        <input type="number" id="occupant_age" name="occupant_age" placeholder="Occupant's Age">

                        <label for="occupant_relation">Relation to Member:</label>
                        <input type="text" id="relation_to_member" name="relation_to_member" placeholder="Relation to Member">

                        <label for="occupant_gender">Gender:</label>
                        <select id="gender" name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <button type="submit" onclick="goToNextPage()">Next</button>


<button type="button" onclick="closeForm()">Close</button>
                </form>
            </div>
            <div id="successMessage" class="floating-message-container" style="display: none;"></div>
<div id="errorMessage" class="floating-message-container" style="display: none;"></div>
        </section>
    </main>

    </section>
    <script>
        function validateForm() {
    function isFieldEmpty(fieldId) {
        var value = document.getElementById(fieldId).value;
        return !value || value.trim() === '';
    }

    // Add checks for required fields
    if (isFieldEmpty('last_name') || isFieldEmpty('first_name') || isFieldEmpty('email')) {
        alert("Last name, first name, and email are required.");
        return false;
    }

    // Email format validation
    var email = document.getElementById('email').value;
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    return true;
}
    function goToNextPage() {
    if (validateForm()) {
        document.getElementById('membershipForm').submit();
        
    } else {
        // Handle validation failure
        alert("Please fill all required fields.");
    }
}




</script>
</main>
</body>
</html>