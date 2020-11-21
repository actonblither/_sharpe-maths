<?php
header('Content-Type: text/html; charset=utf-8;');
error_reporting(E_ALL);
//error_reporting(E_ERROR && ~E_WARNING && ~E_NOTICE);
date_default_timezone_set('Europe/London');

session_start();

if (isset($_SESSION['s_is_logged_in'])){$GLOBALS['s_is_logged_in'] = $_SESSION['s_is_logged_in'];}
if (isset($_SESSION['s_auid'])){$GLOBALS['s_auid'] = $_SESSION['s_auid'];}
if (isset($_SESSION['s_session_id'])){$GLOBALS['session_id'] = $_SESSION['s_session_id'];}
if (isset($_SESSION['s_show_tooltips'])){$GLOBALS['s_show_tooltips'] = $_SESSION['s_show_tooltips'];}
if (isset($_SESSION['s_sticky_navbar'])){$GLOBALS['s_sticky_navbar'] = $_SESSION['s_sticky_navbar'];}

include('includes.php');
?>
