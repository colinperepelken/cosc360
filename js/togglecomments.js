
	
function toggleComments() {
	var comments = document.getElementsByClassName("comment");
	var toggleButton = document.getElementById("toggle-comments");

	for (var i = 0; i < comments.length; i++) {
		if (comments[i].style.display != 'none') {
			comments[i].style.display = 'none';
			toggleButton.innerHTML = "Show Comments &darr;";
		} else {
			comments[i].style.display = 'block';
			toggleButton.innerHTML = "Hide Comments &uarr;";
		}
	}
}

