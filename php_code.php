<?php
include("app_config.php");
$_sql = "select * from _app_tips";
$_dbh = new _db();

$_r = $_dbh->_fetch_db_rows($_sql);
foreach ($_r as $r){
	if (substr($r['body'], 0, 3) == "<p>"){
		$_id = $r['id'];
		$_nb = "<div class='w400'>".$r['body']."</div>";
		$_sql = "update _app_tips set body = :body where id = :id";
		$_d = array('body' => $_nb, 'id' => $_id);
		$_f = array('s', 'i');
		$_res = $_dbh->_update_sql($_sql, $_d, $_f);
	}
}
 

?>