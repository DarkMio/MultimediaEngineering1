<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Example</title>
    <link rel="stylesheet" href="static/css/select2.css">
    <link rel="stylesheet" href="static/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/style.css">
    <link rel="stylesheet" href="static/css/select2-bootstrap.css">
</head>
<body>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Example</title>
    <link rel="stylesheet" href="../../css/select2.css">
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/select2-bootstrap.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <form class="form-horizontal col-lg-8" id="insert-form" method="get">
                <fieldset>
                    <legend>Ein neues Studio registrieren:</legend>
                    <div class="form-group">
                        <label for="studio-name" class="col-lg-2 control-label">Name <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input class="form-control" id="studio-name" placeholder="Studioname" type="text"
                                   name="studioName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="owner-info" class="col-lg-2 control-label">Studio Typ <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <select class="form-control" id="studio-type" name="submitType">
                                <option value="Tattoos" selected>Tattoos</option>
                                <option value="Pircings">Pircings</option>
                                <option value="Body Modification">Body Modification</option>
                                <option value="Permanent Makeup">Permanent Makeup</option>
                                <option value="Brandings">Brandings</option>
                                <option value="Jewellery">Schmuckverkauf</option>
                                <option value="Stamps">Stamps</option>
                                <option value="Tattoo Removal">Tattoo Entfernung</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="studio-street-name" class="col-lg-2 control-label">Anschrift <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-8">
                            <input class="form-control" id="studio-street-name" placeholder="Straße" type="text"
                                   name="studioStreet" required>
                        </div>
                        <div class="col-lg-2">
                            <input class="form-control col-lg-5" id="studio-street-nr" placeholder="Nr" type="text"
                                   name="studioLocation" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="studio-location" class="col-lg-2 control-label">Plz Ort <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-3">
                            <select class="dropdown form-control" id="studio-zip">
                                <option data-placeholder="Plz"></option>
                            </select>
                        </div>

                        <div class="col-lg-7">
                            <input class="form-control col-lg-5" id="studio-location" placeholder="Ortsname" type="text"
                                   name="studioLocation" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="studio-phone" class="col-lg-2 control-label">Telefon</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="studio-phone" placeholder="Telefonnummer" type="tel"
                                   name="studioPhone">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="owner-info" class="col-lg-2 control-label">Inhaber</label>
                        <div class="col-lg-10">
                            <select class="form-control accordion-dropdown" id="owner-info" name="submitType">
                                <option value="withAddress true">eintragen</option>
                                <option value="withoutAddress false" selected="selected">nicht eintragen</option>
                            </select>
                        </div>
                    </div>
                    <div class="accordion" id="accordion">
                        <div class="accordion-group">
                            <div class="panel-collapse collapse" id="userInfo">
                                <div class="form-group">
                                    <label for="owner-name" class="col-lg-2 control-label">Name <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="owner-name" placeholder="Name / Kürzel"
                                               type="text" name="userName">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="owner-street-name" class="col-lg-2 control-label">Anschrift <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <input class="form-control" id="owner-street-name" placeholder="Straße"
                                               type="text" name="studioStreet">
                                    </div>
                                    <div class="col-lg-2">
                                        <input class="form-control col-lg-5" id="owner-street-nr" placeholder="Nr"
                                               type="text" name="studioLocation">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="owner-location" class="col-lg-2 control-label">Plz Ort <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-2">
                                        <input class="form-control col-lg-3" id="owner-zip" placeholder="Plz"
                                               type="text" name="studioZip">
                                    </div>
                                    <div class="col-lg-8">
                                        <input class="form-control col-lg-5" id="owner-location" placeholder="Ortsname"
                                               type="text" name="studioLocation">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="owner-phone" class="col-lg-2 control-label">Telefon</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="owner-phone" placeholder="Telefonnummer"
                                               type="tel" name="studioPhone">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="g-recaptcha" class="col-lg-2 control-label"></label>
                        <div class="col-lg-10">
                            <div class="g-recaptcha" data-size="normal"
                                 data-sitekey="6Ldahw8TAAAAAA_WVGzpXB9oRwILwfyjeppNfDOl"></div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="owner-phone" class="col-lg-2 control-label"></label>
                        <div class="col-lg-10">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-warning submit" value="send" id="form-submit" data-toggle="modal">Absenden</button>
                            <p>
                                <small>Felder mit einer <span class="text-danger">*</span> Makierung sind Pflichtfelder.
                                </small>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-12 text-left">

                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<div id="map-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Bitte die Adresse bestätigen!</h4>
            </div>
            <div class="modal-body">
                <div id="map_wrapper" style="display: block;">
                    <div id="map" style="width:870px;height:400px"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Ändern</button>
                <button type="button" class="btn btn-warning" data-dismiss="modal" id="add-send">Absenden</button>
            </div>
        </div>

    </div>
</div>

</body>
<?php include 'footer.php'; ?>
</body>
<script src="./static/js/select2.full.min.js"></script>
<script src="static/bootstrap/js/transition.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script>
    $(function () {
        var params;
        $("#owner-info").change(function () {
            if ($(this).val().indexOf("withAddress") != -1) {
                $('#userInfo').collapse('show');
            } else {
                $('#userInfo').collapse('hide');
            }
        });

        $("#insert-form").submit(function (e) {
            e.preventDefault();
            params = collectData();
        });

        $("#add-send").click(function (e) {
            e.preventDefault();
            console.log(params);
            addStudio(params, function() {
                spawnRibbon('add-success', 'success', 'Das Studio wurde <strong>erfolgreich</strong> eingetragen!')
                clearInput('#insert-form');
                $('#map-modal').modal("hide");
            });
        });

        var $ajax = $("#studio-zip");

        $ajax.select2({
            theme: "bootstrap",
            // placeholder: "Plz", // - seems to be buggy.
            ajax: {
                url: api_url + "hintLocations",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {zip: params.term};
                },
                processResults: function (data) {
                    var resultset = [];
                    data.response.forEach(function(token) {
                        resultset.push({
                            name: token[0],
                            id: token[1]
                        });
                    });
                    return {
                        results: resultset,
                        pagination: {more: false}};
                },
                cache: true
            },
            escapeMarkup: function(markup) { return markup; },
            minimumInputLength: 3,
            templateResult: formatResult,
            templateSelection: formatSelection
        });

        function formatResult (repo) {
            if (repo.loading) return repo.text;
            return "<div class='select2-results clearfix'>" + repo.id + " " + repo.name + "</div>";
        }

        function formatSelection (zips) {
            $("#studio-location").val(zips.name);
            console.log(zips.name);
            return zips.id;
        }
    });
</script>
</html>