<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Passengers</title>
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
		<h1>Passengers</h1>

		<div id="back">
			<a href="./airport-main.php"><b><u><- BACK TO MAIN MENU</b></u></a>
		</div>

		<br>

		<!-- Form to take in flight number, uses POST to hide values -->
		<h2>Lookup Airline</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Flight Number: <input type="text" name="flightNo">
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
				if(empty($_POST['flightNo'])){	//$_POST super variable returns whatever is stored at index
												// key. Here, it takes flightno as that is the name of the
												//input field above.
					echo "An error has occured."; //echo just displays the message on the webpage itself.
				}
				else{
					//Get flightno from form above.
					$flightNo = $_POST['flightNo'];

					//Create a connection to the database.
					$conn = connectDatabase();

					//If the connection fails, report an error and terminate the script using die().
					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						$airName = "An error has occured";
						die();
					}

					//Create 2 variables to hold sql queries. The first grabs the column names
					//from the flights table, and the second returns all columns where the flightNo
					//is equivalent to what was entered in the form.
					$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'passengers'";
					$sql2 = "SELECT * FROM passengers WHERE FlightNo = $flightNo";

					//Execute queries, and store results in columns and result.
					$columns = mysqli_query($conn, $sql1);
					$result = mysqli_query($conn, $sql2);

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
		
	</body>
</html>