<?php
class _topic_tab{
	protected $_dbh;
	protected $_item_id;
	protected $_items;
	protected $_make_item_tab = false;
	protected $_rows;
	protected $_topic_id;
	protected $_count;
	protected $_is_logged_in;
	protected $_tpl_parent;
	protected $_tpl_head;
	protected $_tpl_head_detail;
	protected $_tpl_sub;
	protected $_oc_class;
	protected $_del_header_params;

	public function __construct($_tid){
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
		$this->_is_logged_in = is_logged_in();

		if ($this->_is_logged_in){
			$this->_tpl_parent = __s_app_url__.'_classes/_templates/_parent_template_admin.txt';
			$this->_tpl_head = __s_app_url__.'_classes/_templates/_head_template_admin.txt';
		}else{
			$this->_tpl_parent = __s_app_url__.'_classes/_templates/_parent_template_user.txt';
			$this->_tpl_head = __s_app_url__.'_classes/_templates/_head_template_user.txt';
		}
	}


	protected function _build_items(){
		$tmp = "";
		if ($this->_is_logged_in){
			$tmp .= "<script>$(document).ready(function(e){";
			$tmp .= _build_del_header($this->_del_params, true);
			$tmp .= _build_add_new($this->_add_new_params, 'jq');
			$tmp .= "});";
			$tmp .= "</script>";
			$tmp .= _build_add_new($this->_add_new_params, 'btn');
		}
		$this->_items = $tmp . $this->_fetch_head_template($this->_tpl_head);
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
			$_inner .= $this->_fetch_head_template($this->_tpl_head, $_r);
			$this->_count++;
		}
		$this->_items = str_replace('{_tab_items}', $_inner, $this->_items);
	}


}