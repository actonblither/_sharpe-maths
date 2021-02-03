<?php
class _navbar{
	private $_section_rows;
	private $_dbh;
	private $_topic_order = array();
	private $_main;

	public function __construct(){
		$this->_dbh = new _db();
		$this->_main = rvs($_REQUEST['main']);
		include_once(__s_lib_folder__.'/_classes/_navbar/_templates/_navbar_template.php');
	}

	private function _fetch_home_menu_entry(){
		$_home_row = $this->_fetch_home_item();
		$tmp = "<div class = 'nav-link home text ";
		if ($this->_main === 'home'){$tmp .= ' selected';}
		$tmp .= " bg-img home20' ";
		$tmp .= 'data-link = "index.php?main=';
		$tmp .= $_home_row['title']."\"><span class = 'text'>".$_home_row['title']."</span></div>\n\n";
		return $tmp;
	}

	private function _fetch_menu_sections(){
		$tmp = '';
		for ($i=0; $i<count($this->_section_rows); $i++){
			$tmp .= "<div id = '".$this->_section_rows[$i]['title']."_title' class = 'title bg-img ";
			$tmp .= $this->_section_rows[$i]['class']."'><span class = 'text'>";
			$tmp .= $this->_section_rows[$i]['title']."</span></div>";
			$tmp .= "<section id = '".$this->_section_rows[$i]['title']."'>";
			$_section_menu_items = $this->_fetch_section_menu_items($this->_section_rows[$i]['id']);
			for($j=0;$j<count($_section_menu_items);$j++){
				rv($_section_menu_items[$j]['title']);

				if ($_section_menu_items[$j]['title']==$this->_main && ($_section_menu_items[$j]['mode']==$this->_mode || empty($_section_menu_items[$j]['mode']))){$sel = ' selected ';}else{$sel = ' ';}

				$link = 'index.php?main='.$_section_menu_items[$j]['main'];
				if (!empty($_section_menu_items[$j]['mode'])){
					$link .= '&amp;mode='.$_section_menu_items[$j]['mode'];
				}
				if (!empty($_section_menu_items[$j]['c_id'])){
					$link .= '&amp;c_id='.$_section_menu_items[$j]['c_id'];
				}

				$tmp .= "<div class = 'nav-entry nav-link nm";
				if (!empty($sel)){$tmp .= ' '.$sel;}
				$tmp .= "bg-img ";
				$tmp .= $_section_menu_items[$j]['class']."' data-link = '".$link."'><span class = 'text'>";
				$tmp .= $_section_menu_items[$j]['title']."</span></div>";
	 		}
			$tmp .= "</section>";
		}
		return $tmp;
	}



	private function _fetch_menu_visibility_code(){
		$tmp = '';
		// Once the page is loaded, read cookies to find
		// 1.	Is the menu visible?
		$tmp .= "if (readCookie('navbar') == 0 || readCookie('navbar') == null){
			$('#navbar').addClass('hidden');
		}else{";
		if ($_SESSION['s_sticky_navbar'] == 0){
			$tmp .= "$('#navbar').addClass('hidden');";
		}else{
			$tmp .= "$('#navbar').removeClass('hidden');\n\n";
		}
		$tmp  .= "}";
		return $tmp;
	}

	private function _fetch_menu_section_visibility_code(){
		$tmp = PHP_EOL;
		// 2.	Are the menu sections visible?
		// Loop through menu array set up at the top of this page.
		$tmp .= "
		if (menus.length>0){
			for (var i=0; i < menus.length; i++)	{
				if (readCookie(menus[i]) == 0 || readCookie(menus[i]) == null){
					$('#'+menus[i]).addClass('hidden');
				}else{
					$('#'+menus[i]).removeClass('hidden');
				}
			}
		}";
		return $tmp;
	}



	private function _fetch_menu_toggle_jq(){
		$tmp = "$(document).on('click', '#menu', function(){
			if ($('#navbar').hasClass('hidden')){
				$('#navbar').removeClass('hidden');";
		if ($_SESSION['s_sticky_navbar'] == 1){
			$tmp .= "createCookie('navbar', 1, 5);";
		}
		$tmp .= "}else{
			$('#navbar').addClass('hidden');";
		if ($_SESSION['s_sticky_navbar'] == 1){
			$tmp .= "createCookie('navbar', 0, 5);";
		}
		$tmp .= "}
			});";
		return $tmp;
	}

	private function _fetch_menu_section_click_jq(){
		$tmp = "$(document).on('click', '.nav-link', function(){
			var url = $(this).attr('data-link');
			window.location.href = url;";
		if ($_SESSION['s_sticky_navbar'] == 0){
				$tmp .= "$('#navbar').addClass('hidden');";
			}
		$tmp .= "});";
		return $tmp;
	}

	private function _fetch_menu_section_toggle_jq(){
		$tmp = '';
		$this->_section_rows = $this->_fetch_section_rows();
		if (!empty($this->_section_rows)){
			for ($i=0; $i < count($this->_section_rows); $i++){
				$tmp .= "$(document).on('click', '#".$this->_section_rows[$i]['title']."_title', function(){";
				$tmp.= "var m = '".$this->_section_rows[$i]['title']."';";
				$tmp.= "var mm = '#'+m;
				if ($(mm).hasClass('hidden')){
					$(mm).removeClass('hidden');
					createCookie(m, 1, 5);
				}else{
					$(mm).addClass('hidden');
					createCookie(m, 0, 5);
				}});";
			}//end for loop
		}//end if block
		return $tmp;
	}

	private function _fetch_array_of_menu_sections(){
		$sql = 'select title from _app_nav_routes where parent_id = 0 and display = 1 and archived = 0';
		$_d = array('parent_id' => 0);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows($sql);
		//_cl($_rows);
		$tmp = '';
		if (!empty($_rows)){
			foreach ($_rows as $r){
				$tmp .= "'".$r['title']."',";
			}
		}
		$tmp=substr($tmp,0,-1);
		return $tmp;
	}

	private function _fetch_home_item(){
		$sql = "select * from _app_nav_routes where parent_id = 0 and archived = 0 and display = 1 order by order_num, id";
		$row = $this->_dbh->_fetch_db_row($sql);
		return $row;
	}

	private function _fetch_section_rows(){
		$sql = 'select * from _app_nav_routes where parent_id = :parent_id and archived = 0 and display = 1 order by order_num, id';
		$_d = array('parent_id' => 1);
		$_f = array('i');
		$rows = $this->_dbh->_fetch_db_rows_p($sql, $_d, $_f);
		return $rows;
	}

	private function _fetch_section_menu_items($t){
		$sql = "select * from _app_nav_routes where parent_id = :parent_id and display = 1 and archived = 0 order by order_num, id";
		$_d = array('parent_id' => $t);
		$_f = array('i', 'i');
		$items = $this->_dbh->_fetch_db_rows_p($sql, $_d, $_f);
		return $items;
	}

	public function _get_topic_order(){
		return $this->_topic_order;
	}
}