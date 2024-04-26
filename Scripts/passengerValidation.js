//Function to show booking flight animation.
function showPassengerAnimation(event){
	event.preventDefault();
	let form = event.target;

	document.getElementById("loadingAnimation").style.display = 'flex';

	document.getElementById("feedback").innerHTML = "Booking Flight...";

	setTimeout(function() {
		form.submit();
	}, 2000);
	return false;
}