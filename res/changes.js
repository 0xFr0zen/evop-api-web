let changesIntervall;

$(document).ready(() => {
	let ressourceArray = [];
	$('head')
		.children()
		.filter((element) => {
			console.log(element);
		});

	changesIntervall = setInterval(() => {
		$.getJSON(
			`https://api.ev-op.de/check-changes/`,
			{ ressource: ressrouceArray },
			(data) => {
				if (currentSiteHash != data.result.hash) {
					location.reload();
				}
			}
		);
	}, 10000);
});
