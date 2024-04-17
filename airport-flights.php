<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Airport Management Database - WIP</title>
	</head>
	<body>
		<!-- Header For Webpage -->
		<h1>PROTOTYPE OF AIRPORT MANAGEMENT SYSTEM</h1>

		<!-- Form to take in flight number, uses POST to hide values -->
		<h2>Lookup Flight No</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			FlightNo: <input type="text" name="flightno">
			<input type="submit">
		</form>

		<!-- NOTICE THE FORMAT OF HTML, (Element names enclosed in <>), this will be useful for
		understanding a lot of what it going on below -->

		<?php
			//Create flightNo variable
			$flightNo = "";

			//Function because otherwise this will be redundant code lol.
			function connectDatabase(){
				//Create variables for server name, username for database, password (default is none)
				//and database name. PHP Variables begin withn a $.
				$servername = "localhost";
				$username = "root";
				$password = "";
				$database = "airportmanagement";

				$conn = new mysqli($servername, $username, $password, $database);

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					$flightNo = "An error has occured";
					die();
				}

				//Report a successful connection.
				echo "Connection Successful!<br>";

				return $conn;
			}

			
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
					$conn = connectDatabase();

					//If the connection fails, report an error and terminate the script using die().
					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						$flightNo = "An error has occured";
						die();
					}

					//Report a successful connection.
					echo "Connection Successful!<br>";

					//Create 2 variables to hold sql queries. The first grabs the column names
					//from the flights table, and the second returns all columns where the flightNo
					//is equivalent to what was entered in the form.
					$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights'";
					$sql2 = "SELECT * FROM flights WHERE FlightNo = $flightNo";

					//Execute queries, and store results in columns and result.
					$columns = mysqli_query($conn, $sql1);
					$result = mysqli_query($conn, $sql2);

					//If the result is NULL (no flight no assigned), report an error.
					if($result == NULL){
						echo "ERROR";
					}

					//Create a table, and create the table header for the flight table results.
					echo "<table border='1'>"; //Some of this is HTML code
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

					$conn->close()
				}
			}
			
			//2 newlines.
			echo "<br><br>";
		?>

		<h2>Add a Flight</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Airline Name: <input type="text" name="airName"> <br>
			FlightNo: <input type="text" name="flightNew"> <br>
			Number of Passengers: <input type="text" name="numPass"> <br>
			Number of Crew: <input type="text" name="numCrew"> <br>
			Origin: <input type="text" name="origin"> <br>
			Destination: <input type="text" name="dest"> <br>
			<input type="submit">
		</form>

		<?php
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				//If the flightNo field is empty, report an error
				if(empty($_POST['flightNew'])){
					echo "An error has occured.";
				}
				else{
					$airName = $_POST['airName'];
					$flightNoNew = $_POST['flightNew'];
					$numPass = $_POST['numPass'];
					$numCrew = $_POST['numCrew'];
					$origin = $_POST['origin'];
					$dest = $_POST['dest'];

					$conn = connectDatabase();

					//$colQuery = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights'";
					$sql = "INSERT INTO flights VALUES ('$airName', $flightNoNew, $numPass, $numCrew, '$origin', '$dest')";
					//$sqlGet = "SELECT * FROM flights WHERE FlightNo = $flightNoNew";
					
					if($conn->query($sql) === TRUE){
						echo "New Flight Successfully Inserted <br>";
					}
					else{
						echo "Error Occurred... Please Try Again... <br>";
					}

					/*$resultNew = mysqli_query($conn, $sql);
					$columns = mysqli_query($conn, $colQuery);
					//$resultNew = mysqli_query($conn, $sqlGet);
					if(!$resultNew){
						echo "FATAL ERROR: QUERY NOT EXECUTED...<br>";
					}

					echo "<table border='1'>";
					echo "<tr>";
					while($row = mysqli_fetch_assoc($columns)){
						foreach($row as $value){
							echo "<th>" . $value . "</th>";
						}
					echo "</tr>";

					while($row = mysqli_fetch_assoc($resultNew))
						echo "<tr>";
						foreach($row as $value){
							echo "<td>" . $value . "</td>";
						}
						echo "</tr>";
					}
					echo "</table> <br>";*/
					
					$conn->close();

					/*$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights'";
					$sql2 = "SELECT * FROM flights WHERE FlightNo = $flightNo";



					$columns = mysqli_query($conn, $sql1);
					$result = mysqli_query($conn, $sql2);*/
				}
			}
		?>

	</body>
</html>