<?php

class Vantiv_curl {

    public function query($url, $reqest_json = '') {

        $headers = array('Accept: application/json', 'Content-Type: application/json');
        $curl = curl_init();
        if ($curl === false) {
            throw new Exception('cURL init failed');
        }

        // Configure curl for website
        curl_setopt($curl, CURLOPT_URL, $url);

        // Set up to view correct page type
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // Turn on SSL certificate verfication

        curl_setopt($curl, CURLOPT_CAPATH, "uatpmi2.vantiv.com");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);


        // Tell the curl instance to talk to the server using HTTP POST
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $reqest_json);

        // 1 second for a connection timeout with curl
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);

        // Try using this instead of the php set_time_limit function call
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        // Causes curl to return the result on success which should help us avoid using the writeback option
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        return $result = curl_exec($curl);
    }

    public function ValidateCardholderIdentity($json) {
        $url = 'https://uatapi2.vantiv.com:8031/v3/ApiService.svc/rest/ValidateCardholderIdentity';
        return $result = $this->query($url, $json);
    }

    public function OpenAccount($json) {
        $url = 'https://uatapi2.vantiv.com:8031/v3/ApiService.svc/rest/OpenAccount';
        return $result = $this->query($url, $json);
    }

    public function GetCardholderIdentityValidationStatus($json) {
        $url = 'https://uatapi2.vantiv.com:8031/v3/ApiService.svc/rest/GetCardholderIdentityValidationStatus';
        return $result = $this->query($url, $json);
    }

}

?>
