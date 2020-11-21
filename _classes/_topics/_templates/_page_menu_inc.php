<?php
$_sql = 'select * from __sys_pages where main = :main and mode = :mode and parent_id = ';
$_d = array('main' => $this->_main, 'mode' => $this->_mode);
$_f = array('s', 's');

$llinks[0] = 'index.php?main=home&mode=intro';
$ltitles[0] = 'Introduction to Mathematics';
$lfields[0] = 'maintro';
$lclass[0] = 'gen';
$lhelp[0] = 'This is the main homepage, where all is made clear.';


//These lines of code remove all empty array values renumbering the array
$llinks=array_values(array_filter($llinks));
$lfields=array_values(array_filter($lfields));
$ltitles=array_values(array_filter($ltitles));
$lclass=array_values(array_filter($lclass));
$lhelp=array_values(array_filter($lhelp));
?>
<div class = 'both lirow tab_menu'>
	<?php
		$ldefault = 'maintro';
		$lfield = 'mode';
		$tm = new _tab_menu($ltitles, $llinks, $lfields, $lfield, $ldefault, $lclass, $lhelp);
		$tm->_set_main('home');
		$tm->_set_mode('cal');
		$tm->_make_tabs();
		echo $tm->_get_code();
	?>
</div>