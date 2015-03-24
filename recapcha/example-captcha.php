<html>
    <body>
        <form action="" method="post">
            <?php
            require_once('recaptchalib.php');

            $publickey = "6Lfk4_wSAAAAAFSNzVWy3vEYd0DxHOqROzU7TQTA";
            $privatekey = "6Lfk4_wSAAAAACGi4T_IlsIGjyRZBBpXPVHAW-mi";

            $resp = null;

            $error = null;

            if ($_POST["recaptcha_response_field"]) {
                $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

                if ($resp->is_valid) {
                    echo "You got it!";
                } else {
                    $error = $resp->error;
                }
            }
            echo recaptcha_get_html($publickey, $error);
            ?>
            <br/>
            <input type="submit" value="submit" />
        </form>
    </body>
</html>
