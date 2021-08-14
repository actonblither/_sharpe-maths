<?php
$_nav = new _navmenu();
?>

<div id = 'nav-outer-container'>
<ul class='item-admin p0'>
	<li class = 'row point expand'>
		<div class='folder-img'>
			<img alt='Open' aria-label = 'Click to open the navigation menu list.' title='Click to open the navigation menu list.' id='o_nav' class='ttip point open-list' src='<?php echo __s_lib_icon_url__?>closed.png' data-list-id='nav-admin' data-img-cl='c_nav' data-img-op='o_nav' width='32' height='32'>
			<img alt='Close' aria-label = 'Click to close the navigation menu list.' title='Click to close the navigation menu list.' id='c_nav' class='ttip point open-list hidden' src='<?php echo __s_lib_icon_url__?>opened.png' data-list-id='nav-admin' data-img-cl='c_nav' data-img-op='o_nav' width='32' height='32'>
		</div>
		<div><h2 class='normal ml10'>Navigation menu</h2></div>
	</li>
</ul>


<?php
echo $_nav->_build_admin_nav_menu_list();
$_SESSION['s_topic_order'] = $_nav->_get_topic_order();
?>
</div>