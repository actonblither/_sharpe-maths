<?php
class _cfg_alg_frac extends _cfg_topic_tpl{

	public function __construct(){
		parent::__construct();
		$_params = array(

			'list_item_view_url' => 'index.php?main=afrac&amp;id=|pid|',
			'list_sql_code_d' => array('parent_id' => 23),
			'list_title_text' => 'Algebraic fraction topics',
			'gen_page_top_img'=> 'afrac32.png',
		);

		$this->_params = array_merge($this->_params, $_params);
	}//end __construct

}//end class
?>