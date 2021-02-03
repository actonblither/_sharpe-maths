<?php
class _open_navbar extends _list_form_app{
	private $_section_rows;

	public function __construct(){
		parent::__construct();
		include_once(__s_lib_folder__.'/_classes/_navbar/_templates/_navbar_template.php');
	}

	private function _fetch_home_menu_entry(){
		$_home_row = $this->_fetch_home_item();
		$tmp = "<div class = 'nav-link home text ";
		if ($this->_main === 'home'){$tmp .= ' selected';}
		$tmp .= " bg-img home20' ";
		$tmp .= 'data-link = "index.php?main=';
		$tmp .= $_home_row['main']."\"><span class = 'text'>".$_home_row['title']."</span></div>\n\n";
		return $tmp;
	}

	private function _fetch_menu_sections(){
		$tmp = '';
		for ($i=0; $i<count($this->_section_rows); $i++){
			$tmp .= "<div id = '".$this->_section_rows[$i]['main']."_title' class = 'title bg-img ";
			$tmp .= $this->_section_rows[$i]['class']."'><span class = 'text'>";
			$tmp .= $this->_section_rows[$i]['title']."</span></div>";
			$tmp .= "<section id = '".$this->_section_rows[$i]['main']."'>";
			$_section_menu_items = $this->_fetch_section_menu_items($this->_section_rows[$i]['id']);
			for($j=0;$j<count($_section_menu_items);$j++){
				rv($_section_menu_items[$j]['main']);
				rv($_section_menu_items[$j]['mode']);
				rv($_section_menu_items[$j]['m2']);
				rv($_section_menu_items[$j]['c_id']);

				if ($_section_menu_items[$j]['main']==$this->_main && ($_section_menu_items[$j]['mode']==$this->_mode || empty($_section_menu_items[$j]['mode']))){$sel = ' selected ';}else{$sel = ' ';}

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

	private function _fetch_logout_menu_entry(){
		$tmp = "<form method = 'post' action = 'index.php' id = 'logout_form'>
		<input type = 'hidden' name = 'main' value = 'logout' />
		<div id = 'n-logout' class = 'title bg-img logout20'><span class = 'text'>Logout</span><input class = 'hidden' type = 'submit' /></div>
		</form>";
		return $tmp;
	}

	private function _fetch_menu_visibility_code(){
		$tmp = '';
		// Once the page is loaded, read cookies to find
		// 1.	Is the menu visible?
		$tmp .= "if (readCookie('navbar') == 0 || readCookie('navbar') == null){
			$('#navbar').addClass('hidden');
		}else{";
		if ($GLOBALS['s_sticky_navbar'] == 0){
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

	private function _fetch_menu_logout_jq(){
		$tmp = "$(document).on('click', '#n-logout', function(){
			$('#logout_form').submit();
		});";
		return $tmp;
	}

	private function _fetch_menu_toggle_jq(){
		$tmp = "$(document).on('click', '#menu', function(){
			if ($('#navbar').hasClass('hidden')){
				$('#navbar').removeClass('hidden');";
		if ($GLOBALS['s_sticky_navbar'] == 1){
			$tmp .= "createCookie('navbar', 1, 5);";
		}
		$tmp .= "}else{
			$('#navbar').addClass('hidden');";
		if ($GLOBALS['s_sticky_navbar'] == 1){
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
		if ($GLOBALS['s_sticky_navbar'] == 0){
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
				$tmp .= "$(document).on('click', '#".$this->_section_rows[$i]['main']."_title', function(){";
				$tmp.= "var m = '".$this->_section_rows[$i]['main']."';";
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
		$sql = 'select main from __sys_open_navbar where parent_id = :parent_id and display = 1 and archived = 0';
		$_d = array('parent_id' => 1);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($sql, $_d, $_f);
		$tmp = '';
		if (!empty($_rows)){
			foreach ($_rows as $r){
				$tmp .= "'".$r['main']."',";
			}
		}
		$tmp=substr($tmp,0,-1);
		return $tmp;
	}

	private function _fetch_home_item(){
		$sql = "select * from __sys_open_navbar where parent_id = 0 and archived = 0 and display = 1 order by order_num, id";
		$row = $this->_dbh->_fetch_db_row($sql);
		return $row;
	}

	private function _fetch_section_rows(){
		$sql = 'select * from __sys_open_navbar where parent_id = :parent_id and archived = 0 and display = 1 order by order_num, id';
		$_d = array('parent_id' => 1);
		$_f = array('i');
		$rows = $this->_dbh->_fetch_db_rows_p($sql, $_d, $_f);
		return $rows;
	}

	private function _fetch_section_menu_items($t){
		$sql = "select * from __sys_open_navbar where parent_id = :parent_id and display = 1 and archived = 0 order by order_num, id";
		$_d = array('parent_id' => $t);
		$_f = array('i', 'i');
		$items = $this->_dbh->_fetch_db_rows_p($sql, $_d, $_f);
		return $items;
	}
}