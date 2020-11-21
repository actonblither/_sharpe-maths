<?php
class _cfg_topic_tpl extends _cfg_app{

	public function __construct(){
		parent::__construct();
		$_params = array(

			'list_item_view_target' => '_self',
			'list_enable_sort' => false,
			'list_sql_code' => 'select * from _app_nav_routes where display = 1 and archived = 0 and parent_id = :parent_id order by order_num',
			'list_sql_code_f' => array('i'),
			'list_fields' => array(
				's::20::left::title::Topic title::filter',
				's::30::left::body::Description::filter',
				's::30::left::content::Contents::filter::'
			),
			'list_li_id_prefix' => 'dd',
			'list_title_filter_label' => 'Topic filter:',
			'gen_title_identifier_sql' => 'title',
			'gen_table' => '_app_nav_routes'
		);

		$this->_params = array_merge($this->_params, $_params);
	}//end __construct

	public function _get_params(){return $this->_params;}


}//end class
?>