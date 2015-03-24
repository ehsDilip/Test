<?php

class Extra {

    // redirect page
    public static function redirect($url) {
        $org_url = trim($url).'.php';
        header("location:" . $org_url);
        exit();
    }

    public static function isBlank($string) {
        $string = trim($string);
        if (empty($string)) {
            return true;
        } else {
            return false;
        }
    }

    public static function isValidEmail($string) {
        $validate = preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $string);
        if (!$validate) {
            return true;
        } else {
            return false;
        }
    }

    public static function isNumeric($string) {
        $validate = preg_match("/^([0-9])+$/", $string);
        if (!$validate) {
            return true;
        } else {
            return false;
        }
    }

    // check for character with space
    public static function isCharacter($string) {
        $validate = preg_match("/^[a-zA-Z ]*$/", $string);
        if (!$validate) {
            return true;
        } else {
            return false;
        }
    }

    // check for character,space, underscore and dash
    public static function isCharSpcUscDsh($string) {
        $validate = preg_match("/^[a-zA-Z _-]*$/", $string);
        if (!$validate) {
            return true;
        } else {
            return false;
        }
    }

    // check for only 
    public static function isAlphaNum($string) {
        $validate = preg_match("/^([A-Za-z0-9])+$/", $string);
        if (!$validate) {
            return true;
        } else {
            return false;
        }
    }

    // check alphanumeric with underscore
    public static function isAlphaNumuUsc($string) {
        $validate = preg_match("/^([A-Za-z0-9_])+$/", $string);
        if (!$validate) {
            return true;
        } else {
            return false;
        }
    }

    // generate random color	
    public static function getRandomColor() {
        mt_srand((double) microtime() * 1000000);
        $c = '#';
        while (strlen($c) < 6) {
            $c .= sprintf("%02X", mt_rand(0, 255));
        }
        return $c;
    }

    public static function showErrorMsg(&$errors) {
        $errMsg = '<div class = "alert alert-danger alert-dismissable">';
        $errMsg .= '<a class = "close" href = "#">�</a>';
        for ($i = 0; $i < sizeof($errors); $i++) {
            $errMsg .= ucfirst(strtolower($errors[$i])) . '<br/>';
        }
        $errMsg .= '</div >';
        echo $errMsg;
    }

    public static function showErrorLoginMsg(&$errors) {
        for ($i = 0; $i < sizeof($errors); $i++) {
            $errMsg = ucfirst(strtolower($errors[$i]));
        }       
        echo $errMsg;
    }
    
    public static function showLoginErrorMsg(&$errors) {
        $errMsg = '<div class = "alert alert-danger alert-dismissable">';
        for ($i = 0; $i < sizeof($errors); $i++) {
            $errMsg .= ucfirst(strtolower($errors[$i])) . '<br/>';
        }
        $errMsg .= '</div >';
        echo $errMsg;
    }

    public static function showSuccessMsgs(&$errors) {
        $errMsg = '<div class = "alert alert-success">';
        for ($i = 0; $i < sizeof($errors); $i++) {
            $errMsg .= ucfirst(strtolower($errors[$i])) . '<br/>';
        }
        $errMsg .= '</div>';
        echo $errMsg;
    }

    public static function showSuccessMsg($errors) {
        $errMsg = '<div class = "alert alert-success">';
        $errMsg .= ucfirst(strtolower($errors)) . '<br/>';
        $errMsg .= '</div>';
        echo $errMsg;
        unset($_SESSION['succMsg']);
    }

    public static function noRecordFound($cols, $errorMsg = '') {
        if ($this->isBlank($errorMsg)) {
            $customMsg = strtolower('record');
        } else {
            $customMsg = strtolower($errorMsg);
        }
        $string = '<tr><td class="noRecord" colspan="' . $cols . '">No ' . $customMsg . ' found</td></tr>';
        echo $string;
    }

    public static function getIpAddress() {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } else {
                $ip = getenv('REMOTE_ADDR');
            }
        }
        return $ip;
    }

    // get all the date of current week
    public static function getWeekDate($date) {
        list($day, $month, $year) = explode("-", $date);     // Assuming $date is in format DD-MM-YYYY
        $wkday = date('l', mktime('0', '0', '0', $month, $day, $year));  // Get the weekday of the given date

        switch ($wkday) {
            case 'Monday': $numDaysToMon = 0;
                break;
            case 'Tuesday': $numDaysToMon = 1;
                break;
            case 'Wednesday': $numDaysToMon = 2;
                break;
            case 'Thursday': $numDaysToMon = 3;
                break;
            case 'Friday': $numDaysToMon = 4;
                break;
            case 'Saturday': $numDaysToMon = 5;
                break;
            case 'Sunday': $numDaysToMon = 6;
                break;
        }

        $monday = mktime('0', '0', '0', $month, $day - $numDaysToMon, $year); // Timestamp of the monday for that week
        $seconds_in_a_day = 86400;

        for ($i = 0; $i < 7; $i++) {            // Get date for 7 days from Monday (inclusive)
            $dates[$i] = date('Y-m-d', $monday + ($seconds_in_a_day * $i));
        }
        return $dates;
    }

    // get all the date of two dates
    public static function getAllDateFromTwodate($fromDate, $toDate) {
        $dateMonthYearArr = array();
        $fromDateTS = strtotime($fromDate);
        $toDateTS = strtotime($toDate);

        for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24)) {
            $currentDateStr = date("Y-m-d", $currentDateTS); // use date() and $currentDateTS to format the dates in between
            $dateMonthYearArr[] = $currentDateStr;    //print $currentDateStr.�<br />�;
        }
        $dateMonthYearArr[] = $currentDateStr;
        return $dateMonthYearArr;
    }

    // add days into date
    public static function addDayIntoDate($date, $day) {
        $add_day_into_date = strtotime(date("Y-m-d", strtotime($date)) . $day . " day");
        return date('Y-m-d', $add_day_into_date);
    }

    // count number of days betwen two date
    public static function getDayFromTwoDate($start, $end) {
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $diff = $end_ts - $start_ts;
        return round($diff / 86400);
    }

    // display the file size in memory
    public static function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . '_' . $units[$pow];
    }

    // convert folder into zip
    public static function createZip($source, $destination) {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {
                $file = str_replace('\\', '/', realpath($file));

                if (is_dir($file) === true) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } else if (is_file($file) === true) {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        } else if (is_file($source) === true) {
            $zip->addFromString(basename($source), file_get_contents($source));
        }
        return $zip->close();
    }

    // generate random  password
    public static function createPassword($length) {
        $chars = "1234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $i = 0;
        $password = "";
        while ($i <= $length) {
            $password .= $chars{mt_rand(0, strlen($chars))};
            $i++;
        }
        return $password;
    }

    // get file name
    public static function getFileName($string) {
        $positin = strpos($string, ".");
        $extension = substr($string, 0, $positin);
        return $extension;
    }

    // get file type
    public static function getFileType($string) {
        $extension = getFileExtension($string);

        $images = array('jpg', 'gif', 'png', 'bmp');
        $docs = array('txt', 'rtf', 'doc');
        $apps = array('zip', 'rar', 'exe');

        if (in_array($extension, $images))
            return "Images";
        if (in_array($extension, $docs))
            return "Documents";
        if (in_array($extension, $apps))
            return "Applications";
        return "";
    }

    // get file extension
    public static function getFileExtension($string) {
        $positin = strrpos($string, ".");
        $extension = substr($string, $positin);
        return $extension;
    }

    // get file extension
    public static function checkFileExtension(&$valid_format = array(), $imgName) {
        if (count($valid_format) > 0) {
            $valid_formats = $valid_format;
        } else {
            $valid_formats = array(".jpg", ".png", ".gif");
        }
        $extension = Extra::getFileExtension(strtolower($imgName));

        if (!in_array($extension, $valid_formats)) {
            return true;
        } else {
            return false;
        }
    }

    // get extension
    public static function getExtOne($str) {
        $t = "";
        $string = $str;
        $tok = strtok($string, ".");
        while ($tok) {
            $t = $tok;
            $tok = strtok(".");
        }
        return $t;
    }

    // get extension
    public static function getExtTwo($string) {
        $string = strtolower($string);
        $exts = split("[/\\.]", $string);
        $n = count($exts) - 1;
        $exts = $exts[$n];
        return $exts;
    }

    // get extension
    public static function getExtThree($string) {
        return end(explode(".", $string));
    }

    // ck editor
    public static function CKEditor($field_name, $value, $toolbar = "User", $cols = "80%", $rows = 10) {
        include('include/ckeditor/ckeditor.php');
        $CKEditor = new CKEditor();
        $CKEditor->basePath = 'include/ckeditor/';
        $CKEditor->returnOutput = true;
        $config['skin'] = 'kama';     // default skin is -> kama   -->  available skins: kama, office2003, v2  --- uncomment line to use       
        $config['uiColor'] = '#AABBCC';  // User Interface Color ... sets CKEditor's toolbar gradient color              
        $config['width'] = $cols;    // width can be set to pixels or percentage... For Example:  $config['width'] = 600;  -OR- $config['width'] = '90%';
        $config['height'] = $rows * 17;
        if ($toolbar == "Basic") {
            $config['toolbar'] = array(array('Source', '-', 'Cut', 'Copy', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', '-', 'BidiLtr', 'BidiRtl'), '/', array('Bold', 'Italic', '-', 'HorizontalRule', '-', 'NumberedList', 'BulletedList', '-', 'OrderedList', 'UnorderedList', '-', 'Link', 'Unlink', 'Anchor', '-', 'About'));
        }
        // Create editor instance.
        echo $CKEditor->editor($field_name, $value, $config);
    }

    // decode the url
    public static function safe_b64decode($string) {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    // encode the url
    public static function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    // make url friendly
    public static function makeUrlFriendly($string) {
        $output = preg_replace("/\s+/", "_", trim($string));
        $output = preg_replace("/\W+/", "", $output);
        $output = preg_replace("/_/", "-", $output);
        return strtolower($output);
    }

    public static function isGreaterThanCurrDate($date) {
        $fromtimestamp = strtotime($date);
        $todaydate = date('d-m-Y');
        $totimestamp = strtotime($todaydate);
        if ($totimestamp <= $fromtimestamp) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function isGreaterDate($todate, $fromdate) {
        $todatetimestamp = strtotime($todate);
        $fromdatetimestamp = strtotime($fromdate);
        if ($fromdatetimestamp > $todatetimestamp) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function isLesserDate($todate, $fromdate) {
        $todatetimestamp = strtotime($todate);
        $fromdatetimestamp = strtotime($fromdate);
        if ($fromdatetimestamp < $todatetimestamp) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function DynamicTitle($action, $title) {
        if (isset($_GET['action']) && $_GET['action'] == $action) {
            echo "<h2>" . $title . "</h2>";
        } else {
            echo "<h2>" . $title . "</h2>";
        }
    }

    public static function sendUserPwdMail($mail_to, $mail_from, $usertype, $username, $password) {
        $subject = "Your Login Credentials";
        if ($usertype == 'Parent') {
            $message = "	
			<table border=0 cellpadding=0 cellspacing=0>
				<tr><td colspan='2'>Dear $username, </td></tr>				
				<tr><td colspan='2'>Your account has been created as $usertype</td></tr>
				<tr><td>Email: </td><td align='left'>$mail_to</td></tr>
				<tr><td>Password: </td><td align='left'>$password</td></tr>				
				<tr><td colspan='2'>Administrator should be able to edit parent information.</td></tr>				  
				<tr><td colspan='2'>&nbsp;</td></tr>
			</table>";
        } else {
            $message = "	
			<table border=0 cellpadding=0 cellspacing=0>
				<tr><td colspan='2'>Dear $username, </td></tr>				
				<tr><td colspan='2'>Your account has been created as $usertype</td></tr>
				<tr><td>Email: </td><td align='left'>$mail_to</td></tr>
				<tr><td>Password: </td><td align='left'>$password</td></tr>				
				<tr><td colspan='2'>&nbsp;</td></tr>
			</table>";
        }
        $headers = "FROM: $mail_from\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        @mail($mail_to, $subject, $message, $headers);
    }

    public static function sendUserChangePwdMail($mail_to, $mail_from, $username, $password) {
        $subject = "Your Login Credentials changed";
        $message = "	
			<table border=0 cellpadding=0 cellspacing=0>
				<tr><td colspan='2'>Dear $username, </td></tr>				
				<tr><td colspan='2'>Your account password has been changed</td></tr>
				<tr><td>Email: </td><td align='left'>$mail_to</td></tr>
				<tr><td>New Password: </td><td align='left'>$password</td></tr>				
				<tr><td colspan='2'>&nbsp;</td></tr>
			</table>";
        $headers = "FROM: $mail_from\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        @mail($mail_to, $subject, $message, $headers);
    }

    public static function sendSuperAdminMail($mail_to, $mail_from, $username) {
        $subject = "User Details";
        $message = "	
			<table border=0 cellpadding=0 cellspacing=0>
				<tr><td colspan='2'>Hello</td></tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
				<tr><td colspan='2'>One user created...$username</td></tr>
				<tr><td colspan='2'>&nbsp;</td></tr>
			</table>";
        $headers = "FROM: $mail_from\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        @mail($mail_to, $subject, $message, $headers);
    }

    public static function sendChildDataMail($mail_to, $mail_from, $childname, $parentname) {
        $subject = "Child Details";
        $message = "	
			<table border=0 cellpadding=0 cellspacing=0>
				<tr><td>Hello</td></tr>				
				<tr><td>Parent Name: $parentname</td></tr>
				<tr><td>Child Name: $childname</td></tr>				
				<tr><td>&nbsp;</td></tr>
			</table>";
        $headers = "FROM: $mail_from\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        @mail($mail_to, $subject, $message, $headers);
    }

    public static function tomorrow($date) {
        $timestamp = strtotime(date("Y-m-d H:i:s", strtotime($date)) . " + 1 day");
        $expired = date('Y-m-d H:i:s', $timestamp);
        return $expired;
    }

    public static function ucword($string) {
        $get_string = ucwords($string);
        return $get_string;
    }

    public static function replaceComma($amount) {
        $get_amount = str_replace(",", "", $amount);
        return $get_amount;
    }

    public static function formatDate($date, $format) {
        //getdate();
        $get_date = date($format, strtotime($date));
        return $get_date;
    }

    public static function honoreeDate($date) {
        $timestamp = strtotime(str_replace('/', '-', $date));
        $get_date = date("d-m-Y", $timestamp);
        return $get_date;
    }

    public static function stringtolower($string) {
        $get_string = strtolower($string);
        return $get_string;
    }

    public static function currentDate($format) {
        if (strtolower($format) == 'd') {
            $currentDate = date('Y-m-d');
        } elseif (strtolower($format) == 't') {
            $currentDate = date('H:i:s');
        } elseif (strtolower($format) == 'dt') {
            $currentDate = date('Y-m-d h:i:s');
        } elseif (strtolower($format) == 'dt_img') {
            //use for create new image name
            $currentDate = date('ymdhis') . "_";
        }
        return $currentDate;
    }

    public static function getUrl($param, $scriptName = '') {
        if (empty($scriptName)) {
            $uriPart = SCRIPTNAME;
        } else {
            $uriPart = $scriptName . ".php";
        }
        $query_string = http_build_query($param, "&");
        $url = $uriPart . '?' . $query_string;
        return $url;
    }

    public static function wrapString($string, $position) {
        $content = substr($string, 0, $position);
        return $content;
    }

    public static function checkLength($string, $length) {
        $content = strlen($string);

        if ($content < $length) {
            return true;
        } else {
            return false;
        }
    }

    public static function selectedOption($source_value, $desti_value) {
        if (trim($source_value) == trim($desti_value)) {
            $value = 'selected="selected"';
        } else {
            $value = '';
        }
        return $value;
    }

    public static function getSuccessMsg($getSuccMsg, $options) {
        foreach ($options as $option_key => $option_value) {
            if (trim($option_key) == trim($getSuccMsg)) {
                $setSuccMsg = $option_value;
            }
        }
        return $setSuccMsg;
    }

    public static function strRandom($a = 10) {
        $b = NULL;
        $c = $d = NULL;
        for ($i = 0; $i < $a; $i++) {
            $c = chr(rand(48, 122));
            while (!preg_match("/[a-zA-Z]/", $c)) {
                if ($c == $d)
                    continue;
                $c = chr(rand(48, 90));
            }
            $b .= $c;
            $d = $c;
        }
        return strtolower($b) . "_";
    }

    public static function NumericRandom($a = 3) {
        $b = NULL;
        $c = $d = NULL;
        for ($i = 0; $i < $a; $i++) {
            $c = chr(rand(48, 122));
            while (!preg_match("/[0-9]/", $c)) {
                if ($c == $d)
                    continue;
                $c = chr(rand(48, 90));
            }
            $b .= $c;
            $d = $c;
        }
        return (int) $b;
    }

    public static function compareValue($paramOne, $paramTwo) {
        if (!strcmp($paramOne, $paramTwo) == 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function setSubTitle($titleData) {
        if (isset($_REQUEST['action'])) {
            $action = $_REQUEST['action'];
            foreach ($titleData as $actionKey => $actionValue) {
                if ($action == $actionKey) {
                    $actionCase = $actionValue;
                }
            }
            return $actionCase;
        } else {
            return '&nbsp;';
        }
    }

    public static function getFullNamePart($fullName) {
        //$nameData = array();

        $expFullName = explode(" ", $fullName);
        $nameFullFirst = $expFullName[0];
        $nameFullFirstLetter = substr($nameFullFirst, 0, 1);

        $nameFullLast = $expFullName[2];
        $nameFullLastLetter = substr($nameFullLast, 0, 1);

        if (count($expFullName) == 3) {
            $nameFullMiddle = $expFullName[1];
        } else {
            $nameFullMiddle = NULL_VALUE;
        }

        return array("nameFullFirst" => $nameFullFirst,
            "nameFullFirstLetter" => $nameFullFirstLetter,
            "nameFullLast" => $nameFullLast,
            "nameFullLastLetter" => $nameFullLastLetter,
            "nameFullMiddle" => $nameFullMiddle);
    }

    public static function getFirstLetter($string) {
        $getFirstLetter = substr($string, 0, 1);
        return $getFirstLetter;
    }

    public static function getTimeStamps($curr_date = '') {

        if (Extra::isBlank($curr_date)) {
            $date = date("Y-m-d h:i:s");
        } else {
            $date = date("Y-m-d h:i:s", strtotime($curr_date));
        }
        $date = new DateTime($date);
        return $date->getTimestamp();
    }

    public static function setTimeStamps($timestamp) {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        echo $date->format('Y-m-d H:i:s') . "\n";
    }

}

?>
