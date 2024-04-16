<!DOCTYPE html>
<html>
	<head>
		<!-- Title that goes where the tab name is -->
		<title>Create Flight</title>
	</head>

	<body>
		<!-- Create a header for the webpage -->
		<h1>PROTOTYPE OF AIRPORT MANAGEMENT SYSTEM - CREATE FLIGHT</h1>

		<!-- Create a form to enter new flight data -->
		<h2>Please Enter Data Into <u>ALL</u> Fields!</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Airline Name: <input type="text" name="airlinename">* <br>
			Number of Passengers: <input type="text" name="pass">* <br>
			Number of Crew Members: <input type="text" name="crew">* <br>
			Origin: <input type="text" name="origin">* <br>
			Destination: <input type="text" name="destination">* <br>
			<input type="submit">
		</form>

		<?php
			//Create variables for all fields and assign them an empty string to start.
			$airlineName = $passengers = $crewMembers = $origin = $destination = "";
			//Query to be used later to assign flight no to new flight automatically.
			$flightNo = "SELECT MAX(FlightNo) FROM flights";

			if($_SERVER['REQUEST_METHOD'] == "POST"){
				//Check if all fields have values.
				//If not, report the error and terminate the script using die().
				//If all are filled out, assign each value to its corresponding variable
				//defined above.
				if(empty($_POST['airlinename'])){
					echo "Airline name is required. <br>";
					die();
				}
				else{
					$airlineName = $_POST['airlinename'];
				}
				if(empty($_POST['pass'])){
					echo "Number of Passengers is required. <br>";
					die();
				}
				else{
					$passengers = $_POST['pass'];
				}
				if(empty($_POST['crew'])){
					echo "Number of Crew Members is required. <br>";
					die();
				}
				else{
					$crewMembers = $_POST['crew'];
				}
				if(empty($_POST['origin'])){
					echo "Origin is required. <br>";
					die();
				}
				else{
					$origin = $_POST['origin'];
				}
				if(empty($_POST['destination'])){
					echo "Destination is required. <br>";
					die();
				}
				else{
					$destination = $_POST['destination'];
				}

				//Below is a WIP.
				
				/*else{
					$servername = "localhost";
					$username = "root";
					$password = "";
					$database = "airportmanagement";
					$conn = new mysqli($servername, $username, $password, $database);

					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						$flightNo = "An error has occured";
					}
					echo "Connection Successful!<br>";

					$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights'";
					$sql2 = "SELECT * FROM flights WHERE FlightNo = $flightNo";

					$columns = mysqli_query($conn, $sql1);
					$result = mysqli_query($conn, $sql2);

					if($result == NULL){
						echo "ERROR";
					}

					echo "<table border='1'>";
					echo "<tr>";
					while($row = mysqli_fetch_assoc($columns)){
						foreach($row as $value){
							echo "<th>" . $value . "</th>";
						}
					}
					echo "</tr>";
					while($row = mysqli_fetch_assoc($result)){
						echo "<tr>";
						foreach ($row as $value){
							echo "<td>" . $value . "</td>";
						}
						echo "</tr>";
					}

					echo "</table><br>";
				}*/
			}

		?>
	</body>
</html>