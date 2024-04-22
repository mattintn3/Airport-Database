<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Admin Tools</title>
		<link href="./adminLogin.css" type="text/css" rel="stylesheet">

		<script type="text/javascript" src="./loginValidation.js"></script>
	</head>
	<body>
		<!-- Header For Webpage -->
		<h1>Administrator Tools</h1>

		<div id="back">
			<a href="./newMain.php"><b><u><- BACK TO MAIN MENU</b></u></a>
		</div>

		<br>

		<!-- Form to take in flight number, uses POST to hide values -->
		<h2>Administrator Login</h2>
		<h4>NOTE: You MUST be an administrator on this server to proceed.</h3>
		<h4>If you are an administrator and need help logging in, please talk to your supervisor.</h3>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onsubmit="return showLoadingAnimation(event)">
			Username: <input type="text" name="username">* <br>
			Password: <input type="password" name="password">* <br> <br>
			<input type="submit" value="Login" class="login">

			<br>

			<span id="feedback"></span>
			<div id="loadingAnimation" style="display: none;">
				<div class="dot"></div>
				<div class="dot"></div>
				<div class="dot"></div>
			</div>
		</form>

		<?php
			session_start();

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
					$sql = "SELECT * FROM admin WHERE Username = ? AND AdminPass = ?";

					$stmt = $conn->prepare($sql);
					$stmt->bind_param("ss", $username, $password);
					$stmt->execute();

					//Execute queries, and store results in columns and result.
					$result = $stmt->get_result();
					//$resultArray = mysqli_fetch_assoc($result);

					echo "<script>console.log('SUCCESS.')</script>";

					//If the result is NULL (no flight num assigned), report an error.
					if($result->num_rows == 0){
						echo "Invalid Username or Password <br>";
						$conn->close();
					}
					else{
						$_SESSION['loggedin'] = true;
						header("Location: ./admin-home.php");
						$conn->close();
						die();
					}
				}
			}
		?>

	</body>
</html>