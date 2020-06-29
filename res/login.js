let logininterval;
$(document).ready((_) => {
    logininterval = setInterval(checklogin, 2000);
});
function checklogin() {
    $.getJSON('https://api.ev-op.de/mod/login-status', (data) => {
        if (data.result.loggedin) {
            document.location.href = '/';
        }
    });
}
function wait(timeout) {
    return new Promise((resolve) => {
        setTimeout(resolve, timeout);
    });
}
