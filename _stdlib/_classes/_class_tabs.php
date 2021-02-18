<?php
//These are in-page soft tabs
class _tabs{
	private $_tab_nav_divs;//string
	private $_tab_labels;//array
	private $_tab_links;//array
	private $_tab_pages;//array

	public function __construct(){}

	public function _build_all(){
		return $this->_build_jq().$this->_build_tabs().$this->_build_pages();
	}

	public function _build_search(){
		return $this->_build_jq().$this->_build_tabs();
	}

	public function _build_tabs(){
		$count = 1;
		$tmp = "
		<nav id = '".$this->_tab_nav_id."' class = 'sub-tabs' aria-label = 'sub-tabs'>";
		for($i = 0; $i < count($this->_tab_labels); $i++){
			$tmp .= "<div class = 'tab-gap'><div class = 'gap-a'></div><div class = 'base'></div></div>";
			$tmp .= "<div class = 'tab-container'>";
			$tmp .= "<a id = '_".$count."_".$this->_tab_links[$i]."' class = 'tab-nav";
			if ($count == 1){ $tmp .= " tab-active' ";}else{$tmp .= "' ";}
			$tmp .= "href = '#".$this->_tab_links[$i]."'>".$this->_tab_labels[$i]."</a>";
			$tmp .= "<div class = 'base'></div>";
			$tmp.= "</div>";
			$count++;
		}
		$tmp .= "<div class = 'tab-gap'><div class = 'gap-a'></div><div class = 'base'></div></div>";
		$tmp .= '</nav>';
		return $tmp;
	}

	public function _build_jq(){
		$tmp = "<script>
				$(document).ready(function(){
					var cookie = readCookie('tab-pref');
					if (!cookie){
						cookie = '_1_intro-1';
						createCookie('tab-pref', cookie, 100);
					}
					if ($('#'+cookie).length > 0){
						$('#".$this->_tab_nav_id." a.tab-active').removeClass('tab-active');
						$('#'+cookie).addClass('tab-active');
					}

					$(document).on('click', '#".$this->_tab_nav_id." a.tab-nav', function(e){
						e.preventDefault();
						e.stopImmediatePropagation();
						///Deal with the tabs
						var old_section_id = $('#".$this->_tab_nav_id." a.tab-active').attr('href');

						$('#".$this->_tab_nav_id." a.tab-active').removeClass('tab-active');
						$(this).addClass('tab-active');
						///Now deal with the <div> visibility
						$(old_section_id).addClass('hidden');
						var new_section_id = $(this).attr('href');
						$(new_section_id).addClass('tab-active').removeClass('hidden');
						createCookie('tab-pref', $(this).attr('id'), 0);
					});
				});
			</script>";
		return $tmp;
	}//end _get_jq



	public function _build_pages(){
		if (isset($_COOKIE['tab-pref'])){
			$cookie = $_COOKIE['tab-pref'];
			$_cookie_tab = substr($cookie, 3);
			if (in_array($_cookie_tab, $this->_tab_links)){
				$_active_tab = $_cookie_tab;
			}else{
				$_active_tab = $this->_tab_links[0];
			}
		}else{
			$_active_tab = $this->_tab_links[0];
		}

		$tmp = "
		<div class = '".$this->_tab_nav_divs."'>";
		for($i = 0; $i < count($this->_tab_labels); $i++){
			$tmp .= "<div class = 'tab-contents";
			if ($this->_tab_links[$i] === $_active_tab){$tmp .= "'";}else{$tmp .= " hidden'";}
			$tmp .= " id = '".$this->_tab_links[$i]."'>";
			$tmp .= $this->_tab_pages[$i];
			$tmp .= "</div>";
		}
		$tmp .= "</div>";
		return $tmp;
	}

	//Setters
	public function _set_tab_nav_id($n){
		$this->_tab_nav_id = $n;
		$this->_tab_nav_divs = $n.'-divs';
	}

	public function _set_tab_labels($n){$this->_tab_labels = $n;}
	public function _set_tab_help($n){$this->_tab_help = $n;}
	public function _set_tab_links($n){$this->_tab_links = $n;}
	public function _set_tab_pages($n){$this->_tab_pages = $n;}

}//end class