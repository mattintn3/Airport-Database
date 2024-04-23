$(document).ready(function(){
	$(".toggleButton").click(function(){
		$(this).next(".form").slideToggle("slow");
	});
});
