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
<?php include 'nav.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <form class="form-horizontal" action="form_response.php" method="post">
                <fieldset>
                    <legend>Ein neues Studio registrieren:</legend>
                    <div class="form-group">
                        <label for="Name" class="col-lg-2 control-label">Name *</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Name" placeholder="Studioname" type="text" name="studioName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Ort" class="col-lg-2 control-label">Plz Ort *</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Ort" placeholder="Postleitzahl Ort" type="text" name="studioLocation" pattern="[0-9]{5}\s[\u00C0-\u017Fa-zA-Z'][\u00C0-\u017Fa-zA-Z-' ]+" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Straße" class="col-lg-2 control-label">Anschrift *</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Straße" placeholder="Hausnummer Straße" type="text" name="studioAddress" pattern="[\u00C0-\u017Fa-zA-Z-'\s]+\s[0-9]+[\w]" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Straße" class="col-lg-2 control-label">Telefon</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Telefon" placeholder="Telefonnummer" type="tel" name="studioPhone" pattern="^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Straße" class="col-lg-2 control-label">Webseite</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="URL" placeholder="URL / Facebook / @Twitter" type="url" name="studioURL">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="E-Mail" class="col-lg-2 control-label">E-Mail</label>
                        <div class="col-lg-10">
                            <input class="form-control" id="Mail" placeholder="E-Mail Adresse" type="email" name="studioMail">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Submitter" class="col-lg-2 control-label">Eingetragen als</label>
                        <div class="col-lg-10">
                            <select class="form-control accordion-dropdown" id="select" name="submitType">
                                <option value="withAddress studio">Studio Inhaber</option>
                                <option value="withAddress user">Gast</option>
                                <option value="withoutAddress" selected="selected">Anonym</option>
                            </select>
                        </div>
                    </div>
                    <div class="accordion" id="accordion">
                        <div class="accordion-group">
                            <div class="panel-collapse collapse" id="userInfo">
                                <div class="form-group">
                                    <label for="Name" class="col-lg-2 control-label">Name</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="Name" placeholder="Name / Kürzel" type="text" name="userName" pattern="[\u00C0-\u017Fa-zA-Z-']+\w[\u00C0-\u017Fa-zA-Z-']">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Ort" class="col-lg-2 control-label">Plz Ort</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="Ort" placeholder="Postleitzahl Ort" type="text" name="userLocation" pattern="[0-9]{5}\s[\u00C0-\u017Fa-zA-Z-' ]+">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Straße" class="col-lg-2 control-label">Anschrift</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="Straße" placeholder="Straße Hausnummer" type="text" name="userAddress" pattern="[\u00C0-\u017Fa-zA-Z-'\s]+\s[0-9]+[\w]">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="E-Mail" class="col-lg-2 control-label">E-Mail</label>
                                    <div class="col-lg-10">
                                        <input class="form-control" id="Mail" placeholder="E-Mail Adresse" type="email" name="userMail">
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
<?php include 'footer.php'; ?>
</body>
<script>
    $( document ).ready(function() {
        $("#select").change(function(){
            if ( $(this).val().indexOf("withAddress") != -1){
                $('#userInfo').collapse('show');
            } else {
                $('#userInfo').collapse('hide');
            }
        })
    });
</script>
</html>