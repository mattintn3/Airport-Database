<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Admin Home</title>
		<link href="./styles.css" type="text/css" rel="stylesheet">
		<link href="./flightStyle.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="./home.js"></script>

	</head>
	<body>
		<img src="https://experiencecle.com/wp-content/uploads/2020/06/bna-vert-lockup-rgb.png" alt="BNA" onclick="returnHome()">

		<!-- Header For Webpage -->
		<h1>Welcome to the Admin Dashboard!</h1>
		<div id="logout">
			<a href="newMain.php" class="logout"><- Logout</a>
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
				<a href="./admin-passengers.php">Passengers</a>
			</li>
			<li class="topBar">
				<a href="./super-admin">Super Admin Settings</a>
			</li>
		</ul>

		<?php
			session_start();

			if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE){
				session_destroy();
			}
			else{
				header("Location: airport-admin.php");
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