let logininterval;
$(document).ready((_) => {
    logininterval = setInterval(checklogin, 2000);
});
function checklogin() {
    $.getJSON('/evop/api/mod-login-status', (data) => {
        if (data.result.loggedin) {
            document.location.href = '/evop/';
        }
    });
}
function wait(timeout) {
    return new Promise((resolve) => {
        setTimeout(resolve, timeout);
    });
}
