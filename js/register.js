/*
 * Validates the registration form.
 */
window.onload = function() {
	document.getElementById("register-form").onsubmit = function(event) {
		
		// get the form fields
		var username = document.getElementById("username");
		var email = document.getElementById("email");
		var password = document.getElementById("password");
		var repeatpassword = document.getElementById("repeatpassword");

		// check if all fields are filled in
		if (username.value == "" || email.value == "" || password.value == "" || repeatpassword.value == "") {
			event.preventDefault();

			if (username.value == "") {
				username.className += " missing";
			} 

			if (email.value == "") {
				email.className += " missing";
			} 

			if (password.value == "") {
				password.className += " missing";
			}

			if (repeatpassword.value == "") {
				repeatpassword.className += " missing";
			}

			alert("Check that all fields are filled in");
		
		}

		if (password.value.localeCompare(repeatpassword.value) != 0) { // check if the passwords match
			event.preventDefault();

			password.className += " missing";
			repeatpassword.className += " missing";

			alert("Passwords do not match!");
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