<?php

class _puzzle{
	private $_dbh;
	private $_id;
	private $_topic_id;
	private $_title;
	private $_problem;
	private $_hint;
	private $_solution;
	private $_explanation;
	private $_title_icon;
	private $_pz_page_title;

	private $_icon_path = __s_app_folder__.'_images/_icons/';
	private $_icon_url =  __s_app_url__.'_images/_icons/';

	public function __construct(){
		$this->_dbh = new _db();
	}

	public function _fetch_topic_puzzles($id){
		$this->_set_pz_page_title('Topic puzzle page');
		$_sql = 'select * from _app_puzzles where display = :display and archived = :archived and topic_id = :topic_id order by difficulty';
		$_d = array('display' => 1, 'archived' => 0, 'topic_id' => $id);
		$_f = array('i', 'i', 'i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		return $this->_build_puzzle_page($_rows);
	}

	public function _fetch_all_puzzles(){
		$this->_set_pz_page_title('General puzzle page');
		$_sql = 'select * from _app_puzzles where display = :display and archived = :archived order by difficulty';
		$_d = array('display' => 1, 'archived' => 0);
		$_f = array('i', 'i');
		$_rows = $this->_dbh->_fetch_db_rows_p($_sql, $_d, $_f);
		echo $this->_build_puzzle_page($_rows);
	}

	private function _build_puzzle_page($_rows){
		$tmp = $this->_build_title_bar();

		$tmp .= "<ul id = 'puzzle_page' class = 'puzzle'>";
			if (!empty($_rows)){
				foreach ($_rows as $_r){
					$this->_id = $_r['id'];
					$this->_title = $_r['title'];
					$this->_puzzle = $_r['puzzle'];
					$this->_hint = $_r['hint'];
					$this->_solution = $_r['solution'];
					$this->_explanation = $_r['explanation'];

					$tmp .= "<li class = 'row'>";
					$tmp .= "<ul class = 'puzzle_container w100pc'>";
					$tmp .= $this->_build_li('puzzle', $this->_id, $this->_puzzle);
					$tmp .= $this->_build_hse_li($this->_id);
					$tmp .= "</ul>";

					$tmp .= "</li>";
				}
			}
		$tmp .= "</ul>";
		return $tmp;
	}

	private function _build_title_bar(){
		$_tb = new _title_bar();
		$_tb->_set_img('puzzle32.png');
		$_tb->_set_title($this->_get_pz_page_title());
		return $_tb->_build_title_bar();
	}

	private function _build_hse_li($_id){
		$tmp = "<li>
							<div class = 'w20 ml50 mr10'>".$this->_fetch_icon('hint', 'hc', $_id)."</div>
							<div class = 'w20 mr10'>".$this->_fetch_icon('solution', 'sc', $_id)."</div>
							<div class = 'w20 mr10'>".$this->_fetch_icon('explanation', 'ec', $_id)."</div>
							<div class = 'hidden mr10 cwrap vm' id = 'hc".$_id."'><span class = 'h4'>Hint: &nbsp;</span>".$this->_hint."</div>
							<div class = 'hidden mr10 vm' id = 'sc".$_id."'><span class = 'h4'>Solution: &nbsp;</span>".$this->_solution."</div>
							<div class = 'hidden mr10 vm' id = 'ec".$_id."'><span class = 'h4'>Explanation: &nbsp;</span>".$this->_explanation."</div>
						</li>";
		return $tmp;
	}

	private function _build_li($_pr, $_id, $_txt){
		$tmp = "<li id = '".$_pr.$_id."' class = 'row'>
							<div class = 'w50 c ico_bin'>".$this->_fetch_icon($_pr, null, $_id)."</div>
							<div class = 'w100pc c text mr10'>".$_txt."</div>
						</li>";
		return $tmp;
}

	private function _fetch_icon($_pr, $_pre, $_id){
		$_ico = $_pr."20.png";
		if ($_pr == 'puzzle'){$_t = 'Puzzle problem'; $_reveal = ''; $_point = '';}
		if ($_pr == 'hint'){$_t = 'Click here for a hint.'; $_reveal = '.
reveal '; $_point = ' point';}
		if ($_pr == 'solution'){$_t = 'Click here for the solution.'; $_reveal = '.
reveal '; $_point = ' point';}
		if ($_pr == 'explanation'){$_t = 'Click here for an explanation of the solution.'; $_reveal = '.
reveal '; $_point = ' point';}
		$_src = $this->_icon_url.$_ico;
		$tmp = "<img src = '".$_src."' class = '".$_reveal."w20 ttip".$_point."' title = '".$_t."' data-id = '".$_id."' data-text_div = '".$_pre."' />";
		return $tmp;
	}


	public function _get_dbh() { return $this->_dbh; }
	public function _get_id() { return $this->_id; }
	public function _get_title() { return $this->_title; }
	public function _get_problem() { return $this->_problem; }
	public function _get_hint() { return $this->_hint; }
	public function _get_solution() { return $this->_solution; }
	public function _get_explanation() { return $this->_explanation; }
	public function _get_pz_page_title() { return $this->_pz_page_title; }

	public function _set_dbh($_t) { $this->_dbh = $_t; }
	public function _set_id($_t) { $this->_id = $_t; }
	public function _set_title($_t) { $this->_title = $_t; }
	public function _set_problem($_t) { $this->_problem = $_t; }
	public function _set_hint($_t) { $this->_hint = $_t; }
	public function _set_solution($_t) { $this->_solution = $_t; }
	public function _set_explanation($_t) { $this->_explanation = $_t; }
	public function _set_pz_page_title($_t) { $this->_pz_page_title = $_t; }

}

class _title_bar{

	private $_img;
	private $_title;

	public function __construct(){}

	public function _build_title_bar(){
		$_tmp = "<div class = 'm5 page-title list-title'>";
		$_img = $this->_get_img();
		$_title = $this->_get_title();
		if (!empty($_img)){
			$_img = "_images/_title_bar_img/".$_img;
			$_img_path = __s_app_folder__.$_img;
			$_img_url = __s_app_url__.$_img;
			if (file_exists($_img_path)){
				$_tmp .= "<img src = '".$_img_url."' alt = '".ucwords($_title)."' />";
			}
		}
		$_tmp .= $_title . '</div>';
		return $_tmp;
	}

	public function _get_img() { return $this->_img; }
	public function _get_title() { return $this->_title; }

	public function _set_img($_t) { $this->_img = $_t; }
	public function _set_title($_t) { $this->_title = $_t; }
}
