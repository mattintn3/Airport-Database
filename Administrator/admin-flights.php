<?php
	require '../Scripts/session-manager.php';
	require '../Scripts/connectDatabase.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) and File Imports -->
		<title>Admin Flights</title>
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
		<h1>Administrator Tools: Flights</h1>
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
			<li class="topBar" id="active">
				<a href="./admin-flights.php">Flights</a>
			</li>
			<li class="topBar">
				<a href="./admin-passengers.php">Passengers</a>
			</li>
			<li class="topBar">
				<a href="./admin-staff.php">Staff</a>
			</li>
		</ul>

		<h2>Please Select an Option</h2>

		<!-- Form to view all flights -->
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			<input type="submit" value="View All Flights" class="toggleButton">
		</form>

		<?php

			//Checks is logged in, if not, return to login and abort the script.
			if(!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== TRUE){
				header("Location: ./airport-admin.php");
				die();
			}

			if($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST['aName']) && empty($_POST['passengers']) && empty($_POST['dest']) && empty($_POST['flightno'])){
				echo "<script>console.log('Connecting to Database... ')</script>";

				//Create a connection to the database.
				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				echo "<script>console.log('Querying Database... ')</script>";

				$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights'";
				$sql2 = "SELECT * FROM flights";

				//Execute queries, and store results in columns and result.
				$columns = mysqli_query($conn, $sql1);
				
				$stmt = $conn->prepare($sql2);
				$stmt->execute();

				$result = $stmt->get_result();

				echo "<script>console.log('SUCCESS.')</script>";

				//If the result returns no rows (no flights), report an error.
				//Otherwise display results as a table.
				if($result->num_rows == 0){
					echo "<p>No Flights Found!</p><br>";
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

		<!-- Form to add a flight -->
		<button type="button" class="toggleButton">Add a Flight</button>
			<div class="form" style="display: none;">
				<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
					Airline Name: <input type="text" name="aName" class="field">* <br> <br>
					Number of Passengers: <input type="number" name="passengers" class="field">* <br> <br>
					Destination: <input type="text" name="dest" class="field">* <br> <br>
					<input type="submit" class="submit"> <br>
					</div>
				</form>
			</div>

			<?php
				if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['aName']) && !empty($_POST['passengers']) && !empty($_POST['dest'])){
					$aName = $_POST['aName'];
					$passengers = $_POST['passengers'];
					$dest = $_POST['dest'];
					$remaining = $passengers;

					echo "<script>console.log('Connecting to Database... ')</script>";
	
					//Create a connection to the database.
					$conn = connectDatabase();
	
					//If the connection fails, report an error and terminate the script using die().
					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						die();
					}
	
					//Run query.
					echo "<script>console.log('Querying Database... ')</script>";
	
					$sqlMax = "SELECT MAX(FlightNo) AS MaxFlight FROM flights";
					$maxResult = mysqli_query($conn, $sqlMax);
					$maxAssoc = mysqli_fetch_assoc($maxResult);
					$flightNoNew = $maxAssoc["MaxFlight"] + 1;

					$sql = "INSERT INTO flights VALUES (?, ?, ?, 'Nashville', ?, ?)";

					$stmt = $conn->prepare($sql);
					$stmt->bind_param("siisi", $aName, $flightNoNew, $passengers, $dest, $remaining);

					//If the query is executed successfully, return a success message.
					//Otherwise, report an error.
					if($stmt->execute()){
						echo "<p>Flight Added Successfully!</p><br>";						}
					else{
						echo "<p>An unknown error has occured, please try again.</p><br>";
					}
	
					$conn->close();
			}
				
				echo "<br><br>";
			?>

			<!-- Form to remove a flight -->
			<button type="button" class="toggleButton">Remove a Flight</button>
				<div class="form" style="display: none;">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
						Flight Number: <input type="text" name="flightno" class="field">* <br> <br>
						<input type="submit" class="submit"> <br>
						</div>
					</form>
				</div>

			<?php
				if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['flightno'])){
					$flightno = $_POST['flightno'];

					echo "<script>console.log('Connecting to Database... ')</script>";
	
					//Create a connection to the database.
					$conn = connectDatabase();
	
					//If the connection fails, report an error and terminate the script using die().
					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						die();
					}
	
					echo "<script>console.log('Querying Database... ')</script>";
	
					$sqlCheck = "SELECT FlightNo FROM flights WHERE FlightNo = ?";
	
					//Execute queries, and store results in columns and result.
					$checkStmt = $conn->prepare($sqlCheck);
					$checkStmt->bind_param("i", $flightno);
					$checkStmt->execute();
					$checkQuery = $checkStmt->get_result();
					$result = mysqli_fetch_assoc($checkQuery);
	
					echo "<script>console.log('SUCCESS.')</script>";
	
					//If the result is NULL (no airline exists), report an error.
					//Otherwise, run the query to remove the airline and report success.
					if($result == NULL){
						echo "<p>Airline Doesn't Exists!</p><br>";
					}
					else{
						$sql = "DELETE FROM flights WHERE FlightNo = ?";

						$stmt = $conn->prepare($sql);
						$stmt->bind_param("s", $flightno);

						if($stmt->execute()){
							echo "<p>Flight Removed Successfully!</p><br>";
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