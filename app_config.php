<?php
header('Content-Type: text/html; charset=utf-8;');
error_reporting(E_ALL);
//error_reporting(E_ERROR && ~E_WARNING && ~E_NOTICE);
date_default_timezone_set('Europe/London');


define('__s_cfg_ini_pth__', 'G:/_smath_cfg/_smath_cfg_v2.ini');
//define('__s_cfg_ini_pth__', '/home/sharpsof/_smath_cfg/_smath_cfg_v2.ini');

$ini_array = parse_ini_file(__s_cfg_ini_pth__, true);

session_start();

define('__s_lib_folder__', $ini_array['paths']['drive'].$ini_array['paths']['lib_folder']);
define('__s_app_folder__', $ini_array['paths']['drive'].$ini_array['paths']['app_folder']);
define ('__s_doc_folder__', $ini_array['paths']['drive'].$ini_array['paths']['doc_folder']);

include(__s_lib_folder__.'_classes/_class_db_connect.php');
include(__s_lib_folder__.'_lib/_stdlib.php');

if (!isset($_SESSION['s_ref'])){
	$_SESSION['s_ref'] = $_SERVER['HTTP_REFERER'];

}

if (instr('sharpe-maths/', $_SESSION['s_ref']) || instr('sharpe-maths', $_SESSION['s_ref'])){
	$_SESSION['s_ref'] = str_replace('sharpe-maths/', '', $_SESSION['s_ref']);
	$_SESSION['s_ref'] = str_replace('sharpe-maths', '', $_SESSION['s_ref']);
}

$_app_url = str_replace('{_svr}', $_SESSION['s_ref'], $ini_array['urls']['app_url']);
$_lib_url = str_replace('{_svr}', $_SESSION['s_ref'], $ini_array['urls']['lib_url']);
$_doc_url = str_replace('{_svr}', $_SESSION['s_ref'], $ini_array['urls']['doc_url']);

define('__s_app_url__', $_app_url);
define('__s_lib_url__', $_lib_url);
define('__s_doc_url__', $_doc_url);

define('__s_lib_icon_url__', __s_lib_url__.'_images/_icons/');
define('__s_app_icon_url__', __s_app_url__.'_images/_icons/');
define('__s_applib_url__', __s_lib_url__.'_applib/');

define('__s_lib_icon_folder__', __s_lib_folder__.'_images/_icons/');
define('__s_app_icon_folder__', __s_app_folder__.'_images/_icons/');

define('__s_applib_folder__', __s_lib_folder__.'_applib/');
define('__s_app_title__', 'Sharpe-Maths Teaching Guide');
define('__s_app_login_screen_logo_path__', __s_app_folder__.'_images/_icons/app_logo.png');



include(__s_lib_folder__.'_classes/_login/_class_login.php');
include(__s_lib_folder__.'_classes/_class_setup.php');
include(__s_lib_folder__.'_classes/_class_tabs.php');
include(__s_lib_folder__.'_classes/_class_navmenu.php');
include(__s_lib_folder__.'_classes/_class_pages.php');
include(__s_lib_folder__.'_classes/_class_header_body.php');
include(__s_lib_folder__.'_classes/_small_classes.php');
include(__s_lib_folder__.'_classes/_class_contact.php');
include(__s_app_folder__.'_classes/_class_topic.php');
include(__s_app_folder__.'_classes/_class_topic_tab.php');
include(__s_app_folder__.'_classes/_class_glossary.php');
include(__s_app_folder__.'_classes/_class_glossary_all.php');
include(__s_app_folder__.'_classes/_class_puzzles.php');
include(__s_app_folder__.'_classes/_class_intro.php');
include(__s_app_folder__.'_classes/_class_exercise.php');
include(__s_app_folder__.'_classes/_class_example.php');
include(__s_app_folder__.'_classes/_class_activity.php');
include(__s_app_folder__.'_classes/_class_article.php');
include(__s_lib_folder__.'_classes/_form_elements/_class_form_element.php');
include(__s_lib_folder__.'_classes/_form_elements/_class_delete.php');
include(__s_lib_folder__.'_classes/_form_elements/_class_add_new.php');
include(__s_lib_folder__.'_classes/_form_elements/_class_hl_form_els.php');


$_SESSION['s_sticky_navbar'] = 1;
$_SESSION['s_version'] = 'v2.10';
if (!isset($_SESSION['s_main']) || !isset($_SESSION['s_id'])){
	$_SESSION['s_main'] = 'page';
	$_SESSION['s_id'] = 1;
}


ob_start(null, 0, PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_REMOVABLE);
?>
