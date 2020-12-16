<?php
class _delete extends _setup{

	private $_img_class = 'del_item';
	private $_id_prefix = 'img';
	private $_id_prefix_length = 3;
	private $_db_main_tbl;//string
	private $_db_main_tbl_field = 'id';
	private $_db_sub_tbls = array();
	private $_db_sub_tbl_fields = array();
	private $_db_tbl_field_value;
	private $_list_id = 'ul#q';
	private $_sub_list_id;
	private $_add_script_tags = false;
	private $_add_document_ready = false;


	public function __construct(){
		parent::__construct();
	}

	public function _delete_img(){
		if (!empty($this->_img_class)){
			$_ic = $this->_img_class." ";
			$tmp = "<div><img id = '".$this->_id_prefix.$this->_db_tbl_field_value."' class = 'w14 h14 ".$_ic." point m5' src = '".__s_lib_url__."_images/_icons/close14.png' /></div>";
			return $tmp;
		}
	}

	public function _delete_jq(){
		$this->_id_prefix_length = strlen($this->_id_prefix);
		$tmp = '';
		if ($this->_add_script_tags){
			$tmp .= "<script>";
		}
		if ($this->_add_document_ready){
			$tmp .= "$(document).ready(function(){";
		}
		$tmp .= "$(document).on('click', '.".$this->_img_class."', function(e){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(".$this->_id_prefix_length.");
					var fd = new FormData();
					fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
					fd.append('db_main_tbl', '".$this->_db_main_tbl."');
					fd.append('db_main_tbl_field', '".$this->_db_main_tbl_field."');
					fd.append('db_tbl_field_value', id);
					fd.append('db_sub_tbls', '".json_encode($this->_db_sub_tbls)."');
					fd.append('db_sub_tbl_fields', '".json_encode($this->_db_sub_tbl_fields)."');
					$.ajax({
						type: 'POST',
						async : true,
						cache : false,
						processData	: false,
						contentType	: false,
						url: '".__s_lib_url__."_ajax/_delete_records.php',
						data: fd,
						dataType: 'json',
						success: function (data) {
							$('".$this->_list_id."'+id).remove();";
		if (!empty($this->_sub_list_id)){
			$tmp .= "$('".$this->_sub_list_id."'+id).remove();";
		}
			$tmp .= "}
					});
				});";
		if ($this->_add_document_ready){
			$tmp .= "});";
		}
		if ($this->_add_script_tags){
			$tmp .= "</script>";
		}
		return $tmp;
	}

	public function _get_img_class() { return $this->_img_class; }
	public function _get_id_prefix() { return $this->_id_prefix; }
	public function _get_id_prefix_length() { return $this->_id_prefix_length; }
	public function _get_db_main_tbl() { return $this->_db_main_tbl; }
	public function _get_db_main_tbl_field() { return $this->_db_main_tbl_field; }
	public function _get_db_sub_tbls() { return $this->_db_sub_tbls; }
	public function _get_db_sub_tbl_fields() { return $this->_db_sub_tbl_fields; }
	public function _get_db_tbl_field_value() { return $this->_db_tbl_field_value; }
	public function _get_list_id() { return $this->_list_id; }
	public function _get_sub_list_id() { return $this->_sub_list_id; }
	public function _get_add_script_tags() { return $this->_add_script_tags; }
	public function _get_add_document_ready() { return $this->_add_document_ready; }

	public function _set_img_class($_t) { $this->_img_class = $_t; }
	public function _set_id_prefix($_t) { $this->_id_prefix = $_t; }
	public function _set_id_prefix_length($_t) { $this->_id_prefix_length = $_t; }
	public function _set_db_main_tbl($_t) { $this->_db_main_tbl = $_t; }
	public function _set_db_main_tbl_field($_t) { $this->_db_main_tbl_field = $_t; }
	public function _set_db_sub_tbls($_t) { $this->_db_sub_tbls = $_t; }
	public function _set_db_sub_tbl_fields($_t) { $this->_db_sub_tbl_fields = $_t; }
	public function _set_db_tbl_field_value($_t) { $this->_db_tbl_field_value = $_t; }
	public function _set_list_id($_t) { $this->_list_id = $_t; }
	public function _set_sub_list_id($_t) { $this->_sub_list_id = $_t; }
	public function _set_add_script_tags($_t) { $this->_add_script_tags = $_t; }
	public function _set_add_document_ready($_t) { $this->_add_document_ready = $_t; }

}




