let changesIntervall;

$(document).ready(() => {
	$('head')
		.children()
		.filter((index, element) => {
			console.log(element);
		});

	changesIntervall = setInterval(() => {
		$.getJSON(
			`https://api.ev-op.de/check-changes/`,
			{ resource: resourceArray, page: location.href },
			(data) => {
				if (data.result) {
					if (currentSiteHash != data.result.hash) {
						location.reload();
					}
				}
			}
		);
	}, 10000);
});
