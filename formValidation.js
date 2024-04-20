function validateFlightNum(){
	let flightNum = document.getElementById("flightnum").value.trim();
	let errorMessage = document.getElementById("errorMessage");

	if(flightNum === ""){
		alert("ERROR: Flight Number is required.")
		errorMessage.innerHTML = "Flight Number is required.";
		return false;
	}
	else{
		errorMessage.innerHTML = "";
		return true;
	}
}