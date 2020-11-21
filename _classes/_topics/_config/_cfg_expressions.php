<?php
class _cfg_expressions extends _cfg_topic_tpl{

	public function __construct(){
		parent::__construct();
		$_params = array(

			'list_item_view_url' => 'index.php?main=exp&amp;id=|pid|',
			'list_sql_code_d' => array('parent_id' => 14),
			'list_title_text' => 'Algebraic expressions topics',
			'gen_page_top_img'=> 'expressions32.png',
		);

		$this->_params = array_merge($this->_params, $_params);
	}//end __construct

}//end class
?>