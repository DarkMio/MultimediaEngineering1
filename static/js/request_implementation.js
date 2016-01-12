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
    if (!hasLogin()) {
        var require = '<div id="login-required" class="alert alert-warning alert-dismissable flyover">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="$(\'#login-required\').toggleClass(\'in\');">'+
            '×</button><strong>Warning!</strong> You are not logged in and tried to access a secured resource.</div>';
        $(require).appendTo('body');
        $('#login-required').toggleClass('in');
    }
}

function hasLogin() {
    return (Cookies.get("token") && Cookies.get("username"));
}

function getLogin() {
    return {username: Cookies.get("username"), token: Cookies.get("token")}
}

function findStudios(long, lat, distance, callback){
    getRequest(api_url + "findStudios", {long: long, lat: lat, distance: distance}, function data(response) {
        callback(response);
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

function insertFormRequest() {
    var input = collectInput();
    var params = $.extend({}, input, getLogin());
    getRequest(api_url + "addStudio", params, callback());

    function collectInput() { // cleans up empty fields on its own.
        var studio = inputHelper('studio');
        studio['studio_type'] = $('#studio-type').val();
        var owner = inputHelper('owner');
        var packed = $.extend({}, studio, owner);
        $.each(packed, function(key, value) { // cleans up all empty fields.
            if (value == "") {
                delete packed[key];
            }
        });
        return packed; // Redundant, sure, but maybe I need to process it later.
    }

    function inputHelper(ident) {
        var dict = {};
        dict[ident + '_name'] = $('#'+ ident +'-name').val();
        dict[ident + '_street_name'] = $('#' + ident + '-street-name').val();
        dict[ident + '_street_nr'] = $('#' + ident + '-street-nr').val();
        dict[ident + '_zip'] = $('#' + ident + '-zip').val();
        dict[ident + '_location'] = $('#' + ident + '-location').val();
        dict[ident + '_phone'] = $('#' + ident + '-phone').val();
        return dict;
    }

    function callback() { // heck, why not nesting the callback as well when we're at it.
        console.log("I am the callback of insertFormRequest.")
    }
}

