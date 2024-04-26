<?php
	//This script manages the administrator login session time.
	//Automatically logs user out if there has been no activity for 5 minutes.
	session_start();

		$inactive = 300; //5 Minutes

		if(isset($_SESSION['timeout'])){
			$session_life = time() - $_SESSION['timeout'];
			if($session_life > $inactive){
				session_destroy();
				header("Location: logout.php");
				die();
			} 
		}
?>