<?php
$base_folder = __DIR__;
$base_folder = str_replace('\\', '/', $base_folder);

define('__s_lib_folder__', $base_folder.'/_stdlib/');
define('__s_app_folder__', $base_folder.'/');
define('__s_app_url__', 'http://localhost/sharpe-maths/');
define('__s_lib_url__', 'http://localhost/sharpe-maths/_stdlib/');
define('__s_icon_url__', 'http://localhost/sharpe-maths/_stdlib/_images/_icons/');
define('__s_applib_url__', __s_lib_url__.'_applib/');
define('__s_app_title__', 'Sharpe-Maths Teaching Guide');
define('__s_cfg_ini_pth__', 'C:/AppServ/_smath_cfg/_smath_cfg_v2.ini');
define('__s_session_folder__', 'C:/AppServ/_smath_v2_sessions/');
define('__s_single_tab_approach__', true);
define('__s_app_login_screen_logo_path__', __s_app_folder__.'_images/_icons/app_logo.png');

include(__s_lib_folder__.'_lib/_stdlib.php');

include(__s_lib_folder__.'_classes/_db_connect/_class_db_connect.php');
include(__s_lib_folder__.'_classes/_login/_class_login.php');
include(__s_lib_folder__.'_classes/_class_setup.php');
include(__s_lib_folder__.'_classes/_class_tabs.php');
include(__s_lib_folder__.'_classes/_class_tab_menu.php');
include(__s_lib_folder__.'_classes/_navmenu/_class_navmenu.php');
include(__s_lib_folder__.'_classes/_config/_class_cfg.php');
include(__s_lib_folder__.'_classes/_pages/_class_pages.php');
include(__s_lib_folder__.'_classes/_list/_class_list.php');
include(__s_lib_folder__.'_classes/_form_elements/_class_form_element.php');
include(__s_app_folder__.'_classes/_config/_class_cfg.php');
include(__s_app_folder__.'_classes/_topics/_config/_cfg_topic_tpl.php');
include(__s_app_folder__.'_classes/_topics/_class_topic.php');
$_SESSION['s_version'] = 'v2.10';
$_SESSION['s_sticky_navbar'] = 0;
if (!isset($_SESSION['s_main'])){$_SESSION['s_main'] = 'page';}

ob_start(null, 0, PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_REMOVABLE);
?>