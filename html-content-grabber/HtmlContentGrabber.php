<?php
/***************************************************************
 *  Copyright (C) 2012  Nabil Droussi <nabildroussi@gmail.com>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ***************************************************************/

class HtmlContentGrabber
{
    private static $_cookies = array();
    private $_header;
    private $_bodyHTML;
    
    public function getCookies()
    {
        return self::$_cookies;
    }
    
    public function addCookie($key, $value)
    {
        self::$_cookies[$key] = $value;
    }
    
    public function connect($url, $uri, $port=80)
    {
        $header = array();
        $header[] = "GET ".  $uri." HTTP/1.1";
        $header[] = "Host: {$url}";
        $header[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0";
        $header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        $header[] = "Accept-Language: fr,fr-fr;q=0.8,en-us;q=0.5,en;q=0.3";
        $header[] = "Accept-Encoding: none";
        $header[] = "Connection: close";
        if(count(self::$_cookies) > 0)
        {
            $cookies = array();
            foreach(self::$_cookies as $key => $value)
            {
                $cookies[] = $key."=".$value;
            }
            $header[] = "Cookie: ".implode("; ",$cookies)."";
        }
            
        
        $header[] = "Cache-Control: max-age=0";
        
        $socket = fsockopen($url, $port);
        fwrite($socket, implode("\n", $header));
        fwrite($socket,"\n\n");
        fflush($socket);
        
        $content = "";
        while(!feof($socket))
        {
            $content.=fread($socket, 4096);
        }
        
        fclose($socket);
        
        $this->_header = substr($content,0,strpos($content, "\r\n\r\n"));
        $this->_bodyHTML = substr($content,strpos($content, "\r\n\r\n")+4);
        
        $this->parseCookies();
        
        return true;
    }
    
    public function getHeader()
    {
        return $this->_header;
    }
    
    public function getBody()
    {
        return $this->_bodyHTML;
    }
    
    public function parseCookies()
    {
        $tmp = explode("\r\n", $this->_header);
        foreach($tmp as $header)
        {
            $position = strpos($header, "Set-Cookie:");
            
            if($position !== false)
            {
                $cookie = substr($header, $position + 12);
                $tmpCookie = explode(";", $cookie);
                if(count($tmpCookie)>0)
                {
                    $cookie = trim($tmpCookie[0]);
                    $tmpCookie = explode("=", $cookie);
                    if(count($tmpCookie == 2))
                        $this->addCookie(trim($tmpCookie[0]), trim($tmpCookie[1]));
                }
            }
        }
    }
}
?>