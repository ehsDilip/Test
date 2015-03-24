<?php
error_reporting(0);
require_once('flickr.php');
$Flickr = new Flickr;
$data = $Flickr->flickrSearch('android', 10);

if ($data['status'] == 'YES') {
    $output='';
    foreach ($data as $images) {
        $output .= '<ul>';
        foreach ($images as $img) {
            $output .= '<li>';
            $output .= '<a href="'.$img['big_image_url'].'">';
            $output .= '<img src="'.$img['small_image_url'].'" alt="'.$img['title'].'"/>';
            $output .= '</a>';
            $output .= '</li>';
        }
        $output .= '</ul>';
    }
    echo $output;
}
if ($data['status'] == 'NO') {
    
}
if ($data['status'] == 'ERROR') {
    
}
?>