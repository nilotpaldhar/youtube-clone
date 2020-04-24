$(document).ready(function() {
	// Hiding and showing side navgation
	$('.navShowHide').on('click', function() {
		var main = $('#mainSectionContainer');
		var sideNav = $('#sideNavContainer');
		main.toggleClass('leftPadding');
		sideNav.toggleClass('show');
	});
});
