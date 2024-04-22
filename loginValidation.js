function showLoadingAnimation(event){
	event.preventDefault();
	let form = event.target;

	document.getElementById("loadingAnimation").style.display = 'flex';

	document.getElementById("feedback").innerHTML = "Logging In...";

	setTimeout(function() {
		form.submit();
	}, 5000);
	return false;
}