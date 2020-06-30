let changesIntervall;

$(document).ready(() => {
	changesIntervall = setInterval(() => {
		$.getJSON(`https://api.ev-op.de/check-changes/`, (data) => {
			if (data.result) {
				if (currentSiteHash != data.result.hash) {
					location.reload();
				}
			}
		});
	}, 10000);
});
