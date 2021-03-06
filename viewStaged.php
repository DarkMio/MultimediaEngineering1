<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Tattoostudio</title>
    <link rel="stylesheet" href="static/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
<?php include "header.php" ?>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <br/>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <h1 class="center-block">Staged Studios</h1>
                <table class="table table-striped table-hover" id="staging-table">
                    <thead>
                    <tr>
                        <th class="hidden-xs">Accept</th>
                        <th class="hidden-xs">ID, Type</th>
                        <th>Name</th>
                        <th>Street, Nr.</th>
                        <th>ZIP, Location</th>
                        <th>Phone</th>
                        <th class="hidden-xs">Owner</th>
                        <th>Operations</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php" ?>
</body>
<script>
    $(function () {
        requireLogin();
        showStaged(Cookies.get("username"), Cookies.get("token"), function (response) {
            var table = $('#staging-table').find("tbody:last");
            $.each(response["response"], function (key, value) {
                var result = "";
                var api_delete = api_url + "deleteStaged?id=";
                var api_accept = api_url + "acceptStaged?id=";
                result += "<tr id='staged-" + value["id"] + "'>" +
                    "<td class=\"hidden-xs\"><label><input type=\"checkbox\"></label></td>" +
                    "<td>" + value["id"] + "</td>" +
                    "<td>" + value["name"] + "</td>" +
                    "<td>" + value["street"] + " " + value["street_nr"] + "</td>" +
                    "<td>" + value["zip"] + " " + value["location"] + "</td>" +
                    "<td>" + value["phone"] + "</td>";
                if (value["owner"]) {
                    result += "<td>" + value["owner"]["first_name"] + " " + value["owner"]["last_name"] + "</td>"
                } else {
                    result += "<td> [No owner] </td>"
                }
                result += "<td>" +
                    "<a onClick=\"acceptStaged(" + value['id'] + ")\"><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span></a>" +
                    "<a onClick=\"deleteStaged(" + value['id'] + ")\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></a>" +
                    "<span class=\"glyphicon glyphicon-star-empty\" aria-hidden=\"true\"></span>" +
                    "</td></tr>";
                table.append(result);
            })
        })
    })
</script>
</html>