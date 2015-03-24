<?php
error_reporting(0);

include('youtube.inc.php');
$ytObject = new youTube();
$key='los angeles web design';
if ($ytObject->search($key, 2)) { // Here 'test' is a  string input which can be take  by User also and '2' no of record you want
    if (count($ytObject->RKT_requestResult->entry) > 0) {
        echo '<div align="center" class="video-container">';
        foreach ($ytObject->RKT_requestResult->entry as $video) {
            $return = $ytObject->parseVideoRow($video);
        }
        echo $return['content'];
        echo '</div>';
    }
}
?>