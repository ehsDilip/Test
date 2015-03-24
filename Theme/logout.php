<?php

include "../include/config.php";

/* * ************* start destroy the session ************** */
if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
    session_unset($_SESSION['user_id']);
    session_unset($_SESSION['user_name']);
    session_destroy();
} else {
    session_destroy();
}
/* * ************* end destroy the session ************** */

// redirect login page
Extra::redirect('login');
?>
