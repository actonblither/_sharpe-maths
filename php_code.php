<?php
include ("app_config.php");

$_dbh=new _db();

$_sql = 'select id, intro from _app_topic';
$_rows = $_dbh->_fetch_db_rows($_sql);
echo count($_rows);
_cl($_rows);
/* foreach ($_rows as $_r){
	$_intro = str_replace('', '', $_r['intro']);


	$_sql = "update _app_topic set intro = :intro where id = :id";
	$_d = array('intro' => $_intro, 'id' => $_r['id']);
	$_f = array('s', 'i');

	//$_result = $_dbh->_update_sql($_sql, $_d, $_f);
	if ($_result){
		echo 'Updated'."<br />";
	}
} */

echo 'Complete';

?>