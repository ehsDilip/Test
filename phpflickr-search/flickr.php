<?php

class Flickr {

    private $apiKey = '9f275087e222ee395c92662437bf84a2';

    public function __construct() {
        
    }

    public function flickrSearch($query = null, $list) {
        $search = 'http://flickr.com/services/rest/?method=flickr.photos.search&api_key=' . $this->apiKey . '&text=' . urlencode($query) . '&per_page=' . $list . '&format=php_serial';
        $result = file_get_contents($search);
        $result = unserialize($result);
        return $this->flickrImageSet($result);
        
    }

    public function flickrImageSet($data) {
        if ($data['stat'] == 'ok') {
            $photos = $data['photos']['photo'];
            if (count($photos) > 0) {
                foreach ($photos as $photo) {

                    /* Image SIZE
                      s : Small
                      t : Thumbnail
                      m : Medium
                      b : Large
                      o :Original */

                    // the image URL becomes somthing like 
                    // http://farm[FARM-ID].static.flickr.com/[SERVER-ID]/[ID]_[SECRET]_[SIZE].jpg
                    $thumbnailImage_size='m';
                    $largeImage_size='b';
                    $farmId = $photo["farm"];
                    $serverId = $photo["server"];
                    $id = $photo["id"];
                    $secret = $photo["secret"];
                    $title = $photo["title"];
                    $imagePathThumbnail = 'http://farm' . $farmId . '.static.flickr.com/' . $serverId . '/' . $id . '_' . $secret . '_'.$thumbnailImage_size.'.jpg';
                    $imagePathLarge = 'http://farm' . $farmId . '.static.flickr.com/' . $serverId . '/' . $id . '_' . $secret . '_'.$largeImage_size.'.jpg';
                    
                    $images[]=array("small_image_url" =>$imagePathThumbnail,"big_image_url"=>$imagePathLarge,"title"=>$title);
                }
                return $result_array=array("status"=>"YES","images" =>$images);
            } else {
                return $result_array=array("status"=>"NO","message" =>'No Results');
            }
        } else {
            return $result_array=array("status"=>"ERROR","message" =>$data['message']);
        }
    }
}
?>

