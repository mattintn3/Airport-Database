function validateForm(elementId){
	let formField = document.getElementById(elementId).value.trim();
	let errorMessage = document.getElementById("errorMessage");

	if(formField === ""){
		alert("ERROR: All fields are required.")
		errorMessage.innerHTML = "All fields are required.";
		return false;
	}
	else{
		errorMessage.innerHTML = "";
		return true;
	}
}