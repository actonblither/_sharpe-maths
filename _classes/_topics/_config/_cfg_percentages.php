<?php
class _cfg_percentages extends _cfg_topic_tpl{

	public function __construct(){
		parent::__construct();
		$_params = array(

			'list_item_view_url' => 'index.php?main=perc&amp;id=|pid|',
			'list_sql_code_d' => array('parent_id' => 11),
			'list_title_text' => 'Percentage topics',
			'gen_page_top_img'=> 'perc32.png',
		);

		$this->_params = array_merge($this->_params, $_params);
	}//end __construct

}//end class
?>