<?php
class _add_new_topic_item extends _setup{

	private $_db_tbl;
	private $_topic_id;
	private $_item_name;
	private $_item_class_name;
	private $_parent_list_id;
	private $_admin_template;
	private $_field_prefix;


	public function __construct($_params){
		parent::__construct();
		$this->_db_tbl = rvs($_params['db_tbl']);
		$this->_topic_id = rvz($_params['topic_id']);
		$this->_item_name = rvs($_params['item_name']);
		$this->_item_class = rvs($_params['item_class']);
		$this->_parent_list_id = rvs($_params['parent_list_id']);
		$this->_admin_template = rvs($_params['admin_template']);
		$this->_field_prefix = rvs($_params['field_prefix']);
	}


	public function _build_add_new_btn(){
		$tmp = "<button type = 'button' class = 'add_new mb5' data-db-tbl = '".$this->_db_tbl."' data-topic-id = '".$this->_topic_id."' data-item-name = '".$this->_item_name."' data-item-class-name = '".$this->_item_class."' data-admin-template = '".$this->_admin_template."' data-parent-list-name = '".$this->_parent_list_id."' data-field-prefix = '".$this->_field_prefix."'>Add new ".$this->_item_name."</button>";
		return $tmp;
	}
}




