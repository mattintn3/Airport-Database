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

		<script type="text/javascript" src="./formValidation.js"></script>
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
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onsubmit="return validateFlightNum()">
			Flight Number: <input type="number" name="flightnum" id="flightnum">
			<input type="submit">
		</form>

		<span id="errorMessage" style="color: red;"></span>

		<!-- NOTICE THE FORMAT OF HTML, (Element names enclosed in <>), this will be useful for
		understanding a lot of what it going on below -->

		<?php
			require 'connectDatabase.php';

			if($_SERVER['REQUEST_METHOD'] == "POST"){
				//If the flightNo field is empty, report an error
				if(empty($_POST['flightnum'])){	//$_POST super variable returns whatever is stored at index
												// key. Here, it takes flightno as that is the name of the
												//input field above.
					//echo "An error has occured."; //echo just displays the message on the webpage itself.
				}
				else{
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
					$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'passengers'";
					$sql2 = "SELECT * FROM passengers WHERE FlightNo = ?";

					$columns = mysqli_query($conn, $sql1);

					$stmt = $conn->prepare($sql2);

					$stmt->bind_param("i", $flightNo);
					$stmt->execute();

					//Execute queries, and store results in columns and result.
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
		
	</body>
</html>