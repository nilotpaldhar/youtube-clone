$(document).ready(function() {
	// Hiding and showing side navgation
	$('.navShowHide').on('click', function() {
		var main = $('#mainSectionContainer');
		var sideNav = $('#sideNavContainer');
		main.toggleClass('leftPadding');
		sideNav.toggleClass(function() {
			$(this)
				.children()
				.toggleClass('show');
			return 'show';
		});
	});

	// Initializing video player
	const videoPlayer = new Plyr('#video-player', {
		disableContextMenu: false
	});
});

function notSignedIn() {
	$('#signInAlert').modal('show');
}
