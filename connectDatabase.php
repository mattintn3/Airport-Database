<?php
	function connectDatabase(){
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "airportmanagement";

		$conn = new mysqli($servername, $username, $password, $database);

		//If the connection fails, report an error and terminate the script using die().
		if($conn->connect_error){
			echo "Connection failed: " . $conn->connect_error . "<br>";
			$flightNo = "An error has occured";
			die();
		}

		//Report a successful connection.
		echo "Connection Successful!<br>";

		return $conn;
	}
?>