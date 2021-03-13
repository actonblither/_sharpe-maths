<?php
class _example{
	private $_dbh;
	private $_item_id;
	private $_items;
	private $_make_item_tab = false;
	private $_rows;
	private $_topic_id;
	private $_count;
	private $_is_logged_in;
	private $_tpl_parent;
	private $_tpl_head;
	private $_tpl_sub;
	private $_oc_class;
	private $_del_header_params;
	private $_main_db_tbl = '_app_topic_eg';
	private $_parent_list_id = 'examples';
	private $_head_list_id = 'egh';
	private $_sub_list_id = 'egi';
	private $_item_name = 'example';
	private $_item_class = '_example';
	private $_del_img_class = 'del_m_eg';
	private $_title_prefix = 'Worked example';
	private $_title_field_name = 'teg_title';
	private $_open_close_id_prefix = 'i';

	public function __construct($_tid){
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
		$this->_is_logged_in = is_logged_in();
		if ($this->_is_logged_in){
			$this->_tpl_parent = __s_app_url__.'_classes/_templates/_parent_template_admin.txt';
			$this->_tpl_head = __s_app_url__.'_classes/_templates/_example_head_admin.txt';
		}else{
			$this->_tpl_parent = __s_app_url__.'_classes/_templates/_parent_template_user.txt';
			$this->_tpl_head = __s_app_url__.'_classes/_templates/_example_head_user.txt';
		}

		$this->_del_params = array(
			'main_db_tbl' => $this->_main_db_tbl,
			'image_class' => $this->_del_img_class,
			'add_script_tags' => false,
			'add_document_ready' => false,
			'head_list_id' => $this->_head_list_id,
			'sub_list_id' => $this->_sub_list_id
		);

		$this->_add_new_params = array(
				'main_db_tbl' => $this->_main_db_tbl,
				'add_script_tags' => false,
				'add_document_ready' => false,
				'parent_list_id' => $this->_parent_list_id,
				'head_list_id' => $this->_head_list_id,
				'sub_list_id' => $this->_sub_list_id,
				'topic_id' => $this->_topic_id,
				'btn_width' => 150,
				'item_class' => $this->_item_class,
				'item_name' => $this->_item_name
		);

		$this->_build_items();
	}

	private function _build_items(){
		$tmp = "";
		if ($this->_is_logged_in){
			$tmp .= "<script>$(document).ready(function(e){";
			$tmp .= _build_del_header($this->_del_params, true);
			$tmp .= _build_add_new($this->_add_new_params, 'jq');
			$tmp .= "});";
			$tmp .= "</script>";
			$tmp .= _build_add_new($this->_add_new_params, 'btn');
		}
		$this->_items = $tmp . $this->_fetch_template($this->_tpl_parent);
		$_inner = '';
		$_sql = 'select * from '.$this->_main_db_tbl.' where topic_id = :topic_id order by order_num, id';
		$_d = array('topic_id' => $this->_topic_id);
		$_f = array('i');
		$this->_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		if (!empty($this->_rows) || $this->_is_logged_in){$this->_make_item_tab = true;}
		$this->_count = 1;
		foreach ($this->_rows as $_r){
			$this->_item_id = $_r['id'];
			$_oc = rvs($_COOKIE[$this->_sub_list_id.$this->_item_id], 'closed');
			$this->_oc_class = ($_oc == 'open') ? '' : ' hidden';
			$_inner .= $this->_fetch_template($this->_tpl_head, $_r);
			$this->_count++;
		}
		$this->_items = str_replace('{_tab_items}', $_inner, $this->_items);
	}

	private function _fetch_head_template($_tpl, $_r = array()){
		$_page = file_get_contents($_tpl);

		$_search_array = array('{_title_field_name}', '{_title_prefix}', '{_parent_list_id}', '{_head_list_id}', '{_sub_list_id}', '{_open_close_id_prefix}', '{_main_db_tbl}', '{_item_id}', '{_topic_id}', '{_count}', '{_icon_lib_url}', '{_icon_app_url}', '{_oc_class}', '{_item_title}');
		$_replace_array = array($this->_title_field_name, $this->_title_prefix, $this->_parent_list_id, $this->_head_list_id, $this->_sub_list_id, $this->_open_close_id_prefix, $this->_main_db_tbl, $this->_item_id, $this->_topic_id, $this->_count, __s_lib_icon_url__, __s_app_icon_url__, $this->_oc_class, rvs($_r[$this->_title_field_name]));

		for ($_i=0; $_i<count($_search_array); $_i++){
			$_page = str_replace($_search_array[$_i], $_replace_array[$_i], $_page);
		}
		return $_page;
	}

	private function _fetch_template($_tpl, $_r = array()){
		$_page = file_get_contents($_tpl);

		$_search_array = array('{_title_field_name}', '{_title_prefix}', '{_parent_list_id}', '{_head_list_id}', '{_sub_list_id}', '{_open_close_id_prefix}', '{_main_db_tbl}', '{_item_id}', '{_topic_id}', '{_count}', '{_icon_lib_url}', '{_icon_app_url}', '{_oc_class}', '{_item_title}', '{_question}', '{_question_note}', '{_method}', '{_method_note}', '{_answer}', '{_answer_note}');
		$_replace_array = array($this->_title_field_name, $this->_title_prefix, $this->_parent_list_id, $this->_head_list_id, $this->_sub_list_id, $this->_open_close_id_prefix, $this->_main_db_tbl, $this->_item_id, $this->_topic_id, $this->_count, __s_lib_icon_url__, __s_app_icon_url__, $this->_oc_class, rvs($_r['teg_title']), rvs($_r['teg_question']), rvs($_r['teg_question_note']), rvs($_r['teg_method']), rvs($_r['teg_method_note']), rvs($_r['teg_answer']), rvs($_r['teg_answer_note']));

		for ($_i=0; $_i<count($_search_array); $_i++){
			$_page = str_replace($_search_array[$_i], $_replace_array[$_i], $_page);
		}
		return $_page;
	}

	public function _build_new_item($_id){
		$_page = file_get_contents($this->_tpl_inner);
		$_page = str_replace('{_item_id}', $_id, $_page);
		$_page = str_replace('{_topic_id}', $this->_topic_id, $_page);
		$_page = str_replace('{_count}', 'new', $_page);
		$_page = str_replace('{_icon_lib_url}', __s_lib_icon_url__, $_page);
		$_page = str_replace('{_icon_app_url}', __s_app_icon_url__, $_page);
		$_page = str_replace('{_oc_class}', $this->_oc_class, $_page);
		$_page = str_replace('{_item_title}', '', $_page);
		$_page = str_replace('{_question}', '', $_page);
		$_page = str_replace('{_question_note}', '', $_page);
		$_page = str_replace('{_method}', '', $_page);
		$_page = str_replace('{_method_note}', '', $_page);
		$_page = str_replace('{_answer}', '', $_page);
		$_page = str_replace('{_answer_note}', '', $_page);
		return $_page;
	}

	public function _get_items() { return $this->_items; }
	public function _get_make_item_tab() { return $this->_make_item_tab; }
}
?>