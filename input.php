<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Hey Internet Explorer: Fuck you. Sincerely, everyone. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tattooliste.de</title>
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./bootstrap/js/transition.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<?php include 'nav.php';?>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <form class="form-horizontal" action="form_response.php" method="post">
                <fieldset>
                    <legend>Ein neues Studio registrieren:</legend>
                    <div class="form-group">
                        <label for="Name" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Name" placeholder="Studioname" type="text" name="studioName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Ort" class="col-lg-2 control-label">Plz Ort</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Ort" placeholder="Postleitzahl Ort" type="text" name="studioLocation" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Straße" class="col-lg-2 control-label">Anschrift</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Straße" placeholder="Stra&szlig;e Hausnummer" type="text" name="studioAddress" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Submitter" class="col-lg-2 control-label">Eingetragen als</label>
                        <div class="col-lg-10">
                            <select class="form-control accordion-dropdown" id="select" name="submitType">
                                <option value="withAddress studio">Studio Inhaber</option>
                                <option selected="selected" value="withAddress user">Gast</option>
                                <option value="withoutAddress">Anonym</option>
                            </select>
                        </div>
                    </div>
                    <div class="accordion" id="accordion">
                        <div class="accordion-group">
                            <div class="panel-collapse collapse in" id="collapseExample">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-2 control-label">Name</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="Name" placeholder="Name / Kürzel" type="text" name="userName">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Ort" class="col-lg-2 control-label">Plz Ort</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="Ort" placeholder="Postleitzahl Ort" type="text" name="userLocation">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Straße" class="col-lg-2 control-label">Anschrift</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="Straße" placeholder="Straße Hausnummer" type="text" name="userAddress">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Ort" class="col-lg-2 control-label"></label>
                            <div class="col-lg-10">
                                <div class="g-recaptcha" data-size="normal" data-sitekey="6Ldahw8TAAAAAA_WVGzpXB9oRwILwfyjeppNfDOl"></div>
                            </div>
                        </div>
                        <div class="col-lg-12 text-right">
                            <button type="reset" class="btn btn-warning">Reset</button>
                            <button type="submit" class="btn btn-primary submit" value="send">Absenden</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
</body>
<script>
    $( document ).ready(function() {

        $(".alert").find(".close").on("click", function (e) {
            // Find all elements with the "alert" class, get all descendant elements with the class "close", and bind a "click" event handler
            e.stopPropagation();    // Don't allow the click to bubble up the DOM
            e.preventDefault();    // Don't let any default functionality occur (in case it's a link)
            $(this).closest(".alert").slideUp(400);    // Hide this specific Alert
        });


        $("#select").change(function(){
            if ( $(this).val().indexOf("withAddress") != -1){
                $('#collapseExample').collapse('show');
            } else {
                $('#collapseExample').collapse('hide');
            }
        })
    });
</script>
</html>