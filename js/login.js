/*
 * Validates the login form.
 */
window.onload = function() {
	document.getElementById("login-form").onsubmit = function(event) {

		// get the form fields
		var username = document.getElementById("username");
		var password = document.getElementById("password");

		// check that all fields are filled in
		if (username.value == "" || password.value == "") {
			event.preventDefault();

			if (username.value == "") {
				username.className += " missing";
			}

			if (password.value == "") {
				password.className += " missing";
			}

			alert("You must enter a username and a password.");
		}
	}


	// reset background colour when user types
	var elements = document.getElementsByClassName("required");

	for (var i = 0; i < elements.length; i++) {
		elements[i].onkeydown = function Timmy(event) {
			var source = event.target || event.srcElement;
			source.classList.remove("missing");
		};
	}
}