<?php
	require '../Scripts/session-manager.php';
	require '../Scripts/connectDatabase.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) and File Imports -->
		<title>Admin Staff</title>
		<link href="../Assets/bna-icon.jpeg" type="image/x-icon" rel="icon">
		<link href="../Stylesheets/styles.css" type="text/css" rel="stylesheet">
		<link href="../Stylesheets/flightStyle.css" type="text/css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script type="text/javascript" src="../Scripts/forms.js"></script>
		<script type="text/javascript" src="../Scripts/adminHome.js"></script>
		<script type="text/javascript" src="../Scripts/logout.js"></script>

	</head>
	<body>
		<img src="../Assets/bna-logo.png" alt="BNA" onclick="adminHome()">

		<!-- Title Card and Navigation Bar -->
		<h1>Administrator Tools: Staff</h1>
		<div id="logout">
			<a href="#" id="logout-link" style="text-decoration: none; color: white;"><- Logout</a>
		</div>

		<br>

		<ul id="navBar">
			<li class="topBar">
				<a href="./admin-home.php" id=>Admin Home</a>
			</li>
			<li class="topBar">
				<a href="./admin-airlines.php">Airlines</a>
			</li>
			<li class="topBar">
				<a href="./admin-flights.php">Flights</a>
			</li>
			<li class="topBar">
				<a href="./admin-passengers.php">Passengers</a>
			</li>
			<li class="topBar" id="active">
				<a href="./admin-staff.php">Staff</a>
			</li>
		</ul>

		<h2>Please Select an Option</h2>

		<!-- Form to view all staff -->
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			<input type="submit" value="View All Staff" class="toggleButton">
		</form>

		<?php

			//If not logged in, return to the login screen.
			if(!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== TRUE){
				header("Location: ../airport-admin.php");
				die();
			}

			if($_SERVER['REQUEST_METHOD'] == "POST" && empty($_POST['fname']) && empty($_POST['lname']) && empty($_POST['empID']) && empty($_POST['aName']) && empty($_POST['flightno']) && empty($_POST['updateEmpID']) && empty($_POST['updateFlightno']) && empty($_POST['remEmpID'])){
				echo "<script>console.log('Connecting to Database... ')</script>";

				//Create a connection to the database.
				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				echo "<script>console.log('Querying Database... ')</script>";

				$sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'staff'";
				$sql2 = "SELECT * FROM staff";

				//Execute queries, and store results in columns and result.
				$columns = mysqli_query($conn, $sql1);
				
				$stmt = $conn->prepare($sql2);
				$stmt->execute();

				$result = $stmt->get_result();

				echo "<script>console.log('SUCCESS.')</script>";

				//If the result returns no rows (no passengers found), report an error.
				//Otherwise, return query result as a table.
				if($result->num_rows == 0){
					echo "<p>No Passengers Found!</p><br>";
				}
				else{
					echo "<div class='table' style='display: none;'>";
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

					echo "</table> <br>";
					echo "</div>";

					echo "<script> $(document).ready(function(){ $('.table').slideToggle('slow'); }); </script>";
				}

				$conn->close();
		}
			
			echo "<br>";
		?>

			<!-- Form to add staff -->
			<button type="button" class="toggleButton">Add Staff</button>
				<div class="form" style="display: none;">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
						First Name: <input type="text" name="fname" class="field">* <br> <br>
						Last Name: <input type="text" name="lname" class="field">* <br> <br>
						EmployeeID: <input type="number" name="empID" class="field">* <br> <br>
						Airline Employer: <input type="text" name="aName" class="field">* <br> <br>
						Flight Number Assigned: <input type="number" name="flightno" class="field">* <br> <br>
						<input type="submit" class="submit"> <br>
						</div>
					</form>
				</div>

			<?php
				if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['empID']) && !empty($_POST['aName']) && !empty($_POST['flightno'])){
					$fname = $_POST['fname'];
					$lname = $_POST['lname'];
					$empID = $_POST['empID'];
					$aName = $_POST['aName'];
					$flightno = $_POST['flightno'];

					echo "<script>console.log('Connecting to Database... ')</script>";
	
					//Create a connection to the database.
					$conn = connectDatabase();
	
					//If the connection fails, report an error and terminate the script using die().
					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						die();
					}
	
					echo "<script>console.log('Querying Database... ')</script>";
	
					$sqlCheck1 = "SELECT EmployeeID FROM staff WHERE EmployeeID = ?";
					$sqlCheck2 = "SELECT FlightNo FROM flights WHERE FlightNo = ?";
	
					//Execute queries, and store results in columns and result.
					$checkStmt = $conn->prepare($sqlCheck1);
					$checkStmt->bind_param("i", $empID);
					$checkStmt->execute();
					$checkQuery = $checkStmt->get_result();
					$result = mysqli_fetch_assoc($checkQuery);

					$checkFlight = $conn->prepare($sqlCheck2);
					$checkFlight->bind_param("i", $flightno);
					$checkFlight->execute();
					$checkFlightQuery = $checkFlight->get_result();
					$flightResult = mysqli_fetch_assoc($checkFlightQuery);
	
					echo "<script>console.log('SUCCESS.')</script>";
	
					//If the result is not NULL (employee already exists), report an error.
					//Otherwise, execute query to add staff and report success.
					if($result != NULL){
						echo "<p>Employee ID Already Exists!</p><br>";
					}
					else if($flightResult == NULL){
						echo "<p>Flight Number Does Not Exist!</p><br>";
					}
					else{
						$sql = "INSERT INTO staff VALUES (?, ?, ?, ?, ?)";

						$stmt = $conn->prepare($sql);
						$stmt->bind_param("ssisi", $fname, $lname, $empID, $aName, $flightno);

						if($stmt->execute()){
							echo "<p>Staff Added Successfully!</p><br>";
						}
						else{
							echo "<p>An unknown error has occured, please try again.</p><br>";
						}
					}
	
					$conn->close();
			}
				
				echo "<br><br>";
			?>

			<!-- Form to update a staff's assigned flight number -->
			<button type="button" class="toggleButton">Update Staff Member's Flight</button>
				<div class="form" style="display: none;">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
						EmployeeID: <input type="number" name="updateEmpID" class="field">* <br> <br>
						New Flight Number Assigned: <input type="number" name="updateFlightno" class="field">* <br> <br>
						<input type="submit" class="submit"> <br>
						</div>
					</form>
				</div>

			<?php
				if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['updateEmpID']) && !empty($_POST['updateFlightno'])){
					$updateEmpID = $_POST['updateEmpID'];
					$updateFlightno = $_POST['updateFlightno'];

					echo "<script>console.log('Connecting to Database... ')</script>";
	
					//Create a connection to the database.
					$conn = connectDatabase();
	
					//If the connection fails, report an error and terminate the script using die().
					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						die();
					}
	
					echo "<script>console.log('Querying Database... ')</script>";
	
					$sqlCheck1 = "SELECT EmployeeID FROM staff WHERE EmployeeID = ?";
					$sqlCheck2 = "SELECT FlightNo FROM flights WHERE FlightNo = ?";
	
					//Execute queries, and store results in columns and result.
					$checkStmt = $conn->prepare($sqlCheck1);
					$checkStmt->bind_param("i", $updateEmpID);
					$checkStmt->execute();
					$checkQuery = $checkStmt->get_result();
					$result = mysqli_fetch_assoc($checkQuery);

					$checkFlight = $conn->prepare($sqlCheck2);
					$checkFlight->bind_param("i", $updateFlightno);
					$checkFlight->execute();
					$checkFlightQuery = $checkFlight->get_result();
					$flightResult = mysqli_fetch_assoc($checkFlightQuery);
	
					echo "<script>console.log('SUCCESS.')</script>";
	
					//If the result is NULL (employee doesn't exists), report an error.
					//Otherwise, execute query to update staff member's assigned flight and report success.
					if($result == NULL){
						echo "<p>Staff Member Does Not Exist!</p><br>";
					}
					else if($flightResult == NULL){
						echo "<p>Flight Number Does Not Exist!</p><br>";
					}
					else{
						$sql = "UPDATE staff SET FlightNo = ? WHERE EmployeeID = ?";

						$stmt = $conn->prepare($sql);
						$stmt->bind_param("ii", $updateFlightno, $updateEmpID);

						if($stmt->execute()){
							echo "<p>Staff Flight Updated Successfully!</p><br>";
						}
						else{
							echo "<p>An unknown error has occured, please try again.</p><br>";
						}
					}
	
					$conn->close();
			}
				
				echo "<br><br>";
			?>

			<!-- Form to remove staff -->
			<button type="button" class="toggleButton">Remove Staff</button>
				<div class="form" style="display: none;">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
						Employee ID: <input type="number" name="remEmpID" class="field">* <br> <br>
						<input type="submit" class="submit"> <br>
						</div>
					</form>
				</div>

			<?php
				if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['remEmpID'])){
					$remEmpID = $_POST['remEmpID'];

					echo "<script>console.log('Connecting to Database... ')</script>";
	
					//Create a connection to the database.
					$conn = connectDatabase();
	
					//If the connection fails, report an error and terminate the script using die().
					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						die();
					}
	
					echo "<script>console.log('Querying Database... ')</script>";
	
					$sqlCheck = "SELECT EmployeeID FROM staff WHERE EmployeeID = ?";
	
					//Execute queries, and store results in columns and result.
					$checkStmt = $conn->prepare($sqlCheck);
					$checkStmt->bind_param("i", $remEmpID);
					$checkStmt->execute();
					$checkQuery = $checkStmt->get_result();
					$result = mysqli_fetch_assoc($checkQuery);
	
					echo "<script>console.log('SUCCESS.')</script>";
	
					//If the result is NULL (staff member doesn't exist), report an error.
					//Otherwise, execute query to remove staff member and report success.
					if($result == NULL){
						echo "<p>Staff Member Doesn't Exists!</p><br>";
					}
					else{
						$sql = "DELETE FROM staff WHERE EmployeeID = ?";

						$stmt = $conn->prepare($sql);
						$stmt->bind_param("i", $remEmpID);

						if($stmt->execute()){
							echo "<p>Staff Member Removed Successfully!</p><br>";
						}
						else{
							echo "<p>An unknown error has occured, please try again.</p><br>";
						}
					}
	
					$conn->close();
			}
				
				echo "<br><br>";
			?>

	</body>
</html>