function request() {
    $.getJSON( "http://localhost/mme/api/v1/login", function( data ) {
        console.log(data);
        var items = [];
        $.each( data, function( key, val ) {
            items.push( "<li id='" + key + "'>" + val + "</li>" );
        });

        $( "<ul/>", {
            "class": "my-new-list",
            html: items.join( "" )
        }).appendTo( "body" );
    }).success(function() {console.log("success")})
        .error(function(data) {console.log(data)})
    ;
}

function debugAPI(endnode, parameters) {
    var api_url = "http://localhost/mme/api/v1/" + endnode;
    var testString = "";
    $.each(parameters, function (key, value) {
        testString += key+"="+value+"&";
    });
    testString = testString.substring(0, testString.length - 1);
    return $.getJSON(api_url + "?" + testString);
}

function debugAPIText(endnode, parameters) {
    var val = debugAPI(endnode, parameters).then(function (data, status, jqXHR) {
        return jqXHR.responseText;
    });
    return val;
}

function findStudios(long, lat, distance){
    return debugAPI("findStudios", {long: long, lat: lat, distance: distance}).then(function(data){
        return "Longitude: "+ data["request"]["long"] + " | Latitude: " + data["request"]["lat"]
        + " | Distance: "+ data["request"]["distance"];
    })
}

function verifyLocation (zip, location){
    return debugAPI("verifyLocation", {zip: zip, location: location}).then(function(data){
        return "Entered Zipcode: "+ data["request"]["zip"] + " | Entered City: "+data["request"]["location"] +
                " ist: "+data["response"]["Exception"];

        //Exception needs to be rewritten in API Class
    })
}

function registerUserino (username, password) {
    return debugAPI("register", {username: username, password: password}).then(function(data){
        return "Username: "+ data["request"]["username"] + " " + data["response"]["Exception"];
    })
}

function login(username, password) {
    return debugAPI("login", {username: username, password: password}).then(function(data){
        return "Username: "+ data["request"]["username"] + " | Token: " + data["response"]["token"];
    })
}

function hintDaLocations(zip){
    return debugAPI("hintLocations", {zip: zip}).then(function(data){
        return "Entered Zip: "+ data["request"]["zip"] + " Results: " + data["response"];
    })
}

function showStagedStudios(username, token){
    return debugAPI("showStaged", {username: username, key: token}).then(function(data){
        return data["response"];
    })
    // needs some work, does not work yet with output, only shows Objects
}

function addStudioKappa(studio_street_nr, studio_name, studio_type, studio_street_name,
                        studio_long, studio_lat, studio_zip, studio_location, username, token ){
    return debugAPI ("addStudio", {studio_street_nr: studio_street_nr, studio_name: studio_name,
                                studio_type: studio_type, studio_street_name: studio_street_name,
                                studio_long:studio_long, studio_lat: studio_lat, studio_zip: studio_zip,
                                studio_location: studio_location, username: username, token: token}).then(function(data){
        return JSON.stringify(data);
    })
}
//works theoretically, but addStudio is broken?