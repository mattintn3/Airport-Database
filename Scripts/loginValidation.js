//Function to display the login animation.
function showLoadingAnimation(event){
	event.preventDefault();
	let form = event.target;

	document.getElementById("loadingAnimation").style.display = 'flex';

	document.getElementById("feedback").innerHTML = "Logging You In...";

	setTimeout(function() {
		form.submit();
	}, 2000);
	return false;
}