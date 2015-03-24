<?php
class CURLQuery {

    protected $_post;
    protected $_options;
    protected $_ch;

    public function __construct($url) {
        if (!extension_loaded('curl')) {
            throw new Exception("cURL extension not enabled.");
        }
        $this->_ch = curl_init($url);
        $this->_options = array();
    }

    public function __get($name) {
        $resultat = NULL;
        if (defined($name)) {
            $value = constant($name);
            if (isset($this->_options[$value])) {
                $resultat = $this->_options[$value];
            }
        }
        return $resultat;
    }

    public function __set($name, $value) {
        if (defined($name) && preg_match('/^CURLOPT_(?!POSTFIELDS)/', $name)) {
            $this->_options[constant($name)] = $value;
        } else {
            throw new Exception("Invalid or protected option '$name'.");
        }
    }

    public function __isset($name) {
        return ( defined($name) && isset($this->_options[constant($name)]) );
    }

    public function __unset($name) {
        if (defined($name) && isset($this->_options[constant($name)])) {
            unset($this->_options[constant($name)]);
        }
    }

    public function __toString() {
        return sprintf("%s (%s)", __CLASS__, curl_getinfo($this->_ch, CURLINFO_EFFECTIVE_URL));
    }

    public function setTimeout($timeout) {
        $timeout = intval($timeout);
        if ($timeout > 0) {
            $this->CURLOPT_TIMEOUT = $timeout;
            $this->CURLOPT_CONNECTTIMEOUT = $timeout;
        }
    }

    public function addPostData($field_name, $value) {
        if (!isset($this->_post[$field_name]) && !is_array($value)) {
            $this->_post[$field_name] = $value;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function addPostFile($field_name, $file) {
        if (is_file($file)) {
            $this->_post[$field_name] = '@' . realpath($file);
        } else {
            throw new Exception("The file '$file' does not exist or is not a regular file");
        }
    }

    public function doRequest($output_file = FALSE) {
        if ($this->_options) {
            if (function_exists('curl_setopt_array')) {
                curl_setopt_array($this->_ch, $this->_options);
            } else {
                foreach ($this->_options as $option => $value) {
                    curl_setopt($this->_ch, $option, $value);
                }
            }
        }
        if ($output_file) {
            @$fp = fopen($output_file, 'w');
            if (!$fp) {
                throw new Exception("Can not open file '$output_file' for writing.");
            }
            curl_setopt($this->_ch, CURLOPT_FILE, $fp);
        } else {
            curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, TRUE);
        }
        if ($this->_post) {
            curl_setopt($this->_ch, CURLOPT_POST, TRUE);
            curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $this->_post);
        }
        $ret = curl_exec($this->_ch);
        if ($output_file) {
            fclose($fp);
        }
        if ($ret === FALSE) {
            throw new Exception("An error occurred : '" . curl_error($this->_ch) . "'");
        }
        return $ret;
    }

    public function __destruct() {
        unset($this->_options);
        unset($this->_post);
        curl_close($this->_ch);
    }

}
