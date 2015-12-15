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