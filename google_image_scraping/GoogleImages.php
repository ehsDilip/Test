<?php

class GoogleImages
{
    //google gives 4 images per request
    private $count = 4;
    //enter your key here
    private $key = 'AIzaSyA5NCX_UIYtJ1Hm9C4GdeuptZ2BeWG80Wc';
    
    private function multi_curl($urls){
        // for curl handlers
        $curl_handlers = array();
        $images = array();
    
        //for storing contents
        $content = array();
        //setting curl handlers
        foreach ($urls as $url) 
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $curl_handlers[] = $curl;
        }
        //initiating multi handler
        $multi_curl_handler = curl_multi_init();
    
        // adding all the single handler to a multi handler
        foreach($curl_handlers as $key => $curl)
        {
            curl_multi_add_handle($multi_curl_handler,$curl);
        }
        
        // executing the multi handler
        do 
        {
            $multi_curl = curl_multi_exec($multi_curl_handler, $active);
        } 
        while ($multi_curl == CURLM_CALL_MULTI_PERFORM  || $active);
        
        foreach($curl_handlers as $curl)
        {
            //checking for errors
            if(curl_errno($curl) == CURLE_OK)
            {
                //if no error then getting content
                $content = curl_multi_getcontent($curl);
                $result = json_decode($content, true);
                foreach($result['responseData']['results'] as $img)
                {
                    $images[] = $img;
                }
            }
            else
            {
                $images[] = curl_error($curl);
            }
        }
        curl_multi_close($multi_curl_handler);
        return $images;
    }

    private function output($images, $cols = 4, $rows = 5){
        //creating table
        echo "<table border='1'>";
        for($i=0; $i < $rows; $i++)
        {
            //outputting text with search criteries found
            echo "<tr>";
            for($j=0; $j < $cols; $j++)
            {
                echo "<td>".$images[($i*$this->count) + $j]['content']."</td>";
            }
            echo "</tr>";
            //outputting thumbnail with link to real size image
            echo "<tr>";
            for($j=0; $j < $cols; $j++)
            {
                echo "<td><a href='".$images[($i*$this->count) + $j]['url'].
                "' target='blank'><img src='".$images[($i*$this->count) + $j]['tbUrl'].
                "' /></a></td>";
            }
            echo "</tr>";
            //outputtin link to webpage where image is found
            echo "<tr>";
            for($j=0; $j < $cols; $j++)
            {
                echo "<td><a href='".$images[($i*$this->count) + $j]['originalContextUrl'].
                "' target='blank'>View page</a></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
    public function get_images($query, $cols = 4, $rows = 5){
        //calculating amount of requests
        $requests = floor(($cols*$rows)/$this->count);
        //creating array with urls
        $urls = array();
        for($i = 0; $i < $requests; $i++)
        {
            $urls[$i] = 'http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=';
            $urls[$i] .= urlencode($query).'&start='.($i*$this->count).'&key='.$this->key;
        }
        //performing multiple requests
        return $images = $this->multi_curl($urls);
        //outputting results
        //$this->output($images, $cols, $rows);
    }
}
?>  