let companyIntervall;
window.loadinglocked = false;
$(document).ready((_) => {
    loadProducts();
    companyIntervall = setInterval(loadProducts, 10000);
    $('#search').on('click', (_) => {
        $('.my-search-textfield').fadeIn(function () {
            $('.my-search-textfield').focus();
            window.loadinglocked = true;
        });
    });
    $('#settings').on('click', (_) => {
        $('#dialogs').css('display', 'flex');
        $('#dialogs #settingscompany').show();
        $('#dialogs').fadeIn(100, function () {
            window.loadinglocked = true;
        });
    });
    $('#delete').on('click', (_) => {
        $('#dialogs').css('display', 'flex');
        $('#dialogs #newcompany').show();
        $('#dialogs').fadeIn(100, function () {
            window.loadinglocked = true;
            $('.my-cancel-new-company-button').on('click', (_) => {
                $('#dialogs').fadeOut(100, function () {
                    $(document.body).click();
                    window.loadinglocked = false;
                });
            });
            $('.my-ok-new-company-button').on('click', (_) => {
                window.loadinglocked = true;

                $.ajax({
                    url: 'https://api.ev-op.de/company/' + encodeURI(companyname) + '/',
                    type: 'DELETE',
                    success: function (deleteddata) {
                        if (!deleteddata.result) {
                            $('.my-card-label-server-issues').removeClass(
                                'hidden'
                            );
                        } else {
                            if (!deleteddata.result.removed.status) {
                                $('.my-card-label-server-issues').removeClass(
                                    'hidden'
                                );
                            } else {
                                $('#dialogs').fadeOut(100, function () {
                                    $(document.body).click();
                                    window.close();
                                });
                            }
                        }
                    },
                    dataType: 'json',
                });
            });
        });
    });
    $(window).on('keydown', function (e) {
        if (e.key == 'Escape') {
            $('.my-search-textfield').fadeOut(100);
            $('#dialogs').fadeOut(100);
            $(document.body).click();
            window.loadinglocked = false;
        }
    });
});
async function loadProducts() {
    if (!window.loadinglocked) {
        $('#producticon').text('hourglass_top');
        await wait(200);
        $.getJSON(
            'https://api.ev-op.de/company/' + encodeURI(companyname) + '/products',
            async (data, status) => {
                $('#content #products #list').empty();
                $('#producticon').text(
                    $('storeicon').text() === 'hourglass_bottom'
                        ? 'hourglass_top'
                        : 'hourglass_bottom'
                );
                await wait(400);
                $('#producticon').text('store_mall_directory');
            }
        );
    }
}
function wait(timeout) {
    return new Promise((resolve) => {
        setTimeout(resolve, timeout);
    });
}
