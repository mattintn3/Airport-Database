<?php require '../Scripts/session-manager.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Admin Home</title>
		<link href="../Assets/bna-icon.jpeg" type="image/x-icon" rel="icon">
		<link href="../Stylesheets/styles.css" type="text/css" rel="stylesheet">
		<link href="../Stylesheets/flightStyle.css" type="text/css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script type="text/javascript" src="../Scripts/adminHome.js"></script>
		<script type="text/javascript" src="../Scripts/logout.js"></script>

	</head>
	<body>
		<img src="../Assets/bna-logo.png" alt="BNA" onclick="adminHome()">

		<!-- Header For Webpage -->
		<h1>Administrator Tools</h1>
		<div id="logout">
			<a href="#" id="logout-link" style="text-decoration: none; color: white;"><- Logout</a>
		</div>

		<br>

		<ul id="navBar">
			<li class="topBar">
				<a href="./admin-home.php" id="active">Admin Home</a>
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
			<li class="topBar">
				<a href="./admin-staff.php">Staff</a>
			</li>
		</ul>

		<h2>Please Select an Option</h2> <br>

		<a href="./admin-airlines.php" class="toggleButton" style="text-decoration: none;">Airlines</a> <br> <br> <br> <br>
		<a href="./admin-flights.php" class="toggleButton" style="text-decoration: none;">Flights</a> <br> <br> <br> <br>
		<a href="./admin-passengers.php" class="toggleButton" style="text-decoration: none;">Passengers</a> <br> <br> <br> <br>
		<a href="./admin-staff.php" class="toggleButton" style="text-decoration: none;">Staff</a>

		<?php
			if(!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== TRUE){
				header("Location: ../airport-admin.php");
				die();
			}

			require 'connectDatabase.php';

			if($_SERVER['REQUEST_METHOD'] == "POST"){
				if(empty($_POST['username']) || empty($_POST['password'])){
					echo "Username and Password are required. <br>";
				}
				else{
					//Get username and password from above form.
					$username = $_POST['username'];
					$password = $_POST['password'];

					echo "<script>console.log('Connecting to Database... ')</script>";

					//sleep(5);

					//Create a connection to the database.
					$conn = connectDatabase();

					//If the connection fails, report an error and terminate the script using die().
					if($conn->connect_error){
						echo "Connection failed: " . $conn->connect_error . "<br>";
						die();
					}

					echo "<script>console.log('Querying Database... ')</script>";

					//sleep(5);

					//Create 2 variables to hold sql queries. The first grabs the column names
					//from the flights table, and the second returns all columns where the flightNo
					//is equivalent to what was entered in the form.
					$sql = "SELECT * FROM admin WHERE Username = '$username' AND AdminPass = '$password'";

					//Execute queries, and store results in columns and result.
					$result = mysqli_query($conn, $sql);
					//$resultArray = mysqli_fetch_assoc($result);

					echo "<script>console.log('SUCCESS.')</script>";

					//If the result is NULL (no flight num assigned), report an error.
					if($result->num_rows == 0){
						echo "Invalid Username or Password <br>";
					}
					else{
						echo "SUCCESSFUL!! <br>";
					}

					$conn->close();
				}
			}
			
			//2 newlines.
			echo "<br><br>";
		?>

	</body>
</html>