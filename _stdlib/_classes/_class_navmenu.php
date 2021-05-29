<?php
class _navmenu extends _setup{
	private $_topic_order = array();
	private $_menu;
	private $_admin;
	private $_main_db_tbl = '_app_nav_routes';
	private $_make_template = false;
	private $_output = '';
	private $_title_width = 'w400';
	private $_nav_id_width = 'w100';
	private $_page_id_width = 'w250';
	private $_topic_id_width = 'w250';
	private $_parent_id_width = 'w250';
	private $_link_width = 'w100';
	private $_order_num_width = 'w100';
	private $_display_width = 'w100';
	private $_expand_width = 'w30';
	private $_delete_width = 'w20';

	private $_title_input_width = 390;
	private $_select_width = 240;

	private $_depth = array();
	private $_nid = array();
	private $_title = array();
	private $_page_id = array();
	private $_link_chk = array();
	private $_link = array();
	private $_parent_id = array();
	private $_topic_id = array();
	private $_order_num = array();
	private $_display_chk = array();
	private $_pic_class = array();
	private $_placeholder_txt = 'Text to appear in the menu entry...';

	public function __construct(){
		parent::__construct();

	}

	public function _build_site_nav_menu(){
		$output = '';
		$this->_build_navmenu($output, 0);
		return $this->_parse_menu_list($output);
	}

	public function _build_admin_nav_menu_list(){
		$output = '';
		if (!is_logged_in()) die();
		$this->_build_admin_navmenu($output, 0);
		return $this->_parse_admin_list($output);
	}

	public function _build_empty_template(){
		if (!is_logged_in()) die();
		return $this->_parse_admin_template_list();
	}



	private function _build_admin_navmenu(&$output, $parent = 0, $indent = 0){
		$_sql = 'select * from '.$this->_main_db_tbl.' where parent_id = :parent_id and archived = 0 order by order_num';
		$_d = array('parent_id' => $parent);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		foreach ($_rows as $_r) {
			$output .= $indent."||".$_r['id']."||".$_r['title']."||".$_r['page_id']."||".$_r['link']."||".$_r['parent_id']."||".$_r['topic_id']."||".$_r['order_num']."||".$_r['display']."**";
			if ($_r['link'] === 1 && empty($_r['page_id'])){
				$this->_topic_order[] = $_r['id'];
			}
			if ($_r['id'] != $parent) {
				$this->_build_admin_navmenu($output, $_r['id'], $indent + 1);
			}
		}
	}


	private function _build_col_headers(){
		return "<li class = 'col_header row'>
			<div class='".$this->_delete_width." row center'></div>
			<div class='".$this->_expand_width." row center'></div>
			<div class='".$this->_title_width." row center'>Title</div>
			<div class='".$this->_nav_id_width." row center'>Nav ID</div>
			<div class='".$this->_page_id_width." row center'>Page ID</div>
			<div class='".$this->_topic_id_width." row center'>Topic ID</div>
			<div class='".$this->_parent_id_width." row center'>Parent ID</div>
			<div class='".$this->_link_width." row center'>Link</div>
			<div class='".$this->_display_width." row center'>Display</div>
		</li>";
	}

	private function _parse_admin_template_list(){
		$_sql = "insert into ".$this->_main_db_tbl." set display=:display, parent_id = 0";
		$_d = array('display' => 1);
		$_f = array('i');

		$this->_nid[0] = $this->_dbh->_insert_sql($_sql, $_d, $_f);

		$_link_data = " data-main = 'topic' data-db-tbl='".$this->_main_db_tbl."' data-sort-list-prefix='navli' data-del-list = '1'";

		$_del_img_path = __s_lib_icon_url__."close14.png";
		$_del_div = "<div class='".$this->_delete_width." row center'><img class = 'del-item' src='".$_del_img_path."' alt='Delete' data-id = '".$this->_nid[0]."' data-db-tbl = '_app_nav_routes' /></div>";

		$this->_title[0] = $this->_hlf->_build_text_box('title', '', $this->_main_db_tbl, $this->_nid[0], $this->_title_input_width, 'Text to appear in the menu entry...');
		$_sql = "select id, title from __sys_pages where display=1 and archived = 0 order by title";
		$this->_page_id[0] = $this->_hlf->_build_select('page_id', 0, $this->_main_db_tbl, $this->_nid[0], $_sql, $this->_select_width);
		$_sql = "select id, title from _app_topic where display=1 and archived = 0 order by title";
		$this->_topic_id[0] = $this->_hlf->_build_select('topic_id', 0, $this->_main_db_tbl, $this->_nid[0], $_sql, $this->_select_width);
		$_sql = "select id, title from _app_nav_routes where display=1 and archived = 0 order by title";
		$this->_parent_id[0] = $this->_hlf->_build_select('parent_id', 0, $this->_main_db_tbl, $this->_nid[0], $_sql, $this->_select_width, true);
		$this->_link_chk[0] = $this->_hlf->_build_chkbox('link', 0, $this->_main_db_tbl, $this->_nid[0]);
		$this->_display_chk[0] = $this->_hlf->_build_chkbox('display', 1, $this->_main_db_tbl, $this->_nid[0]);

		$_col_headers = $this->_build_col_headers();
		$_content_divs = "
<div class='".$this->_title_width." row center'>".$this->_title[0]."</div>
<div class='".$this->_nav_id_width." row center'>".$this->_nid[0]."</div>
<div class='".$this->_page_id_width." row center'>".$this->_page_id[0]."</div>
<div class='".$this->_topic_id_width." row center'>".$this->_topic_id[0]."</div>
<div class='".$this->_parent_id_width." row center'>".$this->_parent_id[0]."</div>
<div class='".$this->_link_width." row center'>".$this->_link_chk[0]."</div>
<div class='".$this->_display_width." row center'>".$this->_display_chk[0]."</div>
";

		$_expand_not = "<div class='w30'></div>";

		$tmp = "<ul id = 'navul".$this->_nid[0]."' class='nav-menu-admin'>".$_col_headers;
		$tmp .= "<li id='navli".$this->_nid[0]."' class='row link point' data-id = '".$this->_nid[0]."' ".$_link_data.">".$_del_div.$_expand_not.$_content_divs;
		$tmp .= "</li></ul>";
		return $tmp;
	}


	private function _parse_admin_list($output){

		$_col_headers = $this->_build_col_headers();

		$tmp = "<div id = 'item-admin' class='p10'><button class='add_new add w150' data-db-tbl = '_app_nav_routes' data-prefix='nav'>Add new nav entry</button><ul class = 'item-admin sortable-list'>".$_col_headers;
		$arr = array_filter(explode('**', $output));
		$_start_child = array();
		$_end_child = array();

		for ($i = 0; $i < count($arr); $i++){
			$_line = explode('||', $arr[$i]);
			$this->_depth[$i] = rvz($_line[0]);
			$this->_nid[$i] = rvz($_line[1]);
			$this->_title[$i] = rvs($_line[2]);
			$this->_page_id[$i] = rvz($_line[3]);
			$this->_link_chk[$i] = rvz($_line[4]);
			$this->_link[$i] = rvz($_line[4]);
			$this->_parent_id[$i] = rvz($_line[5]);
			$this->_topic_id[$i] = rvz($_line[6]);
			$this->_order_num[$i] = rvz($_line[7]);
			$this->_display_chk[$i] = rvz($_line[8]);
		}




		for ($j = 0; $j < count($this->_depth); $j++){
			if (isset($this->_depth[$j+1]) && $this->_depth[$j+1] > $this->_depth[$j]){
				$_start_child[$j] = 1;
			}else{
				$_start_child[$j] = 0;
			}

			if (isset($this->_depth[$j-1]) && $this->_depth[$j] < $this->_depth[$j-1]){
				$_end_child[$j-1] = abs($this->_depth[$j-1]-$this->_depth[$j]);
				//This is to deal with the issue of requiring more than one </ul></li> if the last child is the last item in the submenu
			}else{
				$_end_child[$j-1] = 0;
			}
		}

		// Now build the list
		for ($k = 0; $k < count($this->_depth); $k++){

			if (isset($this->_page_id[$k]) && $this->_page_id[$k] > 0){
				$_link_data = " data-main = 'page' data-db-tbl='".$this->_main_db_tbl."' data-sort-list-prefix='navli' data-del-list = '0' ";
			}else{
				$_link_data = " data-main = 'topic' data-db-tbl='".$this->_main_db_tbl."' data-sort-list-prefix='navli' data-del-list = '0' ";
			}


			$this->_title[$k] = $this->_hlf->_build_text_box('title', $this->_title[$k], $this->_main_db_tbl, $this->_nid[$k], 390, 'Text to appear in the menu entry...');
			$_sql = "select id, title from __sys_pages where display=1 and archived = 0 order by title";
			$this->_page_id[$k] = $this->_hlf->_build_select('page_id', $this->_page_id[$k], $this->_main_db_tbl, $this->_nid[$k], $_sql, 240);


			$_sql = "select id, title from _app_topic where display=1 and archived = 0 order by title";
			$this->_topic_id[$k] = $this->_hlf->_build_select('topic_id', $this->_topic_id[$k], $this->_main_db_tbl, $this->_nid[$k], $_sql, 240);

		$_sql = "select id, parent_id, title from _app_nav_routes where display=1 and archived = 0 order by title";
		$this->_parent_id[$k] = $this->_hlf->_build_select('parent_id', $this->_parent_id[$k], $this->_main_db_tbl, $this->_nid[$k], $_sql, 240, true);

		$this->_link_chk[$k] = $this->_hlf->_build_chkbox('link', $this->_link_chk[$k], $this->_main_db_tbl, $this->_nid[$k]);
		$this->_display_chk[$k] = $this->_hlf->_build_chkbox('display', $this->_display_chk[$k], $this->_main_db_tbl, $this->_nid[$k]);


			$_content_divs = "

<div class='".$this->_title_width." row center'>".$this->_title[$k]."</div>
<div class='".$this->_nav_id_width." row center'>".$this->_nid[$k]."</div>
<div class='".$this->_page_id_width." row center'>".$this->_page_id[$k]."</div>
<div class='".$this->_topic_id_width." row center'>".$this->_topic_id[$k]."</div>
<div class='".$this->_parent_id_width." row center'>".$this->_parent_id[$k]."</div>
<div class='".$this->_link_width." row center'>".$this->_link_chk[$k]."</div>
<div class='".$this->_display_width." row center'>".$this->_display_chk[$k]."</div>
";
			$_expand_img = "<div class='exp w30'><img class='m4 exp' src='".__s_app_icon_url__."expand20.png' alt='Exp'  data-id='".$this->_nid[$k]."'/></div>";
		$_expand_not = "<div class='w30'></div>";
		$_del_img_path = __s_lib_icon_url__."close14.png";
		$_del_div = "<div class='".$this->_delete_width." row center'><img class = 'del-item' src='".$_del_img_path."' alt='Delete' data-id = '".$this->_nid[$k]."' data-db-tbl = '_app_nav_routes' /></div>";

		$_element = 'uxa'.$this->_nid[$k];
			$_x = rvs($_COOKIE[$_element], 'c');
			if ($_x == 'c'){ $_class = 'hidden '; }else{ $_class = ''; }
			if ($_start_child[$k]){
				if ($this->_link[$k]){
					$tmp .= "<li id='navli".$this->_nid[$k]."' class='row link point' data-id = '".$this->_nid[$k]."' ".$_link_data.">".$_del_div.$_expand_not.$_content_divs."<ul id='uxa".$this->_nid[$k]."' class='".$_class."sortable-list'>".$_col_headers;
				}else{
					$tmp .= "<li id='navli".$this->_nid[$k]."' class='col expand point' data-id = '".$this->_nid[$k]."' ".$_link_data."><div class='row'>".$_del_div.$_expand_img.$_content_divs."</div><ul class='".$_class."sortable-list' id = 'uxa".$this->_nid[$k]."'>".$_col_headers;
				}
			}else{
				if ($this->_link[$k]){
					$tmp .= "<li id='navli".$this->_nid[$k]."' class='row link point mb2"."' data-id = '".$this->_nid[$k]."' ".$_link_data.">".$_del_div.$_expand_not.$_content_divs."</li>";
				}else{
					$tmp .= "<li id='navli".$this->_nid[$k]."' class='row expand point' data-id = '".$this->_nid[$k]."'>".$_del_div.$_expand_img.$_content_divs."</li>";
				}
			}
			if (rvz($_end_child[$k]) > 0){
				for($i = 0; $i < $_end_child[$k]; $i++){
					$tmp .= "</ul></li>";
				}
			}
		}
		$tmp .= "</ul></div>";
		return $tmp;
	}

	private function _build_navmenu(&$output, $parent = 0, $indent = 0){
		$_sql = 'select * from '.$this->_main_db_tbl.' where parent_id = :parent_id and display = 1 and archived = 0 order by order_num';
		$_d = array('parent_id' => $parent);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		foreach ($_rows as $_r) {
			$output .= $indent."||".$_r['id']."||".$_r['title']."||".$_r['page_id']."||".$_r['link']."||".$_r['class']."**".PHP_EOL;
			if ($_r['link'] === 1 && empty($_r['page_id'])){
				$this->_topic_order[] = $_r['id'];
			}
			if ($_r['id'] != $parent) {
				$this->_build_navmenu($output, $_r['id'], $indent + 1);
			}
		}
	}

	private function _parse_menu_list($output){
		$tmp = "<div><ul class = 'nav-menu-side'>";
		$arr = array_filter(explode('**', $output));
		$_start_child = array();
		$_end_child = array();

		for ($i = 0; $i < count($arr); $i++){
			$_line = explode('||', $arr[$i]);
			$this->_depth[$i] = rvz($_line[0]);
			$this->_nid[$i] = rvz($_line[1]);
			$this->_title[$i] = rvs($_line[2]);
			$this->_page_id[$i] = rvz($_line[3]);
			$this->_link[$i] = rvz($_line[4]);
			$this->_pic_class[$i] = rvs($_line[5]);
		}

		for ($j = 0; $j < count($this->_depth); $j++){
			if (isset($this->_depth[$j+1]) && $this->_depth[$j+1] > $this->_depth[$j]){
				$_start_child[$j] = 1;
			}else{
				$_start_child[$j] = 0;
			}

			if (isset($this->_depth[$j-1]) && $this->_depth[$j] < $this->_depth[$j-1]){
				$_end_child[$j-1] = abs($this->_depth[$j-1]-$this->_depth[$j]);
				//This is to deal with the issue of requiring more than one </ul></li> if the last child is the last item in the submenu
			}else{
				$_end_child[$j-1] = 0;
			}
		}

		// Now build the list
		for ($k = 0; $k < count($this->_depth); $k++){
			//Get the menu class and add it to the li if it exists
			if (!empty($this->_pic_class[$k])){$_lclass = ' bg_img '.$this->_pic_class[$k];}else{$_lclass = '';}
			if (isset($this->_page_id[$k]) && $this->_page_id[$k] > 0){
				$_link_data = " data-main = 'page' ";
			}else{
				$_link_data = " data-main = 'topic' ";
			}
			$_element = 'uxp'.$this->_nid[$k];
			$_x = rvs($_COOKIE[$_element], 'c');

			if ($_x == 'c'){ $_class = 'hidden'; }else{ $_class = ''; }
			if (!empty($this->_title[$k])){
				if ($_start_child[$k]){
					if ($this->_link[$k]){
						$tmp .= "<li id='navli".$this->_nid[$k]."' class='link point".$_lclass."' data-id = '".$this->_nid[$k]."' ".$_link_data.">".$this->_title[$k]."<ul id='uxp".$this->_nid[$k]."' class='".$_class."'>";
					}else{
						$tmp .= "<li id='navli".$this->_nid[$k]."' class='expand point".$_lclass."' data-id = '".$this->_nid[$k]."'><span class='point w100pc'>".$this->_title[$k]."</span><ul class='".$_class."' id = 'uxp".$this->_nid[$k]."'>";
					}
				}else{
					if ($this->_link[$k]){
						$tmp .= "<li id='navli".$this->_nid[$k]."' class='link point mb2".$_lclass."' data-id = '".$this->_nid[$k]."' ".$_link_data.">".$this->_title[$k]."</li>";
					}else{
						$tmp .= "<li id='navli".$this->_nid[$k]."' class='expand point".$_lclass."' data-id = '".$this->_nid[$k]."'><span class='point w100pc'>".$this->_title[$k]."</span></li>";
					}
				}
			}
			if (rvz($_end_child[$k]) > 0){
				for($i = 0; $i < $_end_child[$k]; $i++){
					$tmp .= "</ul></li>";
				}
			}
		}
		$tmp .= "</ul></div>";
		return $tmp;
	}

	public function _get_dbh() { return $this->_dbh; }
	public function _get_parent_id() { return $this->_parent_id; }
	public function _get_topic_order() { return $this->_topic_order; }
	public function _get_menu() { return $this->_menu; }
	public function _get_main_db_tbl() { return $this->_main_db_tbl; }
	public function _get_make_template() { return $this->_make_template; }

	public function _set_dbh($_t) { $this->_dbh = $_t; }
	public function _set_parent_id($_t) { $this->_parent_id = $_t; }
	public function _set_topic_order($_t) { $this->_topic_order = $_t; }
	public function _set_menu($_t) { $this->_menu = $_t; }
	public function _set_main_db_tbl($_t) { $this->_main_db_tbl = $_t; }
	public function _set_make_template($_t) { $this->_make_template = $_t; }
}