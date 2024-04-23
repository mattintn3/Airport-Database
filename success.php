<?php require 'connectDatabase.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Flights</title>
		<link href="./flightStyle.css" type="text/css" rel="stylesheet">
		<link href="./styles.css" type="text/css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script type="text/javascript" src="./forms.js"></script>
		<script type="text/javascript" src="./loginValidation.js"></script>

	</head>
	<body>
		<!-- Header For Webpage -->
		<h1>Book A Flight</h1>

		<ul id="navBar">
			<li class="topBar">
				<a href="./newMain.php">Home</a>
			</li>
			<li class="topBar">
				<a href="./newFlights.php">Flights</a>
			</li>
			<li class="topBar" id="active">
				<a href="./book-flight.php">Book A Flight</a>
			</li>
			<li class="topBar">
				<a href="./airport-admin.php">Administrator Login</a>
			</li>
		</ul>

		<p>Flight Booked!</p>
		<h2>Thank You!</h2>

		<br>

		<?php
			session_start();

			if(isset($_SESSION['flightBooked']) && $_SESSION['flightBooked'] === TRUE){
				session_destroy();
			}
			else{
				header("Location: ./newMain.php");
				die();
			}
		?>


	</body>
</html>