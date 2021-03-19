<?php
class _navmenu{
	private $_dbh;
	private $_parent_id = 1;
	private $_topic_order = array();
	private $_menu;

	public function __construct(){
		$this->_dbh = new _db();
		$output = '';
		$tmp = '';
		$this->_build_navmenu($output, 0);
		$tmp .= $this->_parse_menu_list($output);
		$this->_menu = $tmp;
	}



	private function _parse_menu_list($output){
		$tmp = "<ul class = 'nav-menu-side'>";
		$arr = array_filter(explode('**', $output));
		$_start_child = array();
		$_end_child = array();

		for ($i = 0; $i < count($arr); $i++){
			$_line = explode('||', $arr[$i]);
			$_depth[$i] = rvz($_line[0]);
			$_id[$i] = rvz($_line[1]);
			$_title[$i] = rvs($_line[2]);
			$_page_id[$i] = rvz($_line[3]);
			$_link[$i] = rvz($_line[4]);
			$_pic_class[$i] = rvs($_line[5]);
		}

		for ($j = 0; $j < count($_depth); $j++){
			if (isset($_depth[$j+1]) && $_depth[$j+1] > $_depth[$j]){
				$_start_child[$j] = 1;
			}else{
				$_start_child[$j] = 0;
			}

			if (isset($_depth[$j-1]) && $_depth[$j] < $_depth[$j-1]){
				$_end_child[$j-1] = abs($_depth[$j-1]-$_depth[$j]);
				//This is to deal with the issue of requiring more than one </ul></li> if the last child is the last item in the submenu
			}else{
				$_end_child[$j-1] = 0;
			}
		}

		// Now build the list
		for ($k = 0; $k < count($_depth); $k++){
			//Get the menu class and add it to the li if it exists
			if (!empty($_pic_class[$k])){$_lclass = ' bg_img '.$_pic_class[$k];}else{$_lclass = '';}
			if (isset($_page_id[$k]) && $_page_id[$k] > 0){
				$_link_data = " data-main = 'page' ";
			}else{
				$_link_data = " data-main = 'topic' ";
			}
			$_element = 'uxp'.$_id[$k];
			$_x = rvs($_COOKIE[$_element], 'c');

			if ($_x == 'c'){ $_class = 'hidden'; }else{ $_class = ''; }
			if ($_start_child[$k]){
				if ($_link[$k]){
					$tmp .= "<li id='navli".$_id[$k]."' class='link point".$_lclass."' data-id = '".$_id[$k]."' ".$_link_data.">".$_title[$k]."<ul id='uxp".$_id[$k]."' class='".$_class."'>";
				}else{
					$tmp .= "<li id='navli".$_id[$k]."' class='expand point".$_lclass."' data-id = '".$_id[$k]."'><span class='point w100pc'>".$_title[$k]."</span><ul class='".$_class."' id = 'uxp".$_id[$k]."'>";
				}
			}else{
				if ($_link[$k]){
					$tmp .= "<li id='navli".$_id[$k]."' class='link point mb2".$_lclass."' data-id = '".$_id[$k]."' ".$_link_data.">".$_title[$k]."</li>";
				}else{
					$tmp .= "<li id='navli".$_id[$k]."' class='expand point".$_lclass."' data-id = '".$_id[$k]."'><span class='point w100pc'>".$_title[$k]."</span></li>";
				}
			}
			if (rvz($_end_child[$k]) > 0){
				for($i = 0; $i < $_end_child[$k]; $i++){
					$tmp .= "</ul></li>";
				}
			}
		}
		$tmp .= "</ul>";
		return $tmp;
	}

	private function _build_navmenu(&$output, $parent = 0, $indent = 0){
		$_sql = 'select * from _app_nav_routes where parent_id = :parent_id and display = 1 and archived = 0 order by order_num';
		$_d = array('parent_id' => $parent);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		foreach ($_rows as $_r) {
			$output .= $indent."||".$_r['id']."||".$_r['title']."||".$_r['page_id']."||".$_r['link']."||".$_r['class']."**";
			if ($_r['link'] === 1 && empty($_r['page_id'])){
				$this->_topic_order[] = $_r['id'];
			}
			if ($_r['id'] != $parent) {
				$this->_build_navmenu($output, $_r['id'], $indent + 1);
			}
		}
	}

	public function _get_navmenu(){ return $this->_menu; }
	public function _get_topic_order(){ return $this->_topic_order; }
}