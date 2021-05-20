<?php
$_topic = new _topic();
?>

<div id = 'topic-outer-container'>
<ul class='item-admin p0'>
	<li class = 'row point expand'>
		<div class='folder-img'>
			<img alt='Open' title='Click to open the topic list.' id='o_topic' class='ttip point open-list' src='<?php echo __s_lib_icon_url__?>closed.png' data-list-id='topic-admin' data-img-cl='c_topic' data-img-op='o_topic' width='32' height='32'>
			<img alt='Close' title='Click to close the topic list.' id='c_topic' class='ttip point open-list hidden' src='<?php echo __s_lib_icon_url__?>opened.png' data-list-id='topic-admin' data-img-cl='c_topic' data-img-op='o_topic' width='32' height='32'>
		</div>
		<div><h3 class='normal ml10'>Topics</h3></div>
	</li>
</ul>


<?php
echo $_topic->_build_admin_topic_list();
?>
</div>