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
		<script type="text/javascript" src="./home.js"></script>

	</head>
	<body>
		<img src="https://experiencecle.com/wp-content/uploads/2020/06/bna-vert-lockup-rgb.png" alt="BNA" onclick="returnHome()">

		<!-- Header For Webpage -->
		<h1>Flights</h1>

		<ul id="navBar">
			<li class="topBar">
				<a href="./newMain.php">Home</a>
			</li>
			<li class="topBar" id="active">
				<a href="./newFlights.php">Flights</a>
			</li>
			<li class="topBar">
				<a href="./book-flight.php">Book A Flight</a>
			</li>
			<li class="topBar">
				<a href="./airport-admin.php">Administrator Login</a>
			</li>
		</ul>

		<h2>Select an Option</h2>

		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			<input type="submit" value="View All Flights" class="toggleButton">
		</form>

		<br>

		<?php
			if($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST['flightnum']) && empty($_POST['dest']) && empty($_POST['aName'])){
				//If the flightNo field is empty, report an error
				//Get flightno from form above.

				//Create a connection to the database.
				echo "<script>console.log('Connecting to Database... ')</script>";

				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights' AND COLUMN_NAME IN ('AirlineName', 'FlightNo', 'Origin', 'Destination', 'SeatsRemaining')";
				$sql2 = "SELECT AirlineName, FlightNo, Origin, Destination, SeatsRemaining FROM flights";

					//Execute queries, and store results in columns and result.
					$columns = mysqli_query($conn, $sql1);

					$stmt = $conn->prepare($sql2);
					$stmt->execute();

					$result = $stmt->get_result();

					//If the result is NULL (no flight num assigned), report an error.
					if($result->num_rows == 0){
						echo "<br><p>No Flights Found!</p>";
					}

					else{
						//Create a table, and create the table header for the flight table results.
						echo "<div class='table' style='display: none;'>";
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
						echo "</div>";

						echo "<script> $(document).ready(function() { $('.table').slideToggle('slow'); }); </script>";
					}

					$conn->close();
			}
			
			//2 newlines.
			echo "<br>";
		?>

		<button type="button" class="toggleButton">Search For Flight By Flight Number</button>
		<div class="form" style="display: none;">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
				Flight Number: <input type="number" name="flightnum" class="field">* <br> <br>
				<input type="submit" class="submit"> <br>
				</div>
			</form>
		</div>

		<br>

		<?php
			if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['flightnum'])){
				//Get flightno from form above.
				$flightNo = $_POST['flightnum'];

				//Create a connection to the database.
				echo "<script>console.log('Connecting to Database... ')</script>";

				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				//Create 2 variables to hold sql queries. The first grabs the column names
				//from the flights table, and the second returns all columns where the flightNo
				//is equivalent to what was entered in the form.
				$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights' AND COLUMN_NAME IN ('AirlineName', 'FlightNo', 'Origin', 'Destination', 'SeatsRemaining')";
				$sql2 = "SELECT AirlineName, FlightNo, Origin, Destination, SeatsRemaining FROM flights WHERE FlightNo = ?";

				//Execute queries, and store results in columns and result.
				$columns = mysqli_query($conn, $sql1);

				$stmt = $conn->prepare($sql2);
				$stmt->bind_param("i", $flightNo);
				$stmt->execute();

				$result = $stmt->get_result();

				//If the result is NULL (no flight num assigned), report an error.
				if($result->num_rows == 0){
					echo "<br><p>No Flights Found!</p>";
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
			}
			
			//2 newlines.
			echo "<br>";
		?>

		<button type="button" class="toggleButton">Search For Flight By Destination</button>
			<div class="form" style="display: none;">
				<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
					Destination: <input type="text" name="dest" class="field">* <br> <br>
					<input type="submit" class="submit"> <br>
					</div>
				</form>
			</div>

		<br>

		<?php
			
			if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['dest'])){
				//Get flightno from form above.
				$dest = $_POST['dest'];

				//Create a connection to the database.
				echo "<script>console.log('Connecting to Database... ')</script>";

				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				//Create 2 variables to hold sql queries. The first grabs the column names
				//from the flights table, and the second returns all columns where the flightNo
				//is equivalent to what was entered in the form.
				$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights' AND COLUMN_NAME IN ('AirlineName', 'FlightNo', 'Origin', 'Destination', 'SeatsRemaining')";
				$sql2 = "SELECT AirlineName, FlightNo, Origin, Destination, SeatsRemaining FROM flights WHERE Destination = ?";

				//Execute queries, and store results in columns and result.
				$columns = mysqli_query($conn, $sql1);

				$stmt = $conn->prepare($sql2);
				$stmt->bind_param("s", $dest);
				$stmt->execute();

				$result = $stmt->get_result();

				//If the result is NULL (no flight num assigned), report an error.
				if($result->num_rows == 0){
					echo "<br><p>No Flights Found!</p>";
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
			}
			
			//2 newlines.
			echo "<br>";
		?>

		<button type="button" class="toggleButton">Search For Flights By Airline</button>
			<div class="form" style="display: none;">
				<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
					Airline Name: <input type="text" name="aName" class="field">* <br> <br>
					<input type="submit" class="submit"> <br>
					</div>
				</form>
			</div>

		<br>

		<?php
			if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['aName'])){
				//Get flightno from form above.
				$aName = $_POST['aName'];

				//Create a connection to the database.
				echo "<script>console.log('Connecting to Database... ')</script>";

				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				//Create 2 variables to hold sql queries. The first grabs the column names
				//from the flights table, and the second returns all columns where the flightNo
				//is equivalent to what was entered in the form.
				$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights' AND COLUMN_NAME IN ('AirlineName', 'FlightNo', 'Origin', 'Destination', 'SeatsRemaining')";
				$sql2 = "SELECT AirlineName, FlightNo, Origin, Destination, SeatsRemaining FROM flights WHERE AirlineName = ?";

				//Execute queries, and store results in columns and result.
				$columns = mysqli_query($conn, $sql1);

				$stmt = $conn->prepare($sql2);
				$stmt->bind_param("s", $aName);
				$stmt->execute();

				$result = $stmt->get_result();

				//If the result is NULL (no flight num assigned), report an error.
				if($result->num_rows == 0){
					echo "<br><p>No Flights Found!</p>";
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
			}
			
			//2 newlines.
			echo "<br>";
		?>

		<br> <br>

		<a href="./book-flight.php" class="toggleButton" style="text-decoration: none;">Book A Flight</a>
	</body>
</html>