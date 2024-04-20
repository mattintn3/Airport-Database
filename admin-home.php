<!DOCTYPE html>
<html>
	<head>
		<!-- Title of webpage (appears in tab name) -->
		<title>Admin Home</title>
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
		<h1>Welcome to the Admin Dashboard!</h1>

		<div id="back">
			<a href="./airport-main.php"><b><u><- LOGOUT</b></u></a>
		</div>

		<br>

		<!-- Form to take in flight number, uses POST to hide values
		<h2>Administrator Login</h2>
		<h4>NOTE: You MUST be an administrator on this server to proceed.</h3>
		<h4>If you are an administrator and need help logging in, please talk to your supervisor.</h3>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Username: <input type="text" name="username">* <br>
			Password: <input type="password" name="password">* <br>
			<input type="submit" value="Login">
		</form> -->

		<?php
			session_start();

			if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE){
				session_destroy();
			}
			else{
				header("Location: airport-admin.php");
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