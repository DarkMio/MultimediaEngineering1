api_url = "http://localhost/mme1/api/v1/";

// @TODO: DEV FEATURE, PLS DELETE
function purgeCookie() {
    Cookies.remove("token", {path: '/'})
}

function dumpCookie() {
    console.log(Cookies.get())
}

function getRequest(url, params, callback) {
    $.get(url, params, function(data) {
            callback(data)
        }
    );
}

function requireLogin() {
    console.log(Cookies.get("username"));
    if (!Cookies.get("token") || !Cookies.get("username")) {
        var require = '<div id="login-required" class="alert alert-warning alert-dismissable flyover">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"x onclick="$(\'#login-required\').toggleClass(\'in\');">'+
            '×</button><strong>Warning!</strong> You are not logged in and tried to access a secured resource.</div>';
        $(require).appendTo('body');
        $('#login-required').toggleClass('in');
    }
}

function findStudios(long, lat, distance){
    getRequest(api_url + "findStudios", {long: long, lat: lat, distance: distance}, function data(a, b) {
        console.log(a, b);
    })
}

function login(username, password, callback) {
    return getRequest(api_url + "login", {username: username, password: password}, function data(response) {
        var success = response["response"]["success"];
        if (success) {
            Cookies.set("username", response["request"]["username"], {expires: 1, path: '/'});
            Cookies.set("token", response["response"]["token"], {expires: 1, path: '/'}); // expires in one day

        }
        callback(success)
    })
}

function showStaged(username, token, callback){
    console.log(username);
    getRequest(api_url + "showStaged", {username: username, token:token}, function data (response){
        callback(response);
    })
}