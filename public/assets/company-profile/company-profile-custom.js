// Add active to current selected menu on navbar
$(document).ready(function() {
	var listMenu = $("li.js_nav-item.nav-item > a");
	var currentURL = window.location.href;
	for (var i = 0; i < listMenu.length; i++) {
		if (currentURL == $(listMenu[i]).attr('href') ||
			currentURL.startsWith($(listMenu[i]).attr('href') + '/') ||
			currentURL.startsWith($(listMenu[i]).attr('href') + '?')) {
			$(listMenu[i]).closest('li').addClass('active'); 
		}
	}
})