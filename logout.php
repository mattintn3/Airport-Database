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
		<script type="text/javascript" src="./home.js"></script>

	</head>
	<body>
		<img src="https://experiencecle.com/wp-content/uploads/2020/06/bna-vert-lockup-rgb.png" alt="BNA" onclick="returnHome()">

		<!-- Header For Webpage -->
		<h1>Administrator Logout</h1>

		<ul id="navBar">
			<li class="topBar">
				<a href="./newMain.php">Home</a>
			</li>
			<li class="topBar">
				<a href="./newFlights.php">Flights</a>
			</li>
			<li class="topBar">
				<a href="./book-flight.php">Book A Flight</a>
			</li>
			<li class="topBar">
				<a href="./airport-admin.php">Administrator Login</a>
			</li>
		</ul>

		<p>You Have Been Logged Out.</p>

		<br>

		<?php
			session_start();

			if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE){
				session_destroy();
			}
			else{
				header("Location: newMain.php");
				die();
			}
		?>


	</body>
</html>