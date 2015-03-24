<?php
include 'library.php';

$action = $_REQUEST["action"];
switch ($action) {
    case "fblogin":
        include 'facebook.php';
        $appid = "679880192067529";
        $appsecret = "f42a666a53581c86447aaac965db3129";
        $facebook = new Facebook(array(
            'appId' => $appid,
            'secret' => $appsecret,
            'cookie' => TRUE,
        ));
        $fbuser = $facebook->getUser();
        if ($fbuser) {
            try {
                $user_profile = $facebook->api('/me');
            } catch (Exception $e) {
                echo $e->getMessage();
                exit();
            }
            
            echo "<pre>";
            print_r($user_profile);
            echo "</pre>";
            
            $user_fbid = $fbuser;
            $user_email = $user_profile["email"];
            $user_fnmae = $user_profile["first_name"];
            
            $user_image = "https://graph.facebook.com/" . $user_fbid . "/picture?type=large";
            
            $check_select = mysql_num_rows(mysql_query("SELECT * FROM `fblogin` WHERE email = '$user_email'"));
            if ($check_select > 0) {
                mysql_query("INSERT INTO `fblogin` (fb_id, name, email, image, postdate) VALUES ('$user_fbid', '$user_fnmae', '$user_email', '$user_image', '$now')");
            }
        }
        break;
}
?>
Fb id:<?php echo $user_fbid; ?>
<br/>
Image : <img src="<?php echo $user_image; ?>" height="100"/>
<br/>
Email:<?php echo $user_email; ?>
