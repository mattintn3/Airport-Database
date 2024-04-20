<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Flights</title>
		<style>
			body{
				background-color: lightgrey;
				text-align: center;
			}
			table{
				margin-left: auto;
				margin-right: auto;
			}
			#back{
				text-align: left;
			}
		</style>

		<script type="text/javascript" src="./formValidation.js"></script>
	</head>
	<body>
		<!-- Header For Webpage -->
		<h1>PROTOTYPE OF AIRPORT MANAGEMENT SYSTEM</h1>

		<br>
		<div id="back">
			<a href="./airport-main.php"><b><u><- BACK TO MAIN MENU</b></u></a>
		</div>

		<!-- Form to take in flight number, uses POST to hide values -->
		<h2>Lookup Flight No</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onclick="return validateFlightNum()">
			FlightNo: <input type="number" name="flightno" id="flightnum">*
			<input type="submit">
		</form>

		<!-- NOTICE THE FORMAT OF HTML, (Element names enclosed in <>), this will be useful for
		understanding a lot of what it going on below -->

		<?php
			require 'connectDatabase.php';
			
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				//If the flightNo field is empty, report an error
				if(empty($_POST['flightno'])){	//$_POST super variable returns whatever is stored at index
												// key. Here, it takes flightno as that is the name of the
												//input field above.
					echo "An error has occured."; //echo just displays the message on the webpage itself.
				}
				else{
					//Get flightno from form above.
					$flightNo = $_POST['flightno'];

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
					$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights'";
					$sql2 = "SELECT * FROM flights WHERE FlightNo = ?";

					//Execute queries, and store results in columns and result.
					$columns = mysqli_query($conn, $sql1);

					$stmt = $conn->prepare($sql2);
					$stmt->bind_params("i", $flightNo);
					$stmt->execute();

					$result = $stmt->get_result();

					//If the result is NULL (no flight num assigned), report an error.
					if($result->num_rows == 0){
						echo "ERROR";
						die();
					}

					//Create a table, and create the table header for the flight table results.
					echo "<table border='5'>"; //Some of this is HTML code
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
			}
			
			//2 newlines.
			echo "<br><br>";
		?>

		<h2>Add a Flight</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Airline Name: <input type="text" name="airName">* <br>
			<!--FlightNo: <input type="text" name="flightNew"> <br>-->
			Number of Passengers: <input type="number" name="numPass">* <br>
			Origin: <input type="text" name="origin">* <br>
			Destination: <input type="text" name="dest">* <br>
			<input type="submit">
		</form>

		<?php
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(empty($_POST['airName'])||empty($_POST['numPass'])||empty($_POST['numCrew'])||empty($_POST['origin'])||empty($_POST['dest'])){
                    echo "Please fill out <b><u>ALL</b></u> fields. <br>";
                }
				else {
					$airName = $_POST['airName'];
					//$flightNoNew = $_POST['flightNew'];
					$numPass = $_POST['numPass'];
					$origin = $_POST['origin'];
					$dest = $_POST['dest'];
	
					echo "<script>console.log('Connecting to Database... ')</script>";

					$conn = connectDatabase();
	
					//$colQuery = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights'";
					$sqlMax = "SELECT MAX(FlightNo) AS MaxFlight FROM flights";
					$maxResult = mysqli_query($conn, $sqlMax);
					$maxAssoc = mysqli_fetch_assoc($maxResult);
					$flightNoNew = $maxAssoc["MaxFlight"] + 1;
					//$sql = "INSERT INTO flights VALUES ('$airName', $flightNoNew, $numPass, '$origin', '$dest', $numPass)";
					$sql = "INSERT INTO flights VALUES (?, ?, ?, ?, ?, ?)";
					//$sqlGet = "SELECT * FROM flights WHERE FlightNo = $flightNoNew";
					
					$stmt = $conn->prepare($sql);
					$stmt->bind_param("siissi", $airName, $flightNoNew, $numPass, $origin, $dest, $numPass);

					/*if($conn->query($sql) === TRUE){*/
					if($stmt->execute() === TRUE){
						echo "New flight successfully inserted <br>";
					}
					else{
						echo "Error occurred... Please try again... <br>";
					}
						
					$conn->close();
				}
			}
		?>

		<h2> Delete a Flight </h2>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Flight Number: <input type="number" name="flightnum">*
			<input type="submit">
		</form>

		<?php
			if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['flightnum'])) {
				if(!empty($_POST['flightnum'])){
					echo "Please enter a flight number...";
				}
				else{

					$flightnum = $_POST['flightnum'];
					
					echo "<script>console.log('Connecting to Database... ')</script>";

					$conn = connectDatabase();

					$sql = "DELETE FROM flights WHERE FlightNo = ?";
					$stmt = $conn->prepare($sql);
					$stmt->bind_param("i", $flightnum);
					
					if($stmt->execute() === TRUE) {
						echo "Flight " . $flightnum . " has been deleted successfully.<br>";
					} else {
						echo "Error occured: " . $conn->error . "<br>";
					}
					$conn->close();
				}
			}
			?>
	</body>
</html>