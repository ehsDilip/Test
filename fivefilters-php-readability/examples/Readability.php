<?php

require_once '../Readability.php';
header('Content-Type: text/html; charset=utf-8');

$url = 'http://daylerees.com/codebright/blade';
$html = file_get_contents($url);

if (function_exists('tidy_parse_string')) {
    $tidy = tidy_parse_string($html, array(), 'UTF8');
    $tidy->cleanRepair();
    $html = $tidy->value;
}

$readability = new Readability($html, $url);

$readability->debug = false;

$readability->convertLinksToFootnotes = true;

$result = $readability->init();

if ($result) {
    
    echo $readability->getTitle()->textContent, "\n\n";
    
    $content = $readability->getContent()->innerHTML;
    
    if (function_exists('tidy_parse_string')) {
        $tidy = tidy_parse_string($content, array('indent' => true, 'show-body-only' => true), 'UTF8');
        $tidy->cleanRepair();
        $content = $tidy->value;
    }
    echo $content;
} else {
    echo 'Looks like we couldn\'t find the content. :(';
}
?>