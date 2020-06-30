let changesIntervall;

$(document).ready(() => {
	let resourceArray = [];
	$('head')
		.children()
		.filter((index, element) => {
			console.log(element);
		});

	changesIntervall = setInterval(() => {
		$.getJSON(
			`https://api.ev-op.de/check-changes/`,
			{ resource: resourceArray },
			(data) => {
				if (currentSiteHash != data.result.hash) {
					location.reload();
				}
			}
		);
	}, 10000);
});
