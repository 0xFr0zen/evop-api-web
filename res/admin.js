let stillloggedinintervall;
let checkmodrequestsintervall;
$(document).ready((_) => {
    checkmodrequests();
    stillloggedinintervall = setInterval(checklogin, 60000);
    checkmodrequestsintervall = setInterval(checkmodrequests, 20000);
});
function checklogin() {
    $.getJSON('/evop/api/mod-login-status', (data) => {
        if (!data.result.loggedin) {
            // document.location.href = 'http://fr0zenofficial.bplaced.net/test-apps/';
        }
    });
}
function checkmodrequests() {
    $.getJSON('/evop/api/mod-requests', (data) => {
        if (data.result.mod_requests) {
            let arr = data.result.mod_requests;
            $('#moderators').empty();
            arr.forEach((req_mod) => {
                $.get(
                    `/evop/api/mod-card/${req_mod.deviceid}/${req_mod.ip}/${req_mod.sessionid}/${req_mod.request_time}/${req_mod.code}`,
                    (card) => {
                        $('#moderators').append(card);
                    },
                    'html'
                );
            });
        }
    });
}
function wait(timeout) {
    return new Promise((resolve) => {
        setTimeout(resolve, timeout);
    });
}
