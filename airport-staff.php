<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Staff</title>
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
		<h1>Staff</h1>

		<div id="back">
			<a href="./airport-main.php"><b><u><- BACK TO MAIN MENU</b></u></a>
		</div>

		<br>

		<!-- Form to take in flight number, uses POST to hide values -->
		<h2>View Staff On Flight</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Flight Number: <input type="number" name="flightNum" id="flightnum">
			<input type="submit">
		</form>

		<!-- NOTICE THE FORMAT OF HTML, (Element names enclosed in <>), this will be useful for
		understanding a lot of what it going on below -->

		<?php
			require 'connectDatabase.php';
			
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				//If the flightNo field is empty, report an error
				if(empty($_POST['flightNum'])){	//$_POST super variable returns whatever is stored at index
												// key. Here, it takes flightno as that is the name of the
												//input field above.
					echo "An error has occured."; //echo just displays the message on the webpage itself.
				}
				else{
					//Get flightno from form above.
					$flightNum = $_POST['flightNum'];

					echo "<script>console.log('Connecting to Database... ')</script>";

					//Create a connection to the database.
					$conn = connectDatabase();

					//If the connection fails, report an error and terminate the script using die().
					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						die();
					}

					//Create 2 variables to hold sql queries. The first grabs the column names
					//from the flights table, and the second returns all columns where the flightNo
					//is equivalent to what was entered in the form.
					$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'staff'";
					$sql2 = "SELECT * FROM staff WHERE FlightNo = ?";

					//Execute queries, and store results in columns and result.
					$columns = mysqli_query($conn, $sql1);

					$stmt = $conn->prepare($sql2);
					$stmt->bind_param("i", $flightNum);
					$stmt->execute();

					$result = stmt->get_result();

					//If the result is NULL (no flight num assigned), report an error.
					if($result->num_rows == 0){
						echo "ERROR";
					}

					else{
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
					}

					$conn->close();
				}
			}
			
			//2 newlines.
			echo "<br><br>";
		?>

		<h2>View Staff Member</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Employee ID: <input type="number" name="companyEmpID">*
			<input type="submit">
		</form>

		<?php
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(empty($_POST['companyEmpID'])){
					echo "Please enter an Employee ID. <br>";
				}
				else{
					$companyEmpID = $_POST['companyEmpID'];

					echo "<script>console.log('Connecting to Database... ')</script>";

					$conn = connectDatabase();

					$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'staff'";
					$sql2 = "SELECT * FROM staff WHERE EmployeeID = ?";

					$columns = mysqli_query($conn, $sql1);

					$stmt = $conn->prepare($sql2);
					$stmt->bind_param("i", $companyEmpID);
					$stmt->execute();

					$result = stmt->get_result();

					if($result->num_rows == 0){
						echo "Employee Does Not Exist! <br>";
					}
					else{
						echo "<table border='5'";
						echo "<tr>";
						
						while($row = mysqli_fetch_assoc($columns)){
							foreach($row as $value){
								echo "<th>" . $value . "</th>";
							}
						}
						echo "</tr>";

						while($row = mysqli_fetch_assoc($result)){
							echo "<tr>";
							foreach($row as $value){
								echo "<td>" . $value . "</td>";
							}
							echo "</tr>";
						}
						echo "</table>";
					}

					$conn->close();

				}
			}


		?>

		<h2>View All Staff Within Airline Company</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Airline Name: <input type="text" name="airNameStaff">*
			<input type="submit">
		</form>

		<?php
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				if(empty($_POST['airNameStaff'])){
					"Please Enter An Airline Name. <br>";
				}
				else{
					$airNameStaff = $_POST['airNameStaff'];

					echo "<script>console.log('Connecting to Database... ')</script>";

					$conn = connectDatabase();

					$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'staff'";
					$sql2 = "SELECT * FROM staff WHERE AirlineName = ?";

					$columns = mysqli_query($conn, $sql1);

					$stmt = $conn->prepare($sql2);
					$stmt->bind_param("s", $airNameStaff);
					$stmt->execute();

					$result = $stmt->get_result();

					if($result->num_rows == 0){
						echo "No Employees Within Airline Company or Airline Company Does Not Exist! <br>";
					}
					else{
						echo "<table border='5'>";
						echo "<tr>";

						while($row = mysqli_fetch_assoc($columns)){
							foreach($row as $value){
								echo "<th>" . $value . "</th>";
							}
						}
						echo "</tr>";

						while($row = mysqli_fetch_assoc($result)){
							echo "<tr>";
							foreach($row as $value){
								echo "<td>" . $value . "</td>";
							}
							echo "</tr>";
						}
					}
					
					$conn->close();
				}
			}



		?>

		<h2>Add Staff</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			First Name: <input type="text" name="fname">* <br>
			Last Name: <input type="text" name="lname">* <br>
			Employee ID: <input type="text" name="empID">* <br>
			Airline Name: <input type="text" name="airName">* <br>
			Flight Number: <input type="text" name="newFlightNum">* <br>
			<input type="submit">
		</form>

		<?php
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				if(empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['empID']) || empty($_POST['airName']) || empty($_POST['newFlightNum'])){
					echo "Please Fill Out <b><u>ALL</b></u> Fields <br>";
				}
				else{
					$fname = $_POST['fname'];
					$lname = $_POST['lname'];
					$empID = $_POST['empID'];
					$airName = $_POST['airName'];
					$newFlightNum = $_POST['newFlightNum'];

					echo "<script>console.log('Connecting to Database... ')</script>";

					$conn = connectDatabase();

					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						die();
					}

					$sqlCheck = "SELECT FlightNo FROM flights WHERE FlightNo = $newFlightNum";

					$checkQuery = mysqli_query($conn, $sqlCheck);
					$check = mysqli_fetch_assoc($checkQuery);

					if($check == NULL){
						echo "ERROR: Flight Number Doesn't Exist! <br>";
					}
					else{
						//$sql = "INSERT INTO staff VALUES ('$fname', '$lname', $empID, '$airName', $newFlightNum)";
						$sql = "INSERT INTO staff VALUES (?, ?, ?, ?, ?)";

						$stmt = $conn->prepare($sql);
						$stmt->bind_param("ssisi", $fname, $lname, $empID, $airName, $newFlightNum);

						if($stmt->execute() === TRUE){
							echo "New Staff Member Added Successfully! <br>";
						}
					}

					$conn->close();
				}
			}
		?>
		
		<h2>Remove Staff</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Employee ID: <input type="text" name="remEmpID">*
			<input type="submit">
		</form>

		<?php
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(empty($_POST['remEmpID'])){
					echo "Please enter an Employee ID! <br>";
				}
				else{
					$remEmpID = $_POST['remEmpID'];

					echo "<script>console.log('Connecting to Database... ')</script>";

					$conn = connectDatabase();

					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						die();
					}
		
					$sqlCheck = "SELECT EmployeeID FROM staff WHERE EmployeeID = ?";

					$stmt = $conn->prepare($sqlCheck);
					$stmt->bind_params("i", $remEmpID);
					$stmt->execute();

					$checkQuery = $stmt->get_result();
					$check = mysqli_fetch_assoc($checkQuery);

					if($check == NULL){
						echo "Employee Does Not Exist! <br>";
					}
					else{
						$sql = "DELETE FROM staff WHERE EmployeeID = $remEmpID";

						if($conn->query($sql) === TRUE){
							echo "Staff Member Successfully Removed. <br>";
						}
					}

					$conn->close();
				}
			}
		?>

	</body>
</html>