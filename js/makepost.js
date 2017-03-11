/*
 * Validates the make a post window.
 */
window.onload = function() {
	document.getElementById("post-form").onsubmit = function(event) {
		
		// get the form fields
		var title = document.getElementById("title");
		var body = document.getElementById("body");


		// check if all fields are filled in
		if (title.value == "" || body.value == "") {
			event.preventDefault();

			if (title.value == "") {
				title.className += " missing";
			} 

			if (body.value == "") {
				body.className += " missing";
			} 

			alert("Check that both the title and body are filled in.");
		
		}
	}

	// reset background colour of input when user types
	var elements = document.getElementsByClassName("required");

	for (var i = 0; i < elements.length; i++) {
		elements[i].onkeydown = function Timmy(event) {
			var source = event.target || event.srcElement;
			source.classList.remove("missing");
		};
	}
}