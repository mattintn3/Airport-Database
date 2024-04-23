<?php require 'connectDatabase.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <title>New Homepage</title>
        <link href="./styles.css" type="text/css" rel="stylesheet">
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
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onclick="return validateFlightNum()">
            Destination: <input type="text" name="destination">
            <input type="submit">
        </form>

        <span id="errorMessage" style="color: red;"></span>
        <?php
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                if(empty($_POST['destination'])) {
                    echo "An error has occurred.";
                } else {
                    $destination = $_POST['destination'];

                    echo "<script>console.log('Connecting to database... ')</script>";
                    $conn = connectDatabase();

                    if ($conn->connect_error) {
                        echo "Connection failed: " . $conn->connect_error . "<br>";
                        die();
                    }

                    $sql1 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'flights'";
                    $sql2 = "SELECT * FROM flights WHERE Destination = ? AND Origin = 'Nashville'";

                    $columns = mysqli_query($conn, $sql1);

                    $stmt = $conn->prepare($sql2);
                    $stmt->bind_param("s", $destination);  // Change to 's' if $destination is a string
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 0) {
                        echo "No flights found.";
                        die();
                    }

                    echo "<table border='5'>";
                    echo "<tr>";
                    while($row = mysqli_fetch_assoc($columns)) {
                        foreach($row as $value) {
                            echo "<th>" . $value . "</th>";
                        }
                    }

                    echo "</tr>";

                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        foreach($row as $value) {
                            echo "<td>" . $value . "</td>";
                        }
                        echo "</tr>";
                    }

                    echo "</table><br>";

                    $conn->close();
                }
            }
        ?>
    </body>
</html>