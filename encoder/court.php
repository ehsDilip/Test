<?php
    if (($_SERVER['HTTP_HOST'] != 'courtgenie.com') && ($_SERVER['HTTP_HOST'] != 'www.courtgenie.com')) {
        echo 'Error.. Occurred';
        exit;
    }
?>