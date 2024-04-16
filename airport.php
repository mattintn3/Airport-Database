<!DOCTYPE html>
<html>
	<body>
		<h1>PROTOTYPE OF AIRPORT MANAGEMENT SYSTEM</h1>

		<h2>Lookup Flight No</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			FlightNo: <input type="text" name="flightno">
			<input type="submit">
		</form>

		<?php
			$flightNo = "";

			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(empty($_POST['flightno'])){
					echo "An error has occured.";
				}
				else{
					$servername = "localhost";
					$username = "root";
					$password = "";
					$database = "airportmanagement";
					$flightNo = $_POST['flightno'];
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
				}
			}

			echo "<br><br>";
		?>
	</body>
</html>