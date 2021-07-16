<?php
class _article extends _topic_tab{
	protected $_main_db_tbl = '_app_topic_art';
	protected $_parent_list_id = 'articles';
	protected $_head_list_id = 'arh';
	protected $_sub_list_id = 'ari';
	protected $_item_name = 'article';
	protected $_item_class = '_article';
	protected $_del_img_class = 'del_m_art';
	protected $_title_prefix = 'Article';
	protected $_title_field_name = 'tart_title';
	protected $_field_prefix = 'tart_';
	protected $_open_close_id_prefix = 'u';
	protected $_sr;//search replace array
	protected $_sortable_list_prefix = 'nar';

	public function __construct($_tid){
		parent::__construct($_tid);
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
		$this->_is_logged_in = is_logged_in();

		if ($this->_is_logged_in){
			$this->_tpl_head = __s_app_folder__.'_classes/_templates/_admin_article_head_tpl.txt';
		}else{
			$this->_tpl_head = __s_app_folder__.'_classes/_templates/_user_article_head_tpl.txt';
		}

		$this->_sub_body = true;
		$this->_build_items();
	}

	protected function _build_edit_elements($_art){
		$this->_header_edit_elements .= parent::_build_edit_elements($_art);
		$_el = new _form_element();
		$_el->_set_el_field_id('tart_body');
		$_el->_set_el_field_value($_art['tart_body']);
		$_el->_set_db_tbl('_app_topic_art');
		$_el->_set_el_id_value($_art['id']);
		$_el->_set_el_width(100);
		$_el->_set_el_height(400);
		$_el->_set_el_width_units('%');
		$_el->_set_el_height_units('px');
		$this->_CKEditor = $_el->_build_ckeditor();

		$_el_btn = new _form_element();
		$_el_btn->_set_db_tbl('_app_topic_art');
		$_el_btn->_set_el_id_value($_art['id']);
		$_el_btn->_set_el_field_id('tart_body');
		$_el_btn->_set_el_field_value('Save article');
		$_el_btn->_set_el_width(200);
		$_el_btn->_set_el_width_units('px');
		$this->_CKEditor .= $_el_btn->_build_save_btn();
	}

	public function _fetch_template($_tpl, $_r = array()){
		if (!$this->_is_logged_in){
			$_tips = new _tips(rvs($_r['tart_body']), $this->_topic_id);
			$_title = "<h2>".rvs($_r['tart_title'])."</h2>";
			$_r['tart_body'] = $_title.$_tips->_get_return_txt();
		}
		$this->_sr = array(
				'_title_field_name' => $this->_title_field_name,
				'_title_prefix' => $this->_title_prefix,
				'_parent_list_id' => $this->_parent_list_id,
				'_head_list_id' => $this->_head_list_id,
				'_sub_list_id' => $this->_sub_list_id,
				'_open_close_id_prefix' => $this->_open_close_id_prefix,
				'_main_db_tbl' => $this->_main_db_tbl,
				'_item_id' => $this->_item_id,
				'_topic_id' => $this->_topic_id,
				'_list_count' => $this->_list_count,
				'_del_class' => $this->_del_img_class,
				'_icon_lib_url' => __s_lib_icon_url__,
				'_icon_app_url' => __s_app_icon_url__,
				'_occ_class' => $this->_occ_class,
				'_oco_class' => $this->_oco_class,
				'_item_title' => rvs($_r['tart_title']),
				'_item_name' => $this->_item_name,
				'_body' => rvs($_r['tart_body']),
				'_field_prefix' => $this->_field_prefix,
				'_sortable_list_prefix' => $this->_sortable_list_prefix,
				'_CKEditor' => rv($this->_CKEditor)
		);

		return $this->_fetch_template_file($_tpl);
	}



}
?>