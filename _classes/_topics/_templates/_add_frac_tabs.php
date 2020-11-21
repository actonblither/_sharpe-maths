<?php

$llinks[0] = 'index.php?main=topic&mode=intro';
$ltitles[0] = 'Introduction';
$lfields[0] = 'intro';
$lclass[0] = 'gen';
$lhelp[0] = 'This is an general introduction to the adding fractions skillset.';

$llinks[1] = 'index.php?main=topic&mode=lesson';
$ltitles[1] = 'Lessons';
$lfields[1] = 'lesson';
$lclass[1] = 'gen';
$lhelp[1] = 'This is a lesson on the adding fractions skillset.';

$llinks[2] = 'index.php?main=topic&mode=eg';
$ltitles[2] = 'Examples';
$lfields[2] = 'eg';
$lclass[2] = 'gen';
$lhelp[2] = '';

$llinks[3] = 'index.php?main=topic&mode=ex';
$ltitles[3] = 'Exercises';
$lfields[3] = 'ex';
$lclass[3] = 'gen';
$lhelp[3] = '';

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
		$tm->_set_main('topic');
		$tm->_make_tabs();
		echo $tm->_get_code();
	?>
</div>