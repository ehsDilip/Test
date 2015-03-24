<?php

include "../include/config.php";

if (isset($_POST['loginIn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (Extra::isBlank($username)) {
        $error[] = "Please enter username.";
    }
    if (Extra::isBlank($password)) {
        $error[] = "Please enter password.";
    }

    if (count($error) == 0) {
        $get_user = "SELECT * FROM " . TAB_USERS . " WHERE user_name = " . $dbh->sqlsafe($username) . " AND user_pass = " . $dbh->sqlsafe(md5($password)) . " ";
        $res_user = $dbh->select($get_user);

        if (count($res_user) > 0) {
            $_SESSION['user_id'] = $res_user[0]['user_id'];
            $_SESSION['user_name'] = $res_user[0]['user_name'];
            Extra::redirect('index');
        } else {
            $error[] = "Invalid username or password. Please try again.";
        }
    }
}
?>