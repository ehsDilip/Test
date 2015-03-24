<?php

function makeSlugUserFriendly($string) {
    $new_string= preg_replace('/[^A-Za-z0-9\-]/', ' ', $string);
    return strtolower(preg_replace( "/\s+/", "-", $new_string ));
}

