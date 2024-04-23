<?php require 'connectDatabase.php'; ?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Book A Flight</title>
		<link href="./flightStyle.css" type="text/css" rel="stylesheet">
		<link href="./styles.css" type="text/css" rel="stylesheet">
		<link href="./passengers.css" type="text/css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script type="text/javascript" src="./forms.js"></script>
		<script type="text/javascript" src="./passengerValidation.js"></script>
		<script type="text/javascript" src="./home.js"></script>

	</head>
	<body>
		<img src="https://experiencecle.com/wp-content/uploads/2020/06/bna-vert-lockup-rgb.png" alt="BNA" onclick="returnHome()">

		<!-- Header For Webpage -->
		<h1>Book A Flight</h1>

		<ul id="navBar">
			<li class="topBar">
				<a href="./newMain.php">Home</a>
			</li>
			<li class="topBar">
				<a href="./newFlights.php">Flights</a>
			</li>
			<li class="topBar" id="active">
				<a href="./book-flight.php">Book A Flight</a>
			</li>
			<li class="topBar">
				<a href="./airport-admin.php">Administrator Login</a>
			</li>
		</ul>

		<h2>Please Enter Your Information Into <b><u>ALL</b></u> Fields</h2>

		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onsubmit="return showPassengerAnimation(event)">
			First Name: <input type="text" name="fname" class="field">* <br>
			Last Name: <input type="text" name="lname" class="field">* <br>
			Date Of Birth: <input type="text" name="dob" pattern="\d{4}-\d{2}-\d{2}" class="field">* <br>
			***DATE FORMAT MUST MATCH: YYYY-MM-DD*** <br>
			Last 4 of SSN: <input type="ssn" name="ssn" pattern="\d{4}" class="field">* <br> <br>
			<input type="submit" class="submit"> <br>

			<span id="feedback"></span>
			<div id="loadingAnimation" style="display: none;">
				<div class="dot"></div>
				<div class="dot"></div>
				<div class="dot"></div>
			</div>
		</form>

		<br>

		<?php
			session_start();

			if(!isset($_SESSION['bookingFlight']) && empty($_POST['fname']) && empty($_POST['lname']) && empty($_POST['dob']) && empty($_POST['ssn'])){
				header("Location: ./book-flight.php");
				die();
			}

			
			if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['dob']) && !empty($_POST['ssn'])){
				$fname = $_POST['fname'];
				$lname = $_POST['lname'];
				$dob = $_POST['dob'];
				$flightNo = $_SESSION['bookingFlight'];
				$ssn = $_POST['ssn'];
				
				//Create a connection to the database.
				echo "<script>console.log('Connecting to Database... ')</script>";

				$conn = connectDatabase();

				//If the connection fails, report an error and terminate the script using die().
				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				$sqlCheck = "SELECT ssn FROM passengers WHERE ssn = ?";

				$checkStmt = $conn->prepare($sqlCheck);
				$checkStmt->bind_param("i", $ssn);
				$checkStmt->execute();
				$checkQuery = $checkStmt->get_result();
				$check = mysqli_fetch_assoc($checkQuery);

				if($check != NULL){
					echo "<p>You are already booked for a flight!</p>";
				}
				else{
					$sql = "INSERT INTO passengers VALUES (?, ?, ?, ?, ?)";
					$stmt = $conn->prepare($sql);
					$stmt->bind_param("sssii", $fname, $lname, $dob, $flightNo, $ssn);

					if($stmt->execute()){
						$flightSql = "UPDATE flights SET SeatsRemaining = SeatsRemaining - 1 WHERE FlightNo = ?";

						$flightStmt = $conn->prepare($flightSql);
						$flightStmt->bind_param("i", $flightNo);
						$flightStmt->execute();

						$_SESSION['flightBooked'] = TRUE;
						header("Location: ./success.php");
						$conn->close();
						die();
					}
					else{
						echo "<p>Sorry! There was an error in your booking. Please try again later.</p>";
						session_destroy();
					}
				}

				$conn->close();
			
			}
			//2 newlines.
			echo "<br>";
		?>


	</body>
</html>