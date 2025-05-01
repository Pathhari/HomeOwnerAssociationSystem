<?php
session_start(); // Start the session at the very beginning

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['admin_id'])) {
  header('Location: log-in.php');
  exit;
}
$host = "localhost"; // Your host name or IP address
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "usersidedb"; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get total registered users
$userCountQuery = "SELECT COUNT(*) AS total_users FROM users";
$userCountResult = $conn->query($userCountQuery);
$userCountRow = $userCountResult->fetch_assoc();
$totalUsers = $userCountRow['total_users'];

// Query to get recent memberships
$recentMembershipQuery = "SELECT COUNT(*) AS recent_memberships FROM membership";
$recentMembershipResult = $conn->query($recentMembershipQuery);
$recentMembershipRow = $recentMembershipResult->fetch_assoc();
$recentMemberships = $recentMembershipRow['recent_memberships'];

// Query to get total collection
$collectionQuery = "SELECT SUM(amount) AS total_collection FROM gcash_payments";
$collectionResult = $conn->query($collectionQuery);
$collectionRow = $collectionResult->fetch_assoc();
$totalCollection = $collectionRow['total_collection'];

$latestUsersQuery = "SELECT first_name, registration_date FROM users ORDER BY registration_date DESC LIMIT 3";
$latestUsersResult = $conn->query($latestUsersQuery);
$latestUsers = $latestUsersResult->fetch_all(MYSQLI_ASSOC);

$recentPaymentsQuery = "SELECT name, amount, payment_date FROM gcash_payments ORDER BY payment_date DESC LIMIT 3";
$recentPaymentsResult = $conn->query($recentPaymentsQuery);
$recentPayments = $recentPaymentsResult->fetch_all(MYSQLI_ASSOC);

$paymentTypesQuery = "SELECT payment_type, COUNT(*) as count FROM gcash_payments GROUP BY payment_type";
$paymentTypesResult = $conn->query($paymentTypesQuery);
$paymentTypesData = [];
while($row = $paymentTypesResult->fetch_assoc()) {
  $paymentTypesData[] = [$row['payment_type'], (int)$row['count']];
}

// Encode the data as JSON to be used by JavaScript
$paymentTypesJSON = json_encode($paymentTypesData);

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="dashboardstyles.css">
<!-- Add this inside your <head> tag -->
<link href='https://fonts.googleapis.com/css?family=Nunito:400,700&display=swap' rel='stylesheet'>

<style>
/* styles.css */
body, html {
  margin: 0;
  padding: 0;
  font-family: 'Nunito', sans-serif; /* A more modern font */
  background: #f4f7fa;
  color: #333;
}
.lower-cards {
  text-align: center; /* Center the text */
  padding: 10px;
  background-image: url(imgs/bg.png); /* A green background for visibility */
  color: white; /* White text color */
  border-radius: 8px; /* Rounded corners */
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); /* Adding some depth with a shadow */
  margin: 10px; /* Add some margin around the element */
  transition: transform 0.3s ease; /* For a hover effect */
}

.lower-cards:hover {
  transform: translateY(-5px); /* Move up slightly on hover */
  box-shadow: 0px 12px 20px 0px rgba(0,0,0,0.3); /* Make the shadow deeper on hover */
}

/* Styling for the heading text */
.lower-cards h2 {
  margin: 0; /* Reset the margin */
  padding: 0;
  font-size: 2.5rem; /* Larger font size */
  font-weight: 700; /* Make it bold */
  text-shadow: 2px 2px 4px rgba(0,0,0,0.2); /* Text shadow for a subtle 3D effect */
}
.welcome-content {
  text-align: center; /* Center the text */
  padding: 10px;
  background-image: url(imgs/bg.png); /* A green background for visibility */
  color: white; /* White text color */
  border-radius: 8px; /* Rounded corners */
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); /* Adding some depth with a shadow */
  margin: 10px; /* Add some margin around the element */
  transition: transform 0.3s ease; /* For a hover effect */
}

.welcome-content:hover {
  transform: translateY(-5px); /* Move up slightly on hover */
  box-shadow: 0px 12px 20px 0px rgba(0,0,0,0.3); /* Make the shadow deeper on hover */
}

/* Styling for the heading text */
.welcome-content h2 {
  margin: 0; /* Reset the margin */
  padding: 0;
  font-size: 2.5rem; /* Larger font size */
  font-weight: 700; /* Make it bold */
  text-shadow: 2px 2px 4px rgba(0,0,0,0.2); /* Text shadow for a subtle 3D effect */
}
#sidebar {
  width: 250px;
  height: 100vh;
  background: #27293D; /* A deep shade for the sidebar */
  color: #fff;
  position: fixed;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
}

.sidebar-header {
  padding: 20px;
  background-image: url(imgs/bg.png);
  text-align: center;
  font-size: 1.4em;
}

.sidebar-menu {
  list-style: none;
  padding: 0;
  margin-top: 20px;
}

.menu-item {
  padding: 20px;
  border-bottom: 1px solid #40445c; /* Slight contrast for separation */
  transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for hover effect */
  cursor: pointer; /* Change mouse cursor on hover */
}

.menu-item:hover, .menu-item.active {
  background-color: #1A1C27; /* Darken item on hover/active */
  color: #4cd137; /* Change text color for contrast */
}

.menu-item i {
  margin-right: 10px;
  transition: transform 0.3s ease; /* Smooth icon transition */
}

.menu-item:hover i {
  transform: scale(1.1); /* Slightly enlarge icons on hover */
}

#main-content {
  margin-left: 250px;
  padding: 20px;
  background: #fff; /* Light background for the content */
  min-height: 100vh;
  box-shadow: -1px 0 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for content box */
}

/* Additional global styles */
a {
  text-decoration: none;
  color: inherit; /* Links inherit the color from its parent */
}

a:hover {
  color: #4cd137; /* Change link color on hover */
}
/* Add to your existing <style> or external CSS file */

.welcome-content h2 {
  color: #333;
  margin-bottom: 10px;
}

.welcome-content p {
  color: #555;
  margin-bottom: 20px;
}

.overview-cards {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

/* Example styles for overview cards */
.overview-card {
  background: #FFFFFF;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  padding: 20px;
  width: calc(33% - 10px);
  margin-right: 15px;
  text-align: center;
}

.overview-card:last-child {
  margin-right: 0;
}

.overview-card h3 {
  margin-top: 0;
  color: #27293D;
}

.overview-card p {
  color: #555;
}

.menu-item.logout {
  color: #ff4d4d; /* Example color for log-out button */
}
.latest-payments-card {
  cursor: pointer; /* Makes it clear the card is clickable */
  transition: all 0.3s ease;
  display: block; /* Ensures the <a> tag behaves like a block element */
  color: inherit; /* Ensures the text inherits the parent color */
}

.latest-payments-card:hover {
  background-color: #e8f5e9; /* Light green background to indicate hover */
  transform: scale(1.02); /* Slightly enlarge the card on hover */
}

.latest-payments-card ul {
  list-style-type: none; /* Removes bullet points */
  padding: 0;
  margin: 0;
}

.latest-payments-card li {
  font-size: 0.9em; /* Smaller font size for list items */
  color: #666; /* Dark grey color for list text */
  margin-bottom: 5px; /* Spacing between list items */
}
.latest-users-card {
  cursor: pointer; /* Makes it clear the card is clickable */
  transition: all 0.3s ease;
  display: block; /* Ensures the <a> tag behaves like a block element */
  color: inherit; /* Ensures the text inherits the parent color */
}
.latest-users-card.latest-payments-card{
  padding-left: 50px;
}
.latest-users-card:hover {
  background-color: #e8f5e9; /* Light green background to indicate hover */
  transform: scale(1.02); /* Slightly enlarge the card on hover */
}

.latest-users-card ul {
  list-style-type: none; /* Removes bullet points */
  padding: 0;
  margin: 0;
}

.latest-users-card li {
  font-size: 0.9em; /* Smaller font size for list items */
  color: #666; /* Dark grey color for list text */
  margin-bottom: 5px; /* Spacing between list items */
}
.overview-cards  {
  display: flex;
  flex-wrap: wrap; /* Allows the cards to wrap to the next line if the screen is too small */
  justify-content: space-between;
  gap: 20px; /* Adds space between the cards */
}

.overview-cards {
  display: flex;
  flex-wrap: wrap;
  gap: 20px; /* Adds space between the cards */
  justify-content: space-between;
}

.overview-card {
  flex: 1;
  margin: 5px;
  max-width: calc(33% - 20px); /* Adjust the width as necessary */
}

/* Ensure that the last card takes up the remaining space if you want to */
.overview-card:last-child {
  flex: 1;
}

.latest-users-card ul {
  /* Existing styles... */
  padding: 0; /* Removes padding inside the list */
}

.latest-users-card li {
  /* Existing styles... */
  list-style-type: none; /* Removes bullet points */
}
.lower-cards {
  display: flex;
  flex-direction: column; /* Stack the cards vertically */
  gap: 20px; /* Space between the cards */
}

/* Ensure cards within the lower-cards container take full width */
.lower-cards .overview-card {
  width: 100%;
}

/* ... existing styles ... */

/* Ensure cards within the lower-cards container take full width */
.lower-cards .overview-card {

  width: 100%; /* Take the full width of the container */
  margin-right: 0; /* Reset any right margin if set */
}

/* Remove any float properties from these classes */
.latest-users-card, .latest-payments-card {
  float: none;
}

/* Remove max-width limitation if previously set */
.latest-users-card, .latest-payments-card {
  max-width: none;
}

/* You might want to adjust the padding and margin of the cards for better alignment */
.overview-card {
  padding: 10px;
  margin-bottom: 5px; /* Adjust the bottom margin for spacing between cards */
}

.lower-cards {
  background-image: url(imgs/bg.png);
  padding: 15px; /* Provide some padding */
  margin: 10px 0; /* Consistent margin as above */
  border-radius: 8px; /* Match border radius */
  box-shadow: 0px 4px 8px rgba(0,0,0,0.1); /* Match box shadow */
  align-items: stretch; /* Ensure cards stretch full width */
}
.latest-users-card, .latest-payments-card {
  background: white;
}

.latest-users-card, .latest-payments-card {
  padding-left: 0;
  padding-right: 0;
}

.lower-cards {
  display: flex;
  flex-direction: row; /* Align children in a row */
  flex-wrap: wrap; /* Allow items to wrap as needed */
  align-items: stretch; /* Stretch the children full height */
  gap: 20px; /* Space between the children */
  padding: 15px; /* Padding inside the lower-cards container */
}

/* Flex child styling */
.overview-card, #chart_div_container {
  flex: 1; /* Flex children grow equally */
  margin: 5px; /* Margin around the cards */
  min-width: 250px; /* Minimum width of the children */
}

/* Specific styling for the chart container */
#chart_div_container {
  padding: 0; /* No padding for the chart container */
}

/* Styling for the chart itself */
#chart_div {
  background-color: #fff; /* White background for the chart area */
  border-radius: 8px; /* Rounded corners like other cards */
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Consistent shadow with other elements */
}


</style>
<!-- Boxicons CDN for icons (you may use your preferred one) -->
<link href='https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

<!-- Sidebar -->
<aside id="sidebar">
  <div class="sidebar-header">
  <img src="imgs/logo.png" alt="HOA Transecta Logo" class="logo">
  </div>
<!-- ... -->
<ul class="sidebar-menu">
  <li class="menu-item active"><a href="#"><i class='bx bx-home-alt'></i> Dashboard</a></li>
  <li class="menu-item"><a href="history.php"><i class='bx bx-history'></i> History</a></li>
  <li class="menu-item"><a href="membersdata.php"><i class='bx bx-data'></i>Members Data</a></li>
  <li class="menu-item"><a href="costcomp.php"><i class='bx bx-calculator'></i> Cost Computation</a></li>
  <li class="menu-item logout"><a href="logout.php"><i class='bx bx-log-out'></i> Log Out</a></li>


</ul>
<!-- ... -->

</aside>

<!-- Main Content -->
<div id="main-content">
  <!-- Welcome Message -->
  <div class="welcome-content">
    <h2>Welcome to Your Dashboard Officer</h2>
    <!-- Overview Cards -->
    <div class="overview-cards">
      <!-- Card for User Count -->
      <div class="overview-card">
        <h3>Users</h3>
        <p><strong><?php echo $totalUsers; ?></strong></p>
        <p>Total Registered Users</p>
      </div>
      <!-- Card for Recent Membership -->
      <div class="overview-card">
        <h3>Membership</h3>
        <p><strong><?php echo $recentMemberships; ?></strong></p>
        <p>Total Members</p>
      </div>
      <!-- Card for Collection -->
      <div class="overview-card">
        <h3>Collection</h3>
        <p><strong>₱<?php echo $totalCollection; ?></strong></p>
        <p>Total Collection</p>
      </div>
     
    </div>
    </div>
    <div class="lower-cards">
    <div class="overview-card latest-users-card">
      <h3>Latest Registered Users</h3>
      <a href="history.php">
      <ul>
        <?php foreach ($latestUsers as $user): ?>
          <li><?php echo htmlspecialchars($user['first_name']); ?> - <?php echo htmlspecialchars(date('M j, Y', strtotime($user['registration_date']))); ?></li>
        <?php endforeach; ?>
      </ul>
      </a>
  </div>
  <div class="overview-card latest-payments-card">
  <h3>Recent Payment Transactions</h3>
  <a href="history.php">
  <ul>
    <?php foreach ($recentPayments as $payment): ?>
      <li><?php echo htmlspecialchars($payment['name']); ?> - ₱<?php echo htmlspecialchars(number_format($payment['amount'], 2)); ?> - <?php echo htmlspecialchars(date('M j, Y', strtotime($payment['payment_date']))); ?></li>
    <?php endforeach; ?>
  </ul>
  </a>
  </div>
  
</div>
<div id="chart_div_container">
      
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      
      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Payment Type');
  data.addColumn('number', 'Count');
  data.addRows(<?php echo $paymentTypesJSON; ?>);

  // Set chart options for a responsive chart
  var options = {
    title: 'Total Collections Chart',
    chartArea: {
      width: '80%', // You might need to adjust this
      height: '70%' // You might need to adjust this
    },
    legend: { position: 'bottom' }
  };

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}

// Add event listener for window resize
google.charts.setOnLoadCallback(drawChart);
$(window).resize(function(){
  drawChart();
});
    </script>
  </head>

  <body>
    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>
      </div>
    </div>
</div>
</div>
</div>
<script src="script.js">
  
    // script.js
// Wait for the DOM to fully load
document.addEventListener('DOMContentLoaded', (event) => {
  // Get all menu items
  const menuItems = document.querySelectorAll('.menu-item');
  
  // Add click event to each menu item
  menuItems.forEach(item => {
    item.addEventListener('click', function() {
      // Remove 'active' class from all menu items
      menuItems.forEach(subItem => {
        subItem.classList.remove('active');
      });
      // Add 'active' class to the clicked menu item
      this.classList.add('active');
    });
  });
});
</script>

</body>
</html>
