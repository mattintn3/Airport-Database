function showLoadingAnimation(event){
	event.preventDefault();
	let form = event.target;

	document.getElementById("loadingAnimation").style.display = 'flex';

	document.getElementById("feedback").innerHTML = "Please Wait...";

	setTimeout(function() {
		form.submit();
	}, 2000);
	return false;
}