<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>HTML5 FORM RESPONSE</title></head>
<link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="./css/style.css">
<body>
<?php include "nav.php" ?>
<output>
    <div class="container">
        <div class="row">
            <div class="jumbotron">
                <h1>PHP ECHO OF POST REQUEST:</h1> <br>
                <?php evalCaptcha() ?>
            </div>
        </div>
    </div>
</output>
<?php include "footer.php" ?>
</body>
</html>

<?php

function evalCaptcha()
{
    if (isset($_POST['g-recaptcha-response'])) {                                                            // check if captcha is set.
        $captcha = $_POST['g-recaptcha-response'];                                                          // store it in a variable
        $secret = trim(file("secret.txt")[0]);                                                              // read secret key file first line (mostly bc of git)
        $response =                                                                                         // json decode of google api
            json_decode(
                file_get_contents(
                    "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" .
                    $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
        if ($response['success'] == false) {                                                                // if verification failed
            print('
                <h2>Captcha Verification failed.</h2>
                <div class="col-md-2 col-md-offset-5">
                        <a onclick="window.history.back();" class="btn btn-info">Retry</a>
                </div>
                ');
        } else {                                                                                            // if verification succeeded
            print("<table class='table table-striped table-hover'>");                                       // ready up table
            foreach ($_POST as $key => $value) {
                if ($key !== "g-recaptcha-response") print("
                                    <tr>
                                        <td class='col-md-2'><span class='text-danger'>$key</span></td>
                                        <td class='col-md-10'>$value</td>
                                    </tr>
                                    ");                                                                     // write table contents
            }
            print("</table>");                                                                              // finish table
        }
    } else {
        print("<h2> Malfunction of Captcha API. </h2>");                                                    // if g-recaptcha-response was not set
    }                                                                                                       // then there might have been an issue from the call form
}
?>