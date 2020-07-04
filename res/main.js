// // Instantiation
let companyIntervall;
let stillloggedinintervall;
window.loadinglocked = false;
let lastopenedWindow;
$(document).ready((_) => {
	stillloggedinintervall = setInterval(checklogin, 10000);
	mdc.topAppBar.MDCTopAppBar.attachTo(
		document.querySelector('.mdc-top-app-bar')
	);
	mdc.iconButton.MDCIconButtonToggle.attachTo(
		document.querySelector('#search')
	);

	$(window).on('keydown', function (e) {
		if (e.key == 'Escape') {
			$('.my-search-textfield').fadeOut(100);
			$('#dialogs').fadeOut(100);
			$(document.body).click();
			window.loadinglocked = false;
		}
	});
});
function checklogin() {
	$.getJSON('https://api.ev-op.de/mod/login-status/' + sessionID, (data) => {
		if (!data.result.status) {
			document.location.href = 'https://admin.ev-op.de/login';
		}
	});
}