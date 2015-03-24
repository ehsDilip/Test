<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Require a Drawn Signature · Signature Pad</title>
    <style>
        body { font: normal 100.01%/1.375 "Helvetica Neue",Helvetica,Arial,sans-serif; }
    </style>
    <link href="css/jquery.signaturepad.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
</head>
<body>
    <form method="post" class="sigPad">
        <ul class="sigNav">
            <li class="drawIt"><a href="#draw-it" >Draw It</a></li>
            <li class="clearButton"><a href="#clear">Clear</a></li>
        </ul>
        <div class="sig sigWrapper">
            <div class="typed"></div>
            <canvas class="pad" width="198" height="55"></canvas>
            <input type="hidden" name="sign_output" class="output">
        </div>
        <input type="submit" value="submit" name="sub">
    </form>
    
    <script src="js/jquery.signaturepad.js"></script>
    <script>
        $(document).ready(function() {
            $('.sigPad').signaturePad({drawOnly: true});
        });
    </script>
    <script src="js/json2.min.js"></script>
    <?php
    if (isset($_REQUEST['sub'])) {
        require_once 'signature-to-image.php';
        $json = $_REQUEST['sign_output'];
        $img = sigJsonToImage($json);
        //header('Content-Type: image/png');
        $name_img= uniqid();
        imagepng($img, $name_img.'.png');
        //imagedestroy($img);
    }
    ?>
</body>
