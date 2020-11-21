<?php
class _cfg{

	protected $_params;
	protected $_dbh;
	protected $_main;
	protected $_mode;
	protected $_m2;
	protected $_view_archive;
	protected $_cfg_title;
	protected $_id;
	protected $_a_id;
	protected $_b_id;
	protected $_c_id;
	protected $_d_id;
	protected $_e_id;
	protected $_f_id;
	protected $_v_id;
	protected $_auid;


	public function __construct(){
		$this->_dbh = new _db();
		$this->_main = rvs($_REQUEST['main']);
		$this->_mode = rvs($_REQUEST['mode']);
		$this->_m2 = rvs($_REQUEST['m2']);
		$this->_view_archive = rvz($_REQUEST['va'] ,0);

		$this->_id = rvz($_REQUEST['id']);
		$this->_a_id = rvz($_REQUEST['a_id']);
		$this->_b_id = rvz($_REQUEST['b_id']);
		$this->_c_id = rvz($_REQUEST['c_id']);
		$this->_d_id = rvz($_REQUEST['d_id']);
		$this->_e_id = rvz($_REQUEST['e_id']);
		$this->_f_id = rvz($_REQUEST['f_id']);
		$this->_v_id = rvz($_REQUEST['v_id']);

		$this->_params = array(
			'form_template' => '',
			'form_show_discard_btn_add' => true,
			'form_show_discard_btn_edit' => true,
			'form_show_title'=>true,
			'form_show_top_tab' => true,

			'list_display_all' => true,
			'list_enable_sort' => true,
			'list_show_add_btn' => true,
			'list_item_edit_target' => '_self',
			'list_own_delete' => false,
			'list_item_show_delete_icon' => true,
			'list_item_show_edit_icon' => true,
			'list_show_title' => true,

			'save_list_on_update' => true,
			'save_standard_save' => true,
			'save_validate_client' => true,
			'save_validate_on_blur' => true,
			'save_validate_server' => true,

			'gen_page_top_img'=> 'person32.png'
		);
	}


	// GETTERS
	public function _get_id() {return $this->_id;}
	public function _get_a_id() {return $this->_a_id;}
	public function _get_b_id() {return $this->_b_id;}
	public function _get_c_id() {return $this->_c_id;}
	public function _get_d_id() {return $this->_d_id;}
	public function _get_e_id() {return $this->_e_id;}
	public function _get_f_id() {return $this->_f_id;}
	public function _get_cfg_title() {return $this->_cfg_title;}
	public function _get_m2(){return $this->_m2;}
	public function _get_main(){return $this->_main;}
	public function _get_mode(){return $this->_mode;}
	public function _get_params(){return $this->_params;}


	// SETTERS
	public function _set_id($_id) {$this->_id = $_id;}
	public function _set_a_id($t) {$this->_a_id = $t;}
	public function _set_b_id($t) {$this->_b_id = $t;}
	public function _set_c_id($t) {$this->_c_id = $t;}
	public function _set_d_id($t) {$this->_d_id = $t;}
	public function _set_e_id($t) {$this->_e_id = $t;}
	public function _set_f_id($t) {$this->_f_id = $t;}
	public function _set_cfg_title($t) {$this->_cfg_title = $t;}
	public function _set_main($t){$this->_main = $t;}
	public function _set_mode($t){$this->_mode = $t;}
	public function _set_m2($t){$this->_m2 = $t;}
	public function _set_auid($t){$this->_auid = $t;}
	public function _set_params($_params){$this->_params = $_params;}
}