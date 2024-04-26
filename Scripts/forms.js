//Function to toggle sliding animation when a toggleButton element is clicked.
$(document).ready(function(){
	$(".toggleButton").click(function(){
		$(this).next(".form").slideToggle("slow");
	});
});
