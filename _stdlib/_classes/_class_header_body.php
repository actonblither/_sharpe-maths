<?php
class _header_body{
	private $_left;
	private $_navbar;
	private $_switch;
	private $_navburger_src;

	public function __construct(){
		if (rvs($_COOKIE['navbar'], 'off') === 'on'){
			$this->_navburger_src = __s_lib_url__.'_images/_icons/close50.png';
			$this->_navbar = true;

		}else{
			$this->_navburger_src = __s_lib_url__.'_images/_icons/menu50.png';
			$this->_navbar = false;

		}
		if (rvs($_COOKIE['nav-position'], 'l') === 'l'){$this->_left = true;}else{$this->_left = false;}
	}

	public function _build_header(){
		if ($this->_left){
			$tmp = $this->_build_navburger_switch();
			$tmp .= $this->_build_user_info();
			$tmp .= $this->_build_main_logo();
		}else{
			$tmp = $this->_build_main_logo();
			$tmp .= $this->_build_user_info();
			$tmp .= $this->_build_navburger_switch();
		}
		return $tmp;
	}

	public function _build_body(){
		if ($this->_left){
			$tmp = $this->_build_body_nav();
			$tmp .= $this->_build_body_maincol();
		}else{
			$tmp = $this->_build_body_maincol();
			$tmp .= $this->_build_body_nav();
		}
		return $tmp;
	}

	public function _build_body_nav(){
		$tmp = "<nav id = 'navbar' ";
		if (!$this->_navbar){$tmp .= " class = 'hidden'";}
		if ($this->_left){$tmp .= " class = 'mr2 ml0'";}else{$tmp .= " class = 'ml2 mr0'";}
		$tmp .= ">";
		$nav = new _navmenu();
		$tmp .= $nav->_get_navmenu();
		$_SESSION['s_topic_order'] = $nav->_get_topic_order();
		$tmp .= "</nav>";
		return $tmp;
	}

	public function _build_body_maincol(){
		$tmp = "<div id = 'maincol'>";
		ob_start();
		include_once(__s_app_folder__.'main_menu.php');
		$tmp .= ob_get_clean();
		$tmp .= "</div>";
		return $tmp;
	}

	private function _build_navburger_switch(){
		$tmp = "<div class = 'row center w60 hauto'>
							<img id = 'navburger' width = '50' height = '50' class = 'point ttip mr10 ml10 mt5 w40 h40' src = '".$this->_navburger_src."' alt = 'Menu' title = 'Toggle the navigation menu' />
						</div>";
		return $tmp;
	}

	private function _build_user_info(){
		$tmp = "<div id = 'user-info' class = 'pb4 ml50 mr50'><div id = 'now-date' class = 'mr20'></div><div id = 'user-name'></div><div id = 'user-priv' class = 'ml20 mr5'></div><div id = 'logout' class = 'link point ml20 mr5 logout-link' data-id='90' data-main='page'></div></div>";
		return $tmp;
	}

	private function _build_main_logo(){
		$tmp = "<div>
<img id = 'main-logo' class = 'ttip mr10 ml10' width = '348' height = '30' src = '".__s_app_url__."_images/app_logo.png' alt = '".__s_app_title__."' title = '".__s_app_title__."' />
</div>";
		return $tmp;
	}


}

