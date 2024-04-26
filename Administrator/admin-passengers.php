<?php
	require '../Scripts/session-manager.php';
	require '../Scripts/connectDatabase.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) and File Imports -->
		<title>Admin Passengers</title>
		<link href="../Assets/bna-icon.jpeg" type="image/x-icon" rel="icon">
		<link href="../Stylesheets/styles.css" type="text/css" rel="stylesheet">
		<link href="../Stylesheets/flightStyle.css" type="text/css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script type="text/javascript" src="../Scripts/forms.js"></script>
		<script type="text/javascript" src="../Scripts/adminHome.js"></script>
		<script type="text/javascript" src="../Scripts/logout.js"></script>

	</head>
	<body>
		<img src="../Assets/bna-logo.png" alt="BNA" onclick="adminHome()">

		<!-- Title Card and Navigation Bar -->
		<h1>Administrator Tools: Passengers</h1>
		<div id="logout">
			<a href="#" id="logout-link" style="text-decoration: none; color: white;"><- Logout</a>
		</div>

		<br>

		<ul id="navBar">
			<li class="topBar">
				<a href="./admin-home.php" id=>Admin Home</a>
			</li>
			<li class="topBar">
				<a href="./admin-airlines.php">Airlines</a>
			</li>
			<li class="topBar">
				<a href="./admin-flights.php">Flights</a>
			</li>
			<li class="topBar" id="active">
				<a href="./admin-passengers.php">Passengers</a>
			</li>
			<li class="topBar">
				<a href="./admin-staff.php">Staff</a>
			</li>
		</ul>

		<h2>Please Select an Option</h2>

		<!-- Form to view all passengers -->
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			<input type="submit" value="View All Passengers" class="toggleButton">
		</form>

		<?php

			//If not logged in, return to the login screen
			if(!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== TRUE){
				header("Location: ./airport-admin.php");
				die();
			}

			if($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST['ssn'])){
				echo "<script>console.log('Connecting to Database... ')</script>";

				//Create a connection to the database.
				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				echo "<script>console.log('Querying Database... ')</script>";

				$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'passengers'";
				$sql2 = "SELECT * FROM passengers";

				//Execute queries, and store results in columns and result.
				$columns = mysqli_query($conn, $sql1);
				
				$stmt = $conn->prepare($sql2);
				$stmt->execute();

				$result = $stmt->get_result();

				echo "<script>console.log('SUCCESS.')</script>";

				//If the result returns no rows (no passengers exist), report an error.
				//Otherwise, return the query result as a table.
				if($result->num_rows == 0){
					echo "<p>No Passengers Found!</p><br>";
				}
				else{
					echo "<div class='table' style='display: none;'>";
					echo "<table border='5'>";
					echo "<tr>";
					while($row = mysqli_fetch_assoc($columns)){
						foreach($row as $value){
							echo "<th>" . $value . "</th>";
						}
					}
					echo "</tr>";

					while($row = mysqli_fetch_assoc($result)){
						echo "<tr>";
						foreach($row as $value){
							echo "<td>" . $value . "</td>";
						}
						echo "</tr>";
					}

					echo "</table> <br>";
					echo "</div>";

					echo "<script> $(document).ready(function(){ $('.table').slideToggle('slow'); }); </script>";
				}

				$conn->close();
		}
			
			echo "<br>";
		?>

			<!-- Form to cancel bookings for a passenger -->
			<button type="button" class="toggleButton">Cancel Booking For Passenger</button>
				<div class="form" style="display: none;">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
						SSN: <input type="number" name="ssn" class="field">* <br> <br>
						<input type="submit" class="submit"> <br>
						</div>
					</form>
				</div>

			<?php
				if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['ssn'])){
					$ssn = $_POST['ssn'];

					echo "<script>console.log('Connecting to Database... ')</script>";
	
					//Create a connection to the database.
					$conn = connectDatabase();
	
					//If the connection fails, report an error and terminate the script using die().
					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						die();
					}
	
					echo "<script>console.log('Querying Database... ')</script>";
	
					$sqlCheck = "SELECT SSN FROM passengers WHERE SSN = ?";
	
					//Execute queries, and store results in columns and result.
					$checkStmt = $conn->prepare($sqlCheck);
					$checkStmt->bind_param("i", $ssn);
					$checkStmt->execute();
					$checkQuery = $checkStmt->get_result();
					$result = mysqli_fetch_assoc($checkQuery);
	
					echo "<script>console.log('SUCCESS.')</script>";
	
					//If the result is NULL (passenger doesn't exist), report an error.
					//Otherwise, execute queries to remove a passenger and add a seat to the flight
					//they were on and report a success.
					if($result == NULL){
						echo "<p>Passenger Doesn't Exists!</p><br>";
					}
					else{
						$sql1 = "DELETE FROM passengers WHERE SSN = ?";
						$sql2 = "SELECT FlightNo FROM passengers WHERE SSN = ?";
						$sql3 = "UPDATE flights SET SeatsRemaining = SeatsRemaining + 1 WHERE FlightNo = ?";

						$flightNoStmt = $conn->prepare($sql2);
						$flightNoStmt->bind_param("i", $ssn);
						$flightNoStmt->execute();
						$resultFlightStmt = $flightNoStmt->get_result();
						$flightAssoc = mysqli_fetch_assoc($resultFlightStmt);
						$flightNoRem = $flightAssoc['FlightNo'];

						$stmt = $conn->prepare($sql1);
						$stmt->bind_param("i", $ssn);

						if($stmt->execute()){
							echo "<p>Booking Cancelled Successfully!</p><br>";

							$updateStmt = $conn->prepare($sql3);
							$updateStmt->bind_param("i", $flightNoRem);
							$updateStmt->execute();
						}
						else{
							echo "<p>An unknown error has occured, please try again.</p><br>";
						}
					}
	
					$conn->close();
			}
				
				echo "<br><br>";
			?>

	</body>
</html>