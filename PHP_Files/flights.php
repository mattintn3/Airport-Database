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
					$sql2 = "SELECT AirlineName, FlightNo, Origin, Destination FROM flights WHERE FlightNo = ?";

					//Execute queries, and store results in columns and result.
					$columns = mysqli_query($conn, $sql1);

					$stmt = $conn->prepare($sql2);
					$stmt->bind_param("i", $flightNo);
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
			echo "<br>";
		?>