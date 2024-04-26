<?php require '../Scripts/connectDatabase.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
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

		<!-- Header For Webpage -->
		<h1>Book A Flight</h1>

		<ul id="navBar">
			<li class="topBar">
				<a href="../newMain.php">Home</a>
			</li>
			<li class="topBar">
				<a href="./newFlights.php">Flights</a>
			</li>
			<li class="topBar" id="active">
				<a href="./book-flight.php">Book A Flight</a>
			</li>
			<li class="topBar">
				<a href="../Administrator/airport-admin.php">Administrator Login</a>
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
				header("Location: ../newMain.php");
				die();
			}
		?>


	</body>
</html>