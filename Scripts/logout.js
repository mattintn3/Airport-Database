//Function to log the user out of the administrator tools.
$(document).ready(function(){
	$("#logout-link").click(function(event) {
		event.preventDefault();
		window.location.href = "logout.php";
	});s
});