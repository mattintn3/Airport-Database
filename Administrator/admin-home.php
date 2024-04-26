<?php 
	require '../Scripts/session-manager.php'; 
	require '../Scripts/connectDatabase.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) and File Imports -->
		<title>Admin Home</title>
		<link href="../Assets/bna-icon.jpeg" type="image/x-icon" rel="icon">
		<link href="../Stylesheets/styles.css" type="text/css" rel="stylesheet">
		<link href="../Stylesheets/flightStyle.css" type="text/css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script type="text/javascript" src="../Scripts/adminHome.js"></script>
		<script type="text/javascript" src="../Scripts/logout.js"></script>

	</head>
	<body>
		<img src="../Assets/bna-logo.png" alt="BNA" onclick="adminHome()">

		<!-- Title Card and Navigation Bar -->
		<h1>Administrator Tools</h1>
		<div id="logout">
			<a href="#" id="logout-link" style="text-decoration: none; color: white;"><- Logout</a>
		</div>

		<br>

		<ul id="navBar">
			<li class="topBar">
				<a href="./admin-home.php" id="active">Admin Home</a>
			</li>
			<li class="topBar">
				<a href="./admin-airlines.php">Airlines</a>
			</li>
			<li class="topBar">
				<a href="./admin-flights.php">Flights</a>
			</li>
			<li class="topBar">
				<a href="./admin-passengers.php">Passengers</a>
			</li>
			<li class="topBar">
				<a href="./admin-staff.php">Staff</a>
			</li>
		</ul>

		<!-- Prompt to select an option -->
		<h2>Please Select an Option</h2> <br>

		<a href="./admin-airlines.php" class="toggleButton" style="text-decoration: none;">Airlines</a> <br> <br> <br> <br>
		<a href="./admin-flights.php" class="toggleButton" style="text-decoration: none;">Flights</a> <br> <br> <br> <br>
		<a href="./admin-passengers.php" class="toggleButton" style="text-decoration: none;">Passengers</a> <br> <br> <br> <br>
		<a href="./admin-staff.php" class="toggleButton" style="text-decoration: none;">Staff</a>

		<?php
			//If not logged in, return to the login screen and terminate the script.
			if(!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== TRUE){
				header("Location: ./airport-admin.php");
				die();
			}
		?>

	</body>
</html>