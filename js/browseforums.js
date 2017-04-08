function toggleForums() {
	var forumLinks = document.getElementById("forum-links");
	var toggleButton = document.getElementById("forum-toggle");

	if (forumLinks.style.display != 'none') {
		forumLinks.style.display = 'none';
		toggleButton.innerHTML = "Browse Forums";
	} else {
		forumLinks.style.display = 'block';
		toggleButton.innerHTML = "Close &darr;";
	}
}