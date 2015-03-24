<?php
//settings
$cache_ext = '.php';
//$cache_time = 3600;
$cache_folder = 'cache/';
//$ignore_pages = array('', '');

$dynamic_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
$cache_file = $cache_folder . md5($dynamic_url) . $cache_ext;
//$ignore = (in_array($dynamic_url, $ignore_pages)) ? true : false;

if (file_exists($cache_file)) {
    ob_start('ob_gzhandler');
    readfile($cache_file);
    ob_end_flush();
    exit();
}

ob_start('ob_gzhandler');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Page to Cache</title>
    </head>
    <body>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut tellus libero.
    </body>
</html>
<?php
if (!is_dir($cache_folder)) {
    mkdir($cache_folder);
}

$fp = fopen($cache_file, 'w');
fwrite($fp, ob_get_contents());
fclose($fp);

ob_end_flush();
?>