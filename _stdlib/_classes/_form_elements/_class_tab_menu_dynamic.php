<?php
class _tab_menu_dynamic extends _app_lib{

	protected $_params;
	private $_selected_field_array;
	private $_tab_str;
	private $_new_tab_str;
	private $_show_add_btn;

	public function __construct(){
		parent::__construct();
		$this->_tab_str = '';
	}

	public function _build_all(){
		$this->_build_open_container();
		$this->_build_static_a_link_tabs();

		if ($this->_form_show_add_tab){$this->_build_add_tab();}
		$this->_build_close_container();
	}

	private function _build_open_container(){
		$this->_tab_str .= "
			<nav class = 'main-tabs' aria-label='Search menu'>
				<div class = 'variable-tab'>
					<div class = 'tab-gap'>
						<div class = 'gap-a'></div>
						<div class = 'base'></div>
					</div>";
	}

	private function _build_close_container(){
		$this->_tab_str .= '</nav>';
	}

	private function _build_static_a_link_tabs(){
		if (!empty($this->_params['title'])){
			for ($i=0; $i < count($this->_params['title']); $i++){
				$this->_tab_str .= "<div class = 'tab-container'>";
				$this->_selected_field_array = explode('_', $this->_params['field'][$i]);
				$this->_tab_str .= $this->_build_tab_a_link($i);
				$this->_tab_str .= "<div class = 'base'></div></div>".PHP_EOL;
				$this->_build_numbered_tab_gap($i);
			}
			$this->_tab_str .= '</div>';
		}
	}

	public function _build_dynamic_tab_btn($_params){
		return "
				<div class = 'tab-container'>
					<a class = 'ttip ".$_params['class']." curr_sel'
						href = '".$_params['link']."'
						title = '".rv($_params['help'])."'>".rv($_params['title'])."
					</a>
					<div class = 'base'></div>
				</div>
				<div class = 'tab-gap ".$_params['class']."'>
					<div class = 'gap-a'></div>
					<div class = 'base'></div>
				</div>".PHP_EOL;
	}

	private function _build_numbered_tab_gap($i){
		$this->_tab_str .= "
			<div class = 'tab-gap ".$this->_params['class'][$i]."'>
				<div class = 'gap-a'></div>
				<div class = 'base'></div>
			</div>".PHP_EOL;
	}

	private function _build_tab_gap(){
		$this->_tab_str .= "
			<div class = 'tab-gap'>
				<div class = 'gap-a'></div>
				<div class = 'base'></div>
			</div>".PHP_EOL;
	}

	private function _build_tab_a_link($i){
		$str = "<a class = 'ttip ".$this->_params['class'][$i];
		$str .= $this->_is_selected(' ');
		$str .= "'";
		$str .= " href = '".$this->_params['link'][$i]."'";
		$str .= " title = '".rv($this->_params['help'][$i])."'>".rv($this->_params['title'][$i]).'</a>'.PHP_EOL;
		return $str;
	}

	private function _build_add_tab(){
		$this->_tab_str .= "
			<div class = 'variable-tab'>
				<div class = 'tab-container'>
					<button id = 'add_new_tab'
						class = 'tab_add_btn point ttip'
						title = 'Add new interview stage'
						type = 'button'>+
					</button>
					<div class = 'base'></div>
				</div>
			</div>";
	}

	private function _is_selected($space = ''){
		if (in_array($this->_mode, $this->_selected_field_array)){return $space.'curr_sel';}
	}



	// GETTERS & SETTERS
	public function _get_tab_str(){return $this->_tab_str;}


}

