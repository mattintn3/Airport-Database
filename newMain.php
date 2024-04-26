<?php require './Scripts/connectDatabase.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <!-- Title of webpage (appears in tab name) and File Imports -->
        <title>BNA Homepage</title>
        <link href="./Assets/bna-icon.jpeg" type="image/x-icon" rel="icon">
        <link href="./Stylesheets/styles.css" type="text/css" rel="stylesheet">
		<link href="./Stylesheets/flightStyle.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="./Scripts/home.js"></script>
    </head>

    <body>
        <!-- Title Card and Navigation Bar -->
        <img src="./Assets/bna-logo.png" alt="BNA" onclick="returnHome()">
        <h1>Welcome to BNA</h1>

        <ul id="navBar">
            <li class="topBar" id="active">
                <a href="./newMain.php">Home</a>
            </li>
            <li class="topBar">
                <a href="./Booking/newFlights.php">Flights</a>
            </li>
			<li class="topBar">
				<a href="./Booking/book-flight.php">Book A Flight</a>
			</li>
            <li class="topBar">
                <a href="./Administrator/airport-admin.php">Administrator Login</a>
            </li>
        </ul>

        <!-- Form to Look up Flights -->
        <h2>Lookup Flights</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
            Destination: <input type="text" name="destination" class="field"> <br> <br>
            <input type="submit" class="submit">
        </form>

        <?php
            //Start the session
			session_start();


            if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['destination'])) {
                //Get form value.
                $destination = $_POST['destination'];

                //Establish a database connection
				$conn = connectDatabase();

				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

                //Query database
				$sql = "SELECT AirlineName, FlightNo, Origin, Destination, SeatsRemaining FROM flights WHERE Destination = ?";

				$stmt = $conn->prepare($sql);
				$stmt->bind_param("s", $destination);
				$stmt->execute();

				$result = $stmt->get_result();

                //If flights are found, return flights found on search-results.php.
				if($result->num_rows == 0){
					echo "<br><p>No Flights Found.</p>";
					$conn->close();
				}
				else{
					$_SESSION['searchResults'] = $destination;
					header("Location: ./Booking/search-results.php");
					$conn->close();
					die();
				}
            }
        ?>
    </body>
</html>