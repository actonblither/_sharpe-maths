<?php
class _example extends _topic_tab{
	protected $_main_db_tbl = '_app_topic_eg';
	protected $_parent_list_id = 'examples';
	protected $_head_list_id = 'egh';
	protected $_sub_list_id = 'egi';
	protected $_item_name = 'example';
	protected $_item_class = '_example';
	protected $_item_sql;
	protected $_del_img_class = 'del_m_eg';
	protected $_title_prefix = 'Worked example';
	protected $_title_field_name = 'teg_title';
	protected $_field_prefix = 'teg_';
	protected $_open_close_id_prefix = 'i';
	protected $_sr;//search replace array
	protected $_sortable_list_prefix = 'neg';

	public function __construct($_tid){
		parent::__construct($_tid);
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;

		$this->_is_logged_in = is_logged_in();
		if ($this->_is_logged_in){
			$this->_tpl_sub = __s_app_url__.'_classes/_templates/_admin_example_sub_tpl.txt';
		}else{
			$this->_tpl_sub = __s_app_url__.'_classes/_templates/_user_example_sub_tpl.txt';
		}
		$this->_sub_sql = false;
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
				'_item_title' => rvs($_r['teg_title']),
				'_item_name' => $this->_item_name,
				'_question' => rvs($_r['teg_question']),
				'_question_note' => rvs($_r['teg_question_note']),
				'_method' => rvs($_r['teg_method']),
				'_method_note' => rvs($_r['teg_method_note']),
				'_answer' => rvs($_r['teg_answer']),
				'_answer_note' => rvs($_r['teg_answer_note']),
				'_field_prefix' => $this->_field_prefix,
				'_sortable_list_prefix' => $this->_sortable_list_prefix
		);

		$_page = file_get_contents($_tpl);
		foreach ($this->_sr as $_key => $_value){
			$_page = str_replace('{'.$_key.'}', $_value, $_page);
		}
		return $_page;
	}



}
?>