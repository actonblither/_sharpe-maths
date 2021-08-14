<?php
$_page = new _pages();
?>
<div id = 'page-outer-container'>
<ul class='item-admin p0'>
	<li class = 'row point expand'>
		<div class='folder-img'>
			<img alt='Open' aria-label = 'Click to open the page list.' title='Click to open the page list.' id='o_page' class='ttip point open-list' src='<?php echo __s_lib_icon_url__?>closed.png' data-list-id='page-admin' data-img-cl='c_page' data-img-op='o_page' width='32' height='32'>
			<img alt='Close' aria-label = 'Click to close the page list.' title='Click to close the page list.' id='c_page' class='ttip point open-list hidden' src='<?php echo __s_lib_icon_url__?>opened.png' data-list-id='page-admin' data-img-cl='c_page' data-img-op='o_page' width='32' height='32'>
		</div>
		<div><h3 class='normal ml10'>Pages</h3></div>
	</li>
</ul>


<?php
echo $_page->_build_admin_page_list();
?>
</div>