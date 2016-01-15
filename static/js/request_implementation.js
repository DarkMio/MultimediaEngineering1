api_url = "http://localhost/mme1/api/v1/";

// @TODO: DEV FEATURE, PLS DELETE
function purgeCookie() {
    Cookies.remove("token", {path: '/'})
}

function dumpCookie() {
    console.log(Cookies.get())
}

function getStudios(long, lat, callback) {
    var params = {long: long, lat: lat, distance: 50};
    getRequest(api_url + "findStudios", params, callback);
}

function getRequest(url, params, callback) {
    $.get(url, params, function(data) {
            callback(data)
        }
    );
}

function spawnRibbon(id, type, text) {
    var require = '<div id="' + id + '" class="alert alert-' + type + ' alert-dismissable flyover">' +
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="$(\'' + id + '\').toggleClass(\'in\');">'+
        '×</button>' + text + '</div>';
    $(require).appendTo('body');
    $('#' + id).toggleClass('in');
}

function requireLogin() {
    console.log(Cookies.get("username"));
    if (!hasLogin()) {
        spawnRibbon('login-required', 'warning', '<strong>Warning!</strong> You are not logged in and tried to access a secured resource.');
        return false;
    }
    return true;
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


function deleteStaged(id) {
    var params = $.extend({}, getLogin(), {id: id});
    getRequest(api_url + "deleteStaged", params, function(){});
    $('#staged-' + id).addClass("danger");
}

function acceptStaged(id) {
    var params = $.extend({}, getLogin(), {id: id});
    console.log(params);
    getRequest(api_url + "acceptStaged", params, function(){});
    $('#staged-' + id).addClass("success");
}

function collectData() {
    if(!checkCaptcha()) {
        return;
    }
    var input = collectInput();
    var params = $.extend({}, input, getLogin());
    params = $.extend({}, params, getLongLat(params));
    modalMap(params['studio_long'], params['studio_lat']);
    return params;

    // getRequest(api_url + "addStudio", params, callback);

    function checkCaptcha() {
        var captcha = grecaptcha.getResponse();
        if(captcha.length == 0) {
            spawnRibbon('captcha-fail', 'danger', 'Du hast das <strong>Captcha</strong> nicht bestätigt!');
            return false;
        } else {
            return true;
        }
    }

    function collectInput() { // cleans up empty fields on its own.
        var studio = inputHelper('studio');
        studio['studio_type'] = $('#studio-type').val();
        var owner = inputHelper('owner');
        var captcha = {captcha: grecaptcha.getResponse()};
        var packed = $.extend({}, studio, owner, captcha);
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

    function getLongLat(params) {
        var address = params['studio_street_name'] + ' ' + params['studio_street_nr'] + ', ' + params['studio_zip'] + ' ' +
                params['studio_location'];
        jQuery.ajax({
                url: "https://maps.googleapis.com/maps/api/geocode/json",
                data: {
                    address: address,
                    region: 'de'
                },
                dataType: 'json',
                async: false,
                success: function(html) {strReturn = html}
            });
        if(strReturn['status'] == "OK") {
            if (strReturn['results'].length > 1) {
                spawnRibbon('location-too-many', 'alert', 'Die angegebene Adresse wurde zu oft gefunden und wor können dein Studio nicht finden.');
            } else {
                return {
                    studio_lat: strReturn['results'][0]['geometry']['location']['lat'],
                    studio_long: strReturn['results'][0]['geometry']['location']['lng']
                };
            }
        } else {
            spawnRibbon('location-not-found', 'alert', 'Die Adresse wurde nicht gefunden - bitte überprüfe deine Angaben.');
        }
        console.log(strReturn);
    }

    function modalMap(long, lat) {
        var mapCanvas = document.getElementById('map');
        var mapOptions = {
            center: new google.maps.LatLng(lat, long),
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(mapCanvas, mapOptions);
        var modal = $('#map-modal');
        modal.on("shown.bs.modal", function() {
            google.maps.event.trigger(map, 'resize');
            map.setCenter(new google.maps.LatLng(lat, long));
            var marker = new google.maps.Marker({
                position: {lat: lat, lng: long},
                map: map
            })
        });
        modal.modal();
    }
}

function clearInput(id) {
    $(id)[0].reset();
}

function addStudio(params, callback) {
    getRequest(api_url + "addStudio", params, callback);
}
