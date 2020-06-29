// // Instantiation
let companyIntervall;
let stillloggedinintervall;
window.loadinglocked = false;
$(document).ready((_) => {
    mdc.topAppBar.MDCTopAppBar.attachTo(
        document.querySelector('.mdc-top-app-bar')
    );
    mdc.iconButton.MDCIconButtonToggle.attachTo(
        document.querySelector('#search')
    );
    loadCompanies();
    companyIntervall = setInterval(loadCompanies, 10000);
    stillloggedinintervall = setInterval(checklogin, 10000);
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
        let tables = $('.my-companyname-tables-textfield').val();
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
                            $.ajax({
                                url:
                                    'https://api.ev-op.de/company/' +
                                    encodeURI(companyname2).replace(
                                        /\-/g,
                                        '_'
                                    ) +
                                    '/tables/' +
                                    tables +
                                    '/',
                                type: 'PUT',
                                success: function (tabledata) {
                                    if (!tabledata.result) {
                                        $(
                                            '.my-card-label-server-issues'
                                        ).removeClass('hidden');
                                    } else {
                                        if (!tabledata.result.updated) {
                                            $(
                                                '.my-card-label-server-issues'
                                            ).removeClass('hidden');
                                        } else {
                                            $('#dialogs').fadeOut(
                                                100,
                                                function () {
                                                    $(document.body).click();
                                                    window.loadinglocked = false;
                                                    loadCompanies();
                                                }
                                            );
                                        }
                                    }
                                },
                                dataType: 'json',
                            });
                        } else {
                            $('.my-card-label-server-issues').removeClass(
                                'hidden'
                            );
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
    $.getJSON('https://api.ev-op.de/mod/login-status/', (data) => {
        if (!data.result.status) {
            document.location.href =
                'https://admin.ev-op.de/login';
        }
    });
}
async function loadCompanies() {
    if (!window.loadinglocked) {
        $('#storeicon').text('hourglass_top');
        await wait(200);
        $.getJSON('https://api.ev-op.de/companies/', async (data, status) => {
            $('#content #companies #list').empty();
            data['result'].forEach(async (element) => {
                let c = new Company(element['name'], element['tables']);
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
function wait(timeout) {
    return new Promise((resolve) => {
        setTimeout(resolve, timeout);
    });
}
function Company(name, tables) {
    this.name = name;
    this.tables = tables;
    this.html = () => {
        let card = document.createElement('div');
        card.className = 'mdc-card my-card';
        let cardContentHolder = document.createElement('div');
        cardContentHolder.className = 'my-card-content';

        let cardLabel = document.createElement('span');
        cardLabel.className = 'my-card-label';
        $(cardLabel).text(this.name);

        let cardInformation = document.createElement('span');
        cardInformation.className = 'my-card-information';
        $(cardInformation).text('tables: ' + this.tables);

        $(cardContentHolder).append(cardLabel);
        $(cardContentHolder).append(cardInformation);

        let action1 = ActionMaker('Show', 'arrow_forward');
        $(action1).on('click', () => {
            let openlink = './company/' + this.name;
            window.open(openlink);
        });
        let cardActionHolder = document.createElement('div');

        cardActionHolder.className = 'mdc-card__actions my-card-action-holder';

        $(cardActionHolder).append(action1);

        $(card).append(cardContentHolder);
        $(card).append(cardActionHolder);
        return card;
    };
}

function ActionMaker(title, text) {
    let action1 = document.createElement('button');
    action1.className =
        'material-icons-round mdc-icon-button mdc-card__action mdc-card__action--icon my-card-action-button';
    $(action1).attr('title', title);
    $(action1).text(text);
    return action1;
}
