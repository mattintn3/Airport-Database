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
		<script type="text/javascript" src="../Scripts/homeEx.js"></script>

	</head>
	<body>
		<img src="../Assets/bna-logo.png" alt="BNA" onclick="returnHomeEx()">

		<!-- Title Card and Navigation Bar -->
		<h1>Book A Flight</h1>

		<ul id="navBar">
			<li class="topBar">
				<a href="../newMain.php" id="active">Home</a>
			</li>
			<li class="topBar">
				<a href="./newFlights.php">Flights</a>
			</li>
			<li class="topBar">
				<a href="./book-flight.php">Book A Flight</a>
			</li>
			<li class="topBar">
				<a href="../Administrators/airport-admin.php">Administrator Login</a>
			</li>
		</ul>

		<h2>Available Flights</h2>
		<br>

		<?php
			//Start the session
			session_start();

			//If a search has not been performed or the flight number field was left empty, return to the homepage.
			//Otherwise, return the search results.
			if(!isset($_SESSION['searchResults']) && empty($_POST['flightnum'])){
				header("Location: ../newMain.php");
				die();
			}

			if(isset($_SESSION['searchResults'])) {

				$dest = $_SESSION['searchResults'];
				$destPerm = $dest;

				//Create a connection to the database.
				echo "<script>console.log('Connecting to Database... ')</script>";

				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights' AND COLUMN_NAME IN ('AirlineName', 'FlightNo', 'Origin', 'Destination', 'SeatsRemaining')";
				$sql2 = "SELECT AirlineName, FlightNo, Origin, Destination, SeatsRemaining FROM flights WHERE Destination = ? AND SeatsRemaining > 0";

				$columns = mysqli_query($conn, $sql1);

				$stmt = $conn->prepare($sql2);
				$stmt->bind_param("s", $dest);
				$stmt->execute();

				$result = $stmt->get_result();

				//Create a table, and create the table header for the flight table results.
				echo "<br> <table border='5'>"; //Some of this is HTML code
				echo "<tr>";
				while($row = mysqli_fetch_assoc($columns)){
					foreach($row as $value){
						echo "<th>" . $value . "</th>"; // . is used for string concatenation.
					}
				}
				echo "</tr>"; //End the row
				//Fetch the data for the result of the query, and display it as a table.
				while($row = mysqli_fetch_assoc($result)){
					echo "<tr>";
					foreach ($row as $value){
						echo "<td>" . $value . "</td>";
					}
					echo "</tr>";
				}
				//End table
				echo "</table><br>";
				$conn->close();
			}
			echo "<br>";
			

		?>

		<!-- Form to make a booking based on flight number from search results. -->
		<h3>If you're ready to make a booking, enter a valid flight number below!</h3>

		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Flight Number: <input type="number" name="flightnum" class="field">* <br> <br>
			<input type="submit" class="submit" value="Book It!"> <br>
		</form>

		<br>

		<?php

			//If a search has not been performed or the flight number field was left empty, return to the homepage.
			//Otherwise, create a booking if the user desires.
			if(!isset($_SESSION['searchResults']) && empty($_POST["flightnum"])){
				header("Location: ../newMain.php");
				die();
			}

			if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['flightnum'])){

				$flightNo = $_POST['flightnum'];

				//Create a connection to the database.
				echo "<script>console.log('Connecting to Database... ')</script>";

				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				$sql = "SELECT SeatsRemaining FROM flights WHERE FlightNo = ?";

				$stmt = $conn->prepare($sql);
				$stmt->bind_param("i", $flightNo);
				$stmt->execute();

				$result = $stmt->get_result();
				$row = mysqli_fetch_assoc($result);

				//If the result returns no rows (invalid flight num), report an error.
				//If there are no seats remaining on a flight, report an error.
				//If a valid flight number is entered, redirect to get-passenger.php and
				//save the flight number in the session global variable and kill the script.
				if($result->num_rows == 0){
					echo "<br><p>Invalid Flight Number... Please Try Again</p>";
				}
				else if($row['SeatsRemaining'] == 0){
					echo "<br><p>Sorry! This Flight Is Full!</p>";
				}
				else{
					$_SESSION['bookingFlight'] = $flightNo;
					header("Location: ./get-passenger.php");
					$conn->close();
					die();
				}

				$conn->close();
			
				echo "<br>";
			}
			else{
				session_destroy();
			}
		?>


	</body>
</html>