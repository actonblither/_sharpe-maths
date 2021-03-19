<?php
class _add_new extends _setup{

	private $_main_db_tbl;
	private $_main_db_tbl_field_value;
	private $_sub_db_tbls;
	private $_sub_db_tbl_fields;
	private $_main_list_id;
	private $_sub_list_id;
	private $_add_script_tags;
	private $_add_document_ready;
	private $_item_name;
	private $_btn_width;
	private $_id_prefix;
	private $_id_prefix_length;
	private $_topic_id;
	private $_item_class;
	private $_admin_template;
	private $_topic_link_tbl;
	private $_topic_link_tbl_field;

	public function __construct($_params){
		parent::__construct();
		$this->_main_db_tbl = rvs($_params['main_db_tbl']);
		$this->_sub_db_tbls = rva($_params['sub_db_tbls']);
		$this->_sub_db_tbl_fields = rva($_params['sub_db_tbl_fields']);
		$this->_parent_list_id = rvs($_params['parent_list_id']);
		$this->_head_list_id = rvs($_params['head_list_id']);
		$this->_sub_list_id = rvs($_params['sub_list_id']);
		$this->_add_script_tags = rvb($_params['add_script_tags']);
		$this->_add_document_ready = rvb($_params['add_document_ready']);
		$this->_item_name = rvs($_params['item_name']);
		$this->_btn_width = rvz($_params['btn_width']);
		$this->_topic_id = rvz($_params['topic_id']);
		$this->_item_class = rvs($_params['item_class']);
		$this->_admin_template = rvs($_params['admin_template']);
		$this->_topic_link_tbl = rvs($_params['topic_link_tbl']);
		$this->_topic_link_tbl_field = rvs($_params['topic_link_tbl_field']);
	}


	public function _build_add_new_btn(){
		$tmp = "<button type = 'button' class = 'add_new_".$this->_item_name." add w".$this->_btn_width." mb5' id = 'to".$this->_topic_id."'>Add new ".$this->_item_name."</button>";
		return $tmp;
	}

	public function _build_add_new_jq(){
		$this->_id_prefix_length = strlen($this->_id_prefix);
		$tmp = '';
		if ($this->_add_script_tags){
			$tmp .= "<script>";
		}
		if ($this->_add_document_ready){
			$tmp .= "$(document).ready(function(){";
		}
		$tmp .= "$(document).on('click', '.add_new_".$this->_item_name."', function(e){
					if (_sure('Are you sure you want to add a new ".$this->_item_name."?')){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(".$this->_id_prefix_length.");
					var fd = new FormData();
					fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
					fd.append('main_db_tbl', '".$this->_main_db_tbl."');
					fd.append('item_class', '".$this->_item_class."');
					fd.append('main_db_tbl_field_value', id);
					fd.append('topic_id', ".$this->_topic_id.");
					fd.append('sub_db_tbls', '".json_encode($this->_sub_db_tbls)."');
					fd.append('sub_db_tbl_fields', '".json_encode($this->_sub_db_tbl_fields)."');
					fd.append('admin_template', '".$this->_admin_template."');
					fd.append('topic_link_tbl', '".$this->_topic_link_tbl."');
					fd.append('topic_link_tbl_field', '".$this->_topic_link_tbl_field."');
					$.ajax({
						type: 'POST',
						async : true,
						cache : false,
						processData	: false,
						contentType	: false,
						url: '".__s_lib_url__."_ajax/_add_record.php',
						data: fd,
						dataType: 'json',
						success: function (data) {
							$('ul#".$this->_parent_list_id."').append(data);
						}
					});
				}
			});";
		if ($this->_add_document_ready){
			$tmp .= "});";
		}
		if ($this->_add_script_tags){
			$tmp .= "</script>";
		}
		return $tmp;
	}

	public function _get_id_prefix() { return $this->_id_prefix; }
	public function _get_id_prefix_length() { return $this->_id_prefix_length; }
	public function _get_main_db_tbl() { return $this->_main_db_tbl; }
	public function _get_main_db_tbl_field() { return $this->_main_db_tbl_field; }
	public function _get_sub_db_tbls() { return $this->_sub_db_tbls; }
	public function _get_sub_db_tbl_fields() { return $this->_sub_db_tbl_fields; }
	public function _get_main_db_tbl_field_value() { return $this->_main_db_tbl_field_value; }
	public function _get_parent_list_id() { return $this->_parent_list_id; }
	public function _get_head_list_id() { return $this->_head_list_id; }
	public function _get_sub_list_id() { return $this->_sub_list_id; }
	public function _get_add_script_tags() { return $this->_add_script_tags; }
	public function _get_add_document_ready() { return $this->_add_document_ready; }

	public function _set_id_prefix($_t) { $this->_id_prefix = $_t; }
	public function _set_id_prefix_length($_t) { $this->_id_prefix_length = $_t; }
	public function _set_main_db_tbl($_t) { $this->_main_db_tbl = $_t; }
	public function _set_main_db_tbl_field($_t) { $this->_main_db_tbl_field = $_t; }
	public function _set_sub_db_tbls($_t) { $this->_sub_db_tbls = $_t; }
	public function _set_sub_db_tbl_fields($_t) { $this->_sub_db_tbl_fields = $_t; }
	public function _set_main_db_tbl_field_value($_t) { $this->_main_db_tbl_field_value = $_t; }
	public function _set_parent_list_id($_t) { $this->_parent_list_id = $_t; }
	public function _set_head_list_id($_t) { $this->_head_list_id = $_t; }
	public function _set_sub_list_id($_t) { $this->_sub_list_id = $_t; }
	public function _set_add_script_tags($_t) { $this->_add_script_tags = $_t; }
	public function _set_add_document_ready($_t) { $this->_add_document_ready = $_t; }

}




