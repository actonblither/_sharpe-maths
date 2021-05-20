<?php
class _pages extends _setup{

	private $_page_text;
	private $_page_route_id;
	private $_page_id;
	private $_page_title;
	private $_page_body;

	public function __construct($_id = 1){
		parent::__construct();
		if ($_id){
			$this->_page_route_id = $_id;
			$this->_fetch_page_id();
			$this->_fetch_page_title();
			$this->_fetch_page_body();
		}
	}

	public function _build_page(){
		if (is_logged_in()){
			return $this->_page_title.$this->_build_edit_page();
		}else{
			return $this->_build_page_start().$this->_page_title.$this->_page_body.$this->_build_page_end();
		}
	}

	public function _build_admin_page_list(){
		$_tmp = "<div id = 'page-admin'><button class='add_new add w150 ml10' data-db-tbl = '__sys_pages' data-prefix = 'page'>Add new page</button>";

		$_sql = 'select id, title from __sys_pages where archived = :archived order by title';
		$_d = array('archived' => 0);
		$_f = array('i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		$_tmp .= "<ul class='item-admin p0'>";
		if (!empty($_rows)){
			foreach ($_rows as $_r){
				$_del_img_path = __s_lib_icon_url__."close14.png";
				$_del_div = "<div class='w20 row center'><img class = 'del-item' src='".$_del_img_path."' alt='Delete' data-id = '".$_r['id']."' data-db-tbl = '__sys_pages' /></div>";
				$_r['title'] = $this->_hlf->_build_text_box('title', $_r['title'], '__sys_pages', $_r['id'], 400, 'Page title...');
				$_tmp .= "<li id = 'pa".$_r['id']."' class='row expand p0'>".$_del_div."<div>".$_r['title']."</div></li>";
			}
		}

		$_tmp .= "</ul></div>";
		return $_tmp;
	}

	public function _build_empty_template(){
		if (!is_logged_in()) die();
		return $this->_parse_admin_template_list();
	}

	private function _parse_admin_template_list(){
		$_sql = "insert into __sys_pages set display=:display";
		$_d = array('display' => 1);
		$_f = array('i');

		$_pid = $this->_dbh->_insert_sql($_sql, $_d, $_f);

		$_link_data = " data-main = 'page' data-db-tbl='__sys_pages' data-sort-list-prefix='topli' data-del-list = '1'";

		$_del_img_path = __s_lib_icon_url__."close14.png";
		$_del_div = "<div class='w20 row center'><img class = 'del-item' src='".$_del_img_path."' alt='Delete' data-id = '".$_pid."' data-db-tbl = '__sys_pages' /></div>";

		$_title = $this->_hlf->_build_text_box('title', '', '__sys_pages', $_pid, 390, 'Page title...');

		$_content_divs = "<div class='w400 row center'>".$_title."</div>";

		$tmp = "<ul id = 'topul".$_pid."' class='nav-menu-admin'>";
		$tmp .= "<li id='topli".$_pid."' class='row link point' data-id = '".$_pid."' ".$_link_data.">".$_del_div.$_content_divs;
		$tmp .= "</li></ul>";
		return $tmp;
	}

	private function _build_edit_page(){
		$_el = new _form_element();
		$_el->_set_el_field_id('body');
		$_el->_set_el_field_value($this->_page_body);
		$_el->_set_db_tbl('__sys_pages');
		$_el->_set_el_id_value($this->_page_id);
		$_el->_set_el_width(100);
		$_el->_set_el_height(100);
		$_el->_set_el_width_units('%');
		$_el->_set_el_height_units('%');

		$_el_btn = new _form_element();
		$_el_btn->_set_db_tbl('__sys_pages');
		$_el_btn->_set_el_id_value($this->_page_id);
		$_el_btn->_set_el_field_id('body');
		$_el_btn->_set_el_field_value('Save page');
		$_el_btn->_set_el_width(120);
		$_el_btn->_set_el_field_class('mb15');
		$_el_btn->_set_el_width_units('px');

		return $_el_btn->_build_save_btn().$_el->_build_ckeditor();
	}

	private function _build_page_start(){
		$tmp = "<section class = 'cwrap'>";
		return $tmp;
	}

	private function _build_page_end(){
		$tmp = "</section>";
		return $tmp;
	}

	private function _fetch_page_id(){
		$_sql = "select page_id from _app_nav_routes where id = :id";
		$_d = array('id' => $this->_page_route_id);
		$_f = array('i');
		$this->_page_id = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
	}

	private function _fetch_page_title(){
		$_sql = "select title from __sys_pages where id = :id";
		$_d = array('id' => $this->_page_id);
		$_f = array('i');
		$_page_title = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
		$_title = new _title_bar();
		$_title->_set_title($_page_title);
		$_title->_set_img('glossary32.png');
		$_title->_set_img_alt('Glossary');
		$this->_page_title = $_title->_build_title_bar();
	}


	private function _fetch_page_body(){
		$_sql = 'select body from __sys_pages where id = :id';
		$_d = array('id' => $this->_page_id);
		$_f = array('i');
		$this->_page_body = $this->_dbh->_fetch_db_datum_p($_sql, $_d, $_f);
	}

}