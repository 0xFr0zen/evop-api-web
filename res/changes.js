let changesIntervall;

$(document).ready(() => {
	changesIntervall = setInterval(() => {
		console.log(resourceArray);
		$.getJSON(
			`https://api.ev-op.de/check-changes/`,
			{ resource: resourceArray, page: window.location.pathname },
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
