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

	public function __construct($_tid){
		parent::__construct($_tid);
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
		$this->_is_logged_in = is_logged_in();


		$this->_sub_instructions = false;
		$this->_sub_body = true;
		$this->_build_items();
	}

	public function _fetch_template($_tpl, $_r = array()){
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
				'_field_prefix' => $this->_field_prefix
		);
		$_page = file_get_contents($_tpl);
		foreach ($this->_sr as $_key => $_value){
			$_page = str_replace('{'.$_key.'}', $_value, $_page);
		}
		return $_page;
	}



}
?>