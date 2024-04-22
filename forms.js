$(document).ready(function(){
	$(".toggleButton").click(function(){
		$(this).next(".form").slideToggle("slow");
		//$(this).next(".form").toggle();
		/*$(this).text(function(i, text){
			return text === "Show Form" ? "Hide Form" : "Show Form";
		});*/
	});
});