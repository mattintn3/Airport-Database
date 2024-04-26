//Function to validate if a flight number was entered.
function validateFlightNum(){
	let formField = document.getElementById("flightnum").value.trim();
	let errorMessage = document.getElementById("errorMessage");

	if(formField === ""){
		alert("ERROR: Flight Number is required.")
		errorMessage.innerHTML = "All fields are required.";
		return false;
	}
	else{
		errorMessage.innerHTML = "";
		return true;
	}
}