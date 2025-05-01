<?php
session_start(); // Start the session at the very beginning
if (!isset($_SESSION['admin_id'])) {
  header('Location: log-in.php');
  exit;
}
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

#main-content {
  position: relative; /* Set the main content to relative */
}

.maintenance-container {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover; /* Ensure it covers the entire content area */
  background-position: center;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10; /* Ensure it's above other content */
}
.overview-card p {
  color: #555;
}

.menu-item.logout {
  color: #ff4d4d; /* Example color for log-out button */
}

/* Adjust other properties as needed */

/* Add more styles as per your design requirements */


/* Add more styles for your content and adjust the sidebar as needed */

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
  <li class="menu-item"><a href="Officer_Dashboard.php"><i class='bx bx-home-alt'></i> Dashboard</a></li>
  <li class="menu-item"><a href="history.php"><i class='bx bx-history'></i> History</a></li>
  <li class="menu-item"><a href="membersdata.php"><i class='bx bx-data'></i>Members Data</a></li>
  <li class="menu-item active"><a href="#"><i class='bx bx-calculator'></i> Cost Computation</a></li>
  <li class="menu-item logout"><a href="logout.php"><i class='bx bx-log-out'></i> Log Out</a></li>


</ul>
<!-- ... -->

</aside>

<div id="main-content">
  <!-- Maintenance Image -->
  <div class="maintenance-container">
    <img src="imgs/ud1.png" alt="Under Maintenance" style="width:100%; height:auto;">
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
