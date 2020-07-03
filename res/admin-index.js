$(document).ready(() => {
	loadCompanies();
	companyIntervall = setInterval(loadCompanies, 10000);
	$('#search').on('click', (_) => {
		$('.my-search-textfield').fadeIn(function () {
			$('.my-search-textfield').focus();
			window.loadinglocked = true;
		});
	});
	$('.my-add-company-button').on('click', function () {
		$('#dialogs').css('display', 'flex');
		$('#dialogs #newcompany').show();
		$('#dialogs').fadeIn(100, function () {
			$('.my-card-label-server-issues').addClass('hidden');
			$('.my-card-label-no-text-written-error').addClass('hidden');
			$('.my-card-label-error').addClass('hidden');
			$('.my-companyname-textfield').val('');
			$('.my-companyname-tables-textfield').val('1');
			$('#dialogs #newcompany .my-companyname-textfield').focus();
			window.loadinglocked = true;
		});
	});
	$('.my-cancel-new-company-button').on('click', (_) => {
		$('#dialogs').fadeOut(100, function () {
			$(document.body).click();
			window.loadinglocked = false;
		});
	});
	$('.my-ok-new-company-button').on('click', (_) => {
		window.loadinglocked = true;
		let companyname2 = $('.my-companyname-textfield').val();
		let tablename = 'table-1';
		if (companyname2.length < 1) {
			$('.my-card-label-no-text-written-error').removeClass('hidden');
			$('#dialogs #newcompany .my-companyname-textfield').focus();
		} else {
			$.post(
				'https://api.ev-op.de/company/' +
					encodeURI(companyname2).replace(/\-/g, '_') +
					'/create',
				function (data, status) {
					if (data.result) {
						if (data.result.created.status) {
							$('#dialogs').fadeOut(100, function () {
								$(document.body).click();
								window.loadinglocked = false;
								loadCompanies();
							});
						} else {
							$('.my-card-label-server-issues').removeClass('hidden');
						}
					} else {
						console.log(data.error);
						$('.my-companyname-textfield').val('');
						$('.my-card-label-error').removeClass('hidden');
						$('.my-companyname-textfield').focus();
					}
				},
				'json'
			);
		}
	});
});

async function loadCompanies() {
	if (!window.loadinglocked) {
		$('#storeicon').text('hourglass_top');
		await wait(200);
		$.getJSON('https://api.ev-op.de/companies/', async (data, status) => {
			$('#content #companies #list').empty();
			data['result'].forEach(async (element) => {
				let c = new Company(
					element['name'],
					element['tables'],
					element['active']
				);
				$('#storeicon').text(
					$('storeicon').text() === 'hourglass_bottom'
						? 'hourglass_top'
						: 'hourglass_bottom'
				);
				$('#content #companies #list').append(c.html());
				await wait(100);
			});
			$('#storeicon').text('store_mall_directory');
		});
	}
}
