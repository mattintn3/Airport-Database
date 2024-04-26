<?php require '../Scripts/connectDatabase.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) and File Imports -->
		<title>Flights</title>
		<link href="../Assets/bna-icon.jpeg" type="image/x-icon" rel="icon">
		<link href="../Stylesheets/flightStyle.css" type="text/css" rel="stylesheet">
		<link href="../Stylesheets/styles.css" type="text/css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script type="text/javascript" src="../Scripts/forms.js"></script>
		<script type="text/javascript" src="../Scripts/loginValidation.js"></script>
		<script type="text/javascript" src="../Scripts/home.js"></script>

	</head>
	<body>
		<img src="../Assets/bna-logo.png" alt="BNA" onclick="returnHome()">

		<!-- Title Card and Navigation Bar -->
		<h1>Administrator Logout</h1>

		<ul id="navBar">
			<li class="topBar">
				<a href="../newMain.php">Home</a>
			</li>
			<li class="topBar">
				<a href="../Booking/newFlights.php">Flights</a>
			</li>
			<li class="topBar">
				<a href="../Booking/book-flight.php">Book A Flight</a>
			</li>
			<li class="topBar">
				<a href="./airport-admin.php">Administrator Login</a>
			</li>
		</ul>

		<p>You Have Been Logged Out.</p>

		<br>

		<?php
			//If logged in, destroy logged in session.
			//Otherwise, redirect to homepage.
			session_start();

			if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE){
				session_destroy();
			}
			else{
				header("Location: ../newMain.php");
				die();
			}
		?>


	</body>
</html>