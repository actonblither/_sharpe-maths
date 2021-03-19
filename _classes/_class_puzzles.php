<?php
class _puzzle extends _topic_tab{
	protected $_main_db_tbl = '_app_puzzles';
	protected $_parent_list_id = 'puzzles';
	protected $_head_list_id = 'pzh';
	protected $_sub_list_id = 'pzi';
	protected $_item_name = 'puzzle';
	protected $_item_class = '_puzzle';
	protected $_del_img_class = 'del_m_pz';
	protected $_title_prefix = 'Puzzle';
	protected $_title_field_name = 'pz_title';
	protected $_open_close_id_prefix = 'p';
	protected $_sr;//search replace array

	public function __construct($_tid){
		parent::__construct($_tid);
		$this->_dbh = new _db();
		$this->_topic_id = $_tid;
		$this->_is_logged_in = is_logged_in();
		if ($this->_is_logged_in){
			$this->_tpl_sub = __s_app_url__.'_classes/_templates/_admin_puzzle_sub_tpl.txt';
		}else{
			$this->_tpl_sub = __s_app_url__.'_classes/_templates/_user_puzzle_sub_tpl.txt';
		}

		$this->_item_sql = 'select p.* from _app_puzzles p left join _app_puzzle_topic_link pt on pt.puzzle_id = p.id where p.display = :display and p.archived = :archived and pt.topic_id = :topic_id order by p.difficulty';


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
				'_item_title' => rvs($_r['pz_title']),
				'_item_name' => $this->_item_name,
				'_pz_puzzle' => rvs($_r['pz_puzzle']),
				'_pz_solution' => rvs($_r['pz_solution']),
				'_pz_explanation' => rvs($_r['pz_explanation'])
		);

		$_page = file_get_contents($_tpl);
		foreach ($this->_sr as $_key => $_value){
			$_page = str_replace('{'.$_key.'}', $_value, $_page);
		}
		return $_page;
	}



}
?>