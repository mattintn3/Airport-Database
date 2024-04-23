<?php require 'connectDatabase.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <title>New Homepage</title>
        <link href="./styles.css" type="text/css" rel="stylesheet">
		<link href="./flightStyle.css" type="text/css" rel="stylesheet">
    </head>

    <body>
        <img src="https://experiencecle.com/wp-content/uploads/2020/06/bna-vert-lockup-rgb.png" style="width: 381px; height: 303px;">
        <h1 style="font-family: Copperplate, fantasy; text-align: center;">Welcome to BNA</h1>

        <br>

        <ul id="navBar">
            <li class="topBar" id="active">
                <a href="./newMain.php">Home</a>
            </li>
            <li class="topBar">
                <a href="./newFlights.php">Flights</a>
            </li>
			<li class="topBar">
				<a href="./book-flight.php">Book A Flight</a>
			</li>
            <li class="topBar">
                <a href="./airport-admin.php">Administrator Login</a>
            </li>
        </ul>

        <h2>Enter Your Destination</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
            Destination: <input type="text" name="destination" class="field"> <br> <br>
            <input type="submit" class="submit">
        </form>

        <?php
			session_start();

            if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['destination'])) {
                $destination = $_POST['destination'];

				$conn = connectDatabase();

				if($conn->connect_error){
					echo "Connection failed: " . $conn->connect_error . "<br>";
					die();
				}

				$sql = "SELECT AirlineName, FlightNo, Origin, Destination, SeatsRemaining FROM flights WHERE Destination = ?";

				$stmt = $conn->prepare($sql);
				$stmt->bind_param("s", $destination);
				$stmt->execute();

				$result = $stmt->get_result();

				if($result->num_rows == 0){
					echo "<br><p>Flight Not Found.</p>";
					$conn->close();
				}
				else{
					$_SESSION['searchResults'] = $destination;
					header("Location: ./search-results.php");
					$conn->close();
					die();
				}
            }
        ?>
    </body>
</html>