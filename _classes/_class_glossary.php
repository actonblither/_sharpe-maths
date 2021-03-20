<?php
class _glossary extends _topic_tab{
	protected $_main_db_tbl = '_app_glossary';
	protected $_parent_list_id = 'glossaries';
	protected $_head_list_id = 'glh';
	protected $_sub_list_id = 'gli';
	protected $_item_name = 'glossary';
	protected $_item_class = '_glossary';
	protected $_item_sql;
	protected $_del_img_class = 'del_m_gl';
	protected $_title_prefix = 'Glossary';
	protected $_title_field_name = 'title';
	protected $_field_prefix = 'glo_';
	protected $_open_close_id_prefix = 'g';
	protected $_sr;//search replace array
	protected $_sortable_list_prefix = 'ngl';

	public function __construct($_tid){
		$this->_topic_link_tbl = '_app_glossary_topic_link';
		$this->_topic_link_tbl_field = 'glossary_id';
		parent::__construct($_tid);
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;

		$this->_is_logged_in = is_logged_in();
		if ($this->_is_logged_in){
			$this->_tpl_head = __s_app_url__.'_classes/_templates/_admin_glossary_head_tpl.txt';
			$this->_tpl_sub = __s_app_url__.'_classes/_templates/_admin_glossary_sub_tpl.txt';
		}else{
			$this->_tpl_head = __s_app_url__.'_classes/_templates/_user_glossary_head_tpl.txt';
			$this->_tpl_sub = __s_app_url__.'_classes/_templates/_user_glossary_sub_tpl.txt';
		}
		$this->_item_sql = 'select g.* from _app_glossary g left join _app_glossary_topic_link gt on g.id = gt.glossary_id where gt.topic_id = :topic_id and g.display = :display and g.archived = :archived order by g.order_num, g.title, g.id';
		$this->_sub_sql = false;
		$this->_link_self_ref = true;
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
				'_item_title' => rvs($_r['title']),
				'_item_name' => $this->_item_name,
				'_body' => rvs($_r['body']),
				'_example_of_use' => rvs($_r['example_of_use']),
				'_field_prefix' => $this->_field_prefix,
				'_sortable_list_prefix' => $this->_sortable_list_prefix
		);

		if ($this->_item_id > 0){
			$_params['db_tbl_link'] = '_app_glossary_link';
			$_params['db_tbl_field1'] = 'id_1';
			$_params['db_tbl_field2'] = 'id_2';
			$_params['this_item_id'] = $this->_item_id;
			$_params['select_list_db_tbl'] = '_app_glossary';
			$_params['select_list_id_prefix'] = 'link_';
			$_p1 = new _multiple_select($_params);
			$this->_sr['_self_link_select'] = $_p1->_build_link_self_ref_select();

			$_params2['db_tbl_link'] = '_app_glossary_topic_link';
			$_params2['db_tbl_field1'] = 'topic_id';
			$_params2['db_tbl_field2'] = 'glossary_id';
			$_params2['this_item_id'] = $this->_item_id;
			$_params2['select_list_db_tbl'] = '_app_topic';
			$_params2['select_list_id_prefix'] = 'topic_link_';
			$_p2 = new _multiple_select($_params2);
			$this->_sr['_topic_link_select'] = $_p2->_build_link_select();
		}

		$_page = file_get_contents($_tpl);
		foreach ($this->_sr as $_key => $_value){
			$_page = str_replace('{'.$_key.'}', $_value, $_page);
		}
		return $_page;
	}
}



?>