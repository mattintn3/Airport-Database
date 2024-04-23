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
		<img src="https://experiencecle.com/wp-content/uploads/2020/06/bna-vert-lockup-rgb.png">

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

		<h2>Available Flights</h2>

		<?php
				//Create a connection to the database.
				echo "<script>console.log('Connecting to Database... ')</script>";

				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights' AND COLUMN_NAME IN ('AirlineName', 'FlightNo', 'Origin', 'Destination', 'SeatsRemaining')";
				$sql2 = "SELECT AirlineName, FlightNo, Origin, Destination, SeatsRemaining FROM flights WHERE SeatsRemaining > 0";

					//Execute queries, and store results in columns and result.
					$columns = mysqli_query($conn, $sql1);

					$stmt = $conn->prepare($sql2);
					$stmt->execute();

					$result = $stmt->get_result();

					//If the result is NULL (no flight num assigned), report an error.
					if($result->num_rows == 0){
						echo "<br><p>No Flights Available At This Time...</p>";
					}

					else{
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
					}

					$conn->close();
			
			//2 newlines.
			echo "<br>";
		?>

		<h2>Which Flight Would You Like To Book?</h2>

		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Flight Number: <input type="number" name="flightnum" class="field">* <br> <br>
			<input type="submit" class="submit"> <br>
		</form>

		<br>

		<?php
			session_start();

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

				//If the result is NULL (no flight num assigned), report an error.
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
		?>


	</body>
</html>