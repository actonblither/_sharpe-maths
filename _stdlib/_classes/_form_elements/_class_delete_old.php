<?php
class _delete extends _setup{

	private $_img_class = 'del_item';
	private $_id_prefix = 'img';
	private $_id_prefix_length = 3;
	private $_main_db_tbl;//string
	private $_main_db_tbl_field = 'id';
	private $_main_db_tbl_field_value;
	private $_sub_db_tbls = array();
	private $_sub_db_tbl_fields = array();
	private $_parent_list_id;
	private $_head_list_id;
	private $_sub_list_id;
	private $_add_script_tags = false;
	private $_add_document_ready = false;



	public function __construct($_params){
		parent::__construct();
		$this->_main_db_tbl = rvs($_params['main_db_tbl']);
		$this->_main_db_tbl_field_value = rvs($_params['main_db_tbl_field_value']);
		$this->_sub_db_tbls = rva($_params['sub_db_tbls']);
		$this->_sub_db_tbl_fields = rva($_params['sub_db_tbl_fields']);
		$this->_img_class = rvs($_params['image_class']);
		$this->_parent_list_id = rvs($_params['parent_list_id']);
		$this->_head_list_id = rvs($_params['head_list_id']);
		$this->_sub_list_id = rvs($_params['sub_list_id']);
		$this->_add_script_tags = rvb($_params['add_script_tags']);
		$this->_add_document_ready = rvb($_params['add_document_ready']);
	}

	public function _delete_img(){
		$tmp = "<div><img id = '".$this->_id_prefix.$this->_main_db_tbl_field_value."' class = 'w14 h14 ".$this->_img_class." point m5' src = '".__s_lib_url__."_images/_icons/close14.png' /></div>";
		return $tmp;
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
					if (_sure('Are you sure you want to delete this item?')){
					e.preventDefault();
					e.stopImmediatePropagation();
					var id = $(this).attr('id').substring(".$this->_id_prefix_length.");
					var fd = new FormData();
					fd.append('app_folder', '".base64_encode(__s_app_folder__)."');
					fd.append('main_db_tbl', '".$this->_main_db_tbl."');
					fd.append('main_db_tbl_field', '".$this->_main_db_tbl_field."');
					fd.append('main_db_tbl_field_value', id);
					fd.append('sub_db_tbls', '".json_encode($this->_sub_db_tbls)."');
					fd.append('sub_db_tbl_fields', '".json_encode($this->_sub_db_tbl_fields)."');
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
							$('#".$this->_head_list_id."' + id).remove();";
		if (!empty($this->_sub_list_id)){
			$tmp .= "$('#".$this->_sub_list_id."' + id).remove();";
		}
			$tmp .= "}
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

	public function _get_img_class() { return $this->_img_class; }
	public function _get_id_prefix() { return $this->_id_prefix; }
	public function _get_id_prefix_length() { return $this->_id_prefix_length; }
	public function _get_main_db_tbl() { return $this->_main_db_tbl; }
	public function _get_main_db_tbl_field() { return $this->_main_db_tbl_field; }
	public function _get_sub_db_tbls() { return $this->_sub_db_tbls; }
	public function _get_sub_db_tbl_fields() { return $this->_sub_db_tbl_fields; }
	public function _get_main_db_tbl_field_value() { return $this->_main_db_tbl_field_value; }
	public function _get_main_list_id() { return $this->_main_list_id; }
	public function _get_sub_list_id() { return $this->_sub_list_id; }
	public function _get_add_script_tags() { return $this->_add_script_tags; }
	public function _get_add_document_ready() { return $this->_add_document_ready; }

	public function _set_img_class($_t) { $this->_img_class = $_t; }
	public function _set_id_prefix($_t) { $this->_id_prefix = $_t; }
	public function _set_id_prefix_length($_t) { $this->_id_prefix_length = $_t; }
	public function _set_main_db_tbl($_t) { $this->_main_db_tbl = $_t; }
	public function _set_main_db_tbl_field($_t) { $this->_main_db_tbl_field = $_t; }
	public function _set_sub_db_tbls($_t) { $this->_sub_db_tbls = $_t; }
	public function _set_sub_db_tbl_fields($_t) { $this->_sub_db_tbl_fields = $_t; }
	public function _set_main_db_tbl_field_value($_t) { $this->_main_db_tbl_field_value = $_t; }
	public function _set_main_list_id($_t) { $this->_main_list_id = $_t; }
	public function _set_sub_list_id($_t) { $this->_sub_list_id = $_t; }
	public function _set_add_script_tags($_t) { $this->_add_script_tags = $_t; }
	public function _set_add_document_ready($_t) { $this->_add_document_ready = $_t; }

}




