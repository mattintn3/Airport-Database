<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Airlines</title>
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
	</head>
	<body>
		<!-- Header For Webpage -->
		<h1>Airlines!</h1>

		<div id="back">
			<a href="./airport-main.php"><b><u><- BACK TO MAIN MENU</b></u></a>
		</div>

		<br>

		<!-- Form to take in flight number, uses POST to hide values -->
		<h2>Lookup Airline</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Airline Name: <input type="text" name="airName">*
			<input type="submit">
		</form>

		<?php
			require 'connectDatabase.php';

			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(empty($_POST['airName'])){
					echo "An error has occured.";
				}
				else{
					//Get flightno from form above.
					$airName = $_POST['airName'];

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
					$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'airlines'";
					$sql2 = "SELECT * FROM airlines WHERE AirlineName = ?";

					//Execute queries, and store results in columns and result.
					$columns = mysqli_query($conn, $sql1);

					$stmt = $conn->prepare($sql2);
					$stmt->bind_param("s", $airName);
					$stmt->execute();

					$result = $stmt->get_result();

					//If the result is NULL (no flight num assigned), report an error.
					if($result->num_rows == 0){
						echo "ERROR";
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

		<h2>Add Airline</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Airline Name: <input type="text" name="newAirName">*
			<input type="submit">
		</form>

		<?php
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(empty($_POST['newAirName'])){
					echo "Please enter an airline name!";
				}
				else{
					$newAirName = $_POST["newAirName"];

					echo "<script>console.log('Connecting to Database... ')</script>";

					$conn = connectDatabase();

					$sqlCheck = "SELECT AirlineName FROM airlines WHERE AirlineName = ?";
					
					$stmt = $conn->prepare($sqlCheck);
					$stmt->bind_param("s", $newAirName);
					$stmt->execute();

					$checkQuery = $stmt->get_result();
					$check = mysqli_fetch_assoc($checkQuery);

					if($check != NULL){
						echo "Airline Already Exists! <br>";
					}
					else{
						$sql = "INSERT INTO airlines VALUES ('$newAirName', 0)";

						if($conn->query($sql) === TRUE){
							echo "New Airline Added Successfully! <br>";
						}
						else{
							echo "An error has occured... <br>";
						}
					}

					$conn->close();
				}
			}
		?>

		<br> <br>

		<h2>Remove Airline</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Airline Name:<input type="text" name="remAirName">*
			<input type="submit">
		</form>

		

		<?php
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				if(empty($_POST['remAirName'])){
					echo "Please enter an airline name!";
				}
				else{
					$remAirName = $_POST["remAirName"];

					echo "<script>console.log('Connecting to Database... ')</script>";

					$conn = connectDatabase();

					$sqlCheck = "SELECT AirlineName FROM airlines WHERE AirlineName = '$remAirName'";

					$checkQuery = mysqli_query($conn, $sqlCheck);
					$check = mysqli_fetch_assoc($checkQuery);

					if($check == NULL){
						echo "Airline Doesn't Exist! <br>";
					}
					else{
						$flightSql = "SELECT FlightNo FROM flights WHERE AirlineName = ?";
						$sql = "DELETE FROM airlines WHERE AirlineName = '$remAirName'";

						 $stmt = $conn->prepare($flightSql);
						 $stmt->bind_param("s", $flightSql);
						 $stmt->execute();

						$flightNums = $stmt->get_result();

						if($flightNums != NULL){
							while($row = mysqli_fetch_assoc($flightNums)){
								foreach($row as $value){
									$sqlRem = "DELETE FROM flights WHERE FlightNo = $value";
									$staffSql = "SELECT FlightNo FROM staff WHERE FlightNo = $value";
									$passSql = "SELECT FlightNo FROM passengers WHERE FlightNo = $value";
									$staffRem = "DELETE FROM staff WHERE FlightNo = $value";
									$passRem = "DELETE FROM passengers WHERE FlightNo = $value";

									$staffCheck = mysqli_query($conn, $staffSql);
									$passCheck = mysqli_query($conn, $passSql);

									if($conn->query($sqlRem) === TRUE){
										echo "Flight Deleted <br>";
									}
									
									if($staffCheck != NULL){
										$conn->query($staffRem);
									}
									if($passCheck != NULL){
										$conn->query($passRem);
									}

								}
							}
						}

						if($conn->query($sql) === TRUE){
							echo "Airline Removed Successfully! <br>";
						}
						else{
							echo "An error has occured... <br>";
						}
					}

					$conn->close();
				}
			}

		?>

	</body>
</html>